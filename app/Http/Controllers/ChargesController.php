<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\Customers;
use App\Models\Rates;
use App\Models\CustomersRates;
use App\Models\UserBonos;
use App\Models\Charges;
use App\Services\ChargesService;

class ChargesController extends Controller {

  public function updateCobro(Request $request, $id) {
    $charge = Charges::find($id);

    if (!$charge)
      return view('admin.popup_msg');
    $cRate = CustomersRates::where('charge_id', $charge->id)->first();
    $user_id = null;
    if ($cRate) {
      $date = getMonthSpanish($cRate->rate_month, false) . ' ' . $cRate->rate_year;
      $user_id = $cRate->user_id;
    } else {
      $time = strtotime($charge->date_payment);
      $date = getMonthSpanish(date('n', $time), false) . ' ' . date('Y', $time);
    }
    return view('admin.charges.cobro_update', [
        'taxes' => Rates::all(),
        'rate' => Rates::find($charge->rate_id),
        'date' => $date,
        'customer' => $charge->customer,
        'importe' => $charge->import,
        'charge' => $charge,
        'user_id' => $user_id,
        'allUsers' => User::getUsersWithRoles()
    ]);
  }

  public function updateCharge(Request $request, $id) {
    $charge = Charges::find($id);
    $user_id = $request->input('user_id', null);
    if (!$charge) {
      return back()->withErrors(['cobro no encontrado']);
    }
    if ($request->input('deleted')) {
      $cRate = CustomersRates::where('charge_id', $id)->first();
      if ($cRate) {
        $cRate->charge_id = null;
        $cRate->save();
        $charge->delete();
        return redirect('/admin/clientes/generar-cobro/' . $cRate->id)->with('success', 'cobro Eliminado');
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

    $id_cRate = $req->input('id_cRate', null);
    $cRate = CustomersRates::find($id_cRate);

    if (!$cRate) {
      return back()->withErrors(['Tarifa no encontrada']);
    }

    $time = strtotime($cRate->rate_year . '/' . $cRate->rate_month . '/01');
    $uID = $cRate->customer_id;
    $rID = $cRate->rate_id;
    $tpay = $req->input('type_payment', 'cash');
    $value = $req->input('importe', 0);
    $disc = $req->input('discount', '0');
    $user_id = $req->input('user_id', null);
    $ChargesService = new ChargesService();
    $resp = $ChargesService->generatePayment(
            $time, $uID, $rID, $tpay, $value,
            $disc, $user_id);

    if ($resp[0] == 'error') {
      return back()->withErrors([$resp[1]]);
    }
    if ($tpay == 'bono') {

      $customerBonos->usar($resp[2], $oDates->date_type, $oDates->date);
    }

    return redirect('/admin/update/cobro/' . $resp[2])->with('success', $resp[1]);
  }

  public function chargeCustomer(Request $req) {
    $month = $req->input('date_payment', null);
    $operation = $req->input('type', 'all');
    $customer_id = $req->input('customer_id', null);
    $user_id = $req->input('user_id', null);
    if ($month)
      $time = strtotime($month);
    else
      $time = time();
    $rID = $req->input('rate_id', null);
    $tpay = $req->input('type_payment', 'cash');
    $value = $req->input('importe', 0);
    $disc = $req->input('discount', 0);
    $oCustomer = Customers::find($customer_id);
    /*     * ********************************************************* */
    $resp = ['error', 'Error al procesar su cobro'];
    $ChargesService = new ChargesService();
    if ($tpay == 'justAsign')
      $resp = $ChargesService->generateRate($time, $customer_id, $rID, $value, $disc, $user_id);
    else $resp = $ChargesService->generatePayment($time, $customer_id, $rID, $tpay, $value, $disc, $user_id);
    
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
    $oCobro->customer_id = $oUser->id;
    $oCobro->date_payment = date('Y-m-d');
    $oCobro->rate_id = $oRate->id;
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
