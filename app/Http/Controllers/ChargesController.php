<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\Rates;
use App\Models\UserRates;
use App\Models\UserBonos;
use App\Models\Charges;
use App\Services\ChargesService;

class ChargesController extends Controller {

  public function updateCobro(Request $request, $id) {
    $charge = Charges::find($id);

    if (!$charge)
      return view('admin.popup_msg');
    $uRate = UserRates::where('id_charges', $charge->id)->first();
    $coach_id = null;
    if ($uRate) {
      $date = getMonthSpanish($uRate->rate_month, false) . ' ' . $uRate->rate_year;
      $coach_id = $uRate->coach_id;
    } else {
      $time = strtotime($charge->date_payment);
      $date = getMonthSpanish(date('n', $time), false) . ' ' . date('Y', $time);
    }
    $oUser = $charge->user;
    return view('admin.charges.cobro_update', [
        'taxes' => Rates::all(),
        'rate' => Rates::find($charge->id_rate),
        'date' => $date,
        'user' => $oUser,
        'importe' => $charge->import,
        'charge' => $charge,
        'coach_id' => $coach_id,
        'coachs' => User::getCoachs()
    ]);
  }

  public function updateCharge(Request $request, $id) {
    $charge = Charges::find($id);
    $id_coach = $request->input('id_coach', null);
    if (!$charge) {
      return back()->withErrors(['cobro no encontrado']);
    }
    if ($request->input('deleted')) {
      $uRate = UserRates::where('id_charges', $id)->first();
      $sBonos = new \App\Services\BonoService();
      if ($uRate) {
        $uRate->id_charges = null;
        $uRate->save();
        $charge->delete();
        return redirect('/admin/clientes/generar-cobro/' . $uRate->id)->with('success', 'cobro Eliminado');
      }
      $charge->delete();
      return back()->with('success', 'cobro Eliminado');
    } else {
      $charge->import = $request->input('importe');
      if ($request->input('type_payment'))
        $charge->type_payment = $request->input('type_payment');
      if ($request->input('discount'))
        $charge->discount = $request->input('discount');
      $charge->save();
      return back()->with('success', 'cobro actualizado');
    }
  }

  public function cobrar(Request $req) {

    $id_uRate = $req->input('id_uRate', null);
    $uRate = UserRates::find($id_uRate);

    if (!$uRate) {
      return back()->withErrors(['Tarifa no encontrada']);
    }

    $time = strtotime($uRate->rate_year . '/' . $uRate->rate_month . '/01');
    $uID = $uRate->id_user;
    $rID = $uRate->id_rate;
    $tpay = $req->input('type_payment', 'cash');
    $value = $req->input('importe', 0);
    $disc = $req->input('discount', '0');
    $id_coach = $req->input('id_coach', null);
    $ChargesService = new ChargesService();
    $resp = $ChargesService->generatePayment(
            $time, $uID, $rID, $tpay, $value,
            $disc, $id_coach);

    if ($resp[0] == 'error') {
      return back()->withErrors([$resp[1]]);
    }
    if ($tpay == 'bono') {

      $UserBonos->usar($resp[2], $oDates->date_type, $oDates->date);
    }

    return redirect('/admin/update/cobro/' . $resp[2])->with('success', $resp[1]);
  }

  public function chargeUser(Request $req) {
    $month = $req->input('date_payment', null);
    $operation = $req->input('type', 'all');
    $id_coach = $req->input('id_coach', null);
    if ($month)
      $time = strtotime($month);
    else
      $time = time();
    $uID = $req->input('id_user', null);
    $rID = $req->input('id_rate', null);
    $tpay = $req->input('type_payment', 'cash');
    $value = $req->input('importe', 0);
    $disc = $req->input('discount', 0);
    $oUser = User::find($uID);
    /*     * ********************************************************* */
    $resp = ['error', 'Error al procesar su cobro'];
    if ($operation == 'all' || !$operation) {
      $ChargesService = new ChargesService();
      $resp = $ChargesService->generatePayment($time, $uID, $rID, $tpay, $value, $disc, $id_coach);
    } else {
      $u_email = $req->input('u_email', null);
      if ($u_email && $oUser->email != $u_email) {
        $oUser->email = $u_email;
        $oUser->save();
      }
      $u_phone = $req->input('u_phone', null);
      if ($u_phone && $oUser->telefono != $u_phone) {
        $oUser->telefono = $u_phone;
        $oUser->save();
      }
      return $this->generateStripeLink($time, $uID, $rID, $tpay, $value, $disc, $operation, $id_coach);
    }

    if ($resp[0] == 'error') {
      return back()->withErrors([$resp[1]]);
    }
    return back()->with('success', $resp[1]);
  }

  public static function savePayment($date, $uID, $rID, $tpay, $value, $disc, $idStripe, $cStripe) {
    $oUser = User::find($uID);
    if (!$oUser)
      return ['error', 'Usuario no encontrado'];
    $oRate = Rates::find($rID);
    if (!$oRate)
      return ['error', 'Tarifa no encontrada'];
    $dataMail = [
        'fecha_pago' => $date,
        'type_payment' => $tpay,
        'importe' => $value,
    ];
    if (!$disc)
      $disc = 0;
    //BEGIN PAYMENTS
    $oCobro = new Charges();
    $oCobro->id_user = $oUser->id;
    $oCobro->date_payment = date('Y-m-d');
    $oCobro->id_rate = $oRate->id;
    $oCobro->type_payment = $tpay;
    $oCobro->type = 1;
    $oCobro->import = $value;
    $oCobro->discount = $disc;
    $oCobro->type_rate = $oRate->type;
    $oCobro->id_stripe = $idStripe;
    $oCobro->customer_stripe = $cStripe;
    $oCobro->save();
    //END PAYMENTS
    $statusPayment = 'Pago realizado correctamente, por ' . payMethod($tpay);
    /*     * ********************************************************** */
    \App\Services\MailsService::sendEmailPayRate($dataMail, $oUser, $oRate);
    return ['OK', $statusPayment, $oCobro->id];
  }

  public function getPriceTax(Request $request) {
    $tax = Rates::find($request->idTax);
    return $tax->price;
  }

}
