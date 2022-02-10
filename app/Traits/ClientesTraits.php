<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Customers;
use App\Http\Controllers\StripeController;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Rates;
use App\Models\TypesRate;
use App\Models\Dates;
use App\Models\CustomersNotes;
use App\Models\CustomersRates;
use App\Models\Charges;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

trait ClientesTraits {

  public function index(Request $request, $month = false) {
    if (!$month)
      $month = date('n');

    $year = getYearActive();
    $months = lstMonthsSpanish(false);
    unset($months[0]);

    $detail = [];
    $payments = $noPay = 0;
    $status = isset($request->status) ? $request->status : 1;
    if ($status == 'all') {
      $sqlUsers = Customers::whereNotNull('id');
    } else {
      $sqlUsers = Customers::where('status', $status);
    }
    $customers = $sqlUsers->orderBy('name', 'asc')->get();
    $customerIDs = $sqlUsers->pluck('id');
    //---------------------------------------------//
    $aRates = [];
    $typeRates = TypesRate::pluck('name', 'id');
    $oRates = Rates::all();
//    $oRates = Rates::whereIn('type', $typeRates)->get();
    $rPrices = $rNames = [];
    if ($oRates) {
      foreach ($oRates as $r) {
        $aRates[$r->id] = $r;
        $rPrices[$r->id] = $r->price;
        $rNames[$r->id] = $r->name;
        if (isset($typeRates[$r->type])) {
          $rNames[$r->id] = $typeRates[$r->type] . '<br>' . $r->name;
        }
      }
    }

    //---------------------------------------------//
    $arrayPaymentMonthByUser = array();
    $date = date('Y-m-d', strtotime($year . '-' . $month . '-01' . ' -1 month'));
    $toPay = $cRates = $uCobros = [];
    $total_pending = 0;
    $monthAux = date('m', strtotime($date));
    $yearAux = date('Y', strtotime($date));
    for ($i = 0; $i < 3; $i++) {
      $resp = $this->getRatesByMonth($monthAux, $yearAux, $customerIDs, $rPrices, $rNames);
      $cRates[$i] = $resp[0];
      $toPay[$i] = $resp[2];
      $noPay += $resp[2];
      $detail[] = $resp[3];
      $next = strtotime($date . ' +1 month');
      $date = date('Y-m-d', $next);
      $monthAux = date('m', $next);
      $yearAux = date('Y', $next);
    }

    if (count($detail) > 0) {
      $aux = '';
      foreach ($detail as $item) {
        foreach ($item as $k => $d) {
          $aux .= $k . ':{';
          foreach ($d as $k2 => $i2) {
            $aux .= "$k2: '$i2',";
          }
          $aux .= '},';
        }
      }
      $detail = "{ $aux }";
    } else {
      $detail = null;
    }
    return view('/admin/usuarios/clientes/index', [
        'customers' => $customers,
        'month' => $month,
        'year' => $year,
        'status' => $status,
        'toPay' => $toPay,
        'noPay' => $noPay,
        'cRates' => $cRates,
        'detail' => $detail,
        'months' => $months,
//        'ausers' => $ausers,
        'total_pending' => array_sum($arrayPaymentMonthByUser),
    ]);
  }

  public function getRatesByMonth($month, $year, $customerIDs, $rPrices, $rNames) {

    $detail = [];
    $RateIDs = array_keys($rPrices);
    $cRates = CustomersRates::whereIN('customer_id', $customerIDs)
            ->where('rate_year', $year)
            ->where('rate_month', $month)
            ->whereIn('rate_id', $RateIDs)
            ->with('charges')
            ->get();

    $payments = $noPay = 0;
    $uLstRates = [];
    if ($cRates) {
      /*       * ****************************** */
      $aDates = Dates::whereIn('customers_rate_id', $cRates->pluck('id'))
                      ->pluck('date', 'customers_rate_id')->toArray();

      foreach ($cRates as $k => $v) {
        $idRate = $v->rate_id;
        $idUser = $v->customer_id;
        if (!isset($uLstRates[$idUser])) {
          $uLstRates[$idUser] = [];
        }
        if (!isset($uLstRates[$idUser][$idRate])) {
          $uLstRates[$idUser][$idRate] = [];
        }



        $dateCita = '';
        if (isset($aDates[$v->id])) {
          $auxDate = explode(' ', $aDates[$v->id]);
          $dateCita = dateMin($auxDate[0]);
        }


        $detail[$v->id] = [
            'n' => '',
            'p' => moneda($rPrices[$idRate]),
            's' => $rNames[$idRate],
            'mc' => '', //Metodo pago
            'dc' => '', // fecha pago
            'date' => $dateCita
        ];
        // si esta pagado
        $auxCharges = $v->charges;
        if ($auxCharges) {
          $uLstRates[$idUser][$idRate][] = [
              'price' => $auxCharges->import,
              'id' => $v->id,
              'paid' => true,
              'cid' => $v->charge_id
          ];
          $payments += $auxCharges->import;
          $detail[$v->id]['mc'] = payMethod($auxCharges->type_payment);
          $detail[$v->id]['dc'] = dateMin($auxCharges->date_payment);
        } else {
          $importe = $v->price;
//          $importe = ($v->price === null) ? $rPrices[$idRate]:$v->price;
          $noPay += $importe;

          $uLstRates[$idUser][$idRate][] = [
              'price' => $importe,
              'id' => $v->id,
              'paid' => false,
              'cid' => -1,
          ];
        }
      }
    }
    return [$uLstRates, $payments, $noPay, $detail];
  }

  public function clienteRateCharge($cRateID) {
    $cRates = CustomersRates::find($cRateID);
    if (!$cRates) {
      return view('admin.popup_msg', ['msg' => 'Servicio no asignada']);
    }
    $oCustomer = $cRates->customer;
    $oRates = $cRates->rate;
    
    return view('/admin/usuarios/clientes/cobro', [
        'rate' => $oRates,
        'customer' => $oCustomer,
        'importe' => ($cRates->price == null) ? $oRates->price : $cRates->price,
        'year' => $cRates->rate_year,
        'month' => $cRates->rate_month,
        'id_appointment' => $cRates->id_appointment,
        'cRate' => $cRates->id,
        'user_id' => $cRates->user_id,
        'allUsers' => User::getUsersWithRoles()
    ]);
  }

  public function informe($id, $tab = 'datos') {
    $year = getYearActive();
    $months = lstMonthsSpanish(false);
    unset($months[0]);
    $customer = Customers::find($id);
    $customerID = $customer->id;

    $lstMetas = $customer->getMetaContentGroups(['costComercial', 'costAlquiler', 'costTotal']);

    $typeRates = TypesRate::pluck('name', 'id');
    $aRates = $rPrices = $rNames = [];
//    $oRates = Rates::where('status', 1)->get();
    $oRates = Rates::all();

    if ($oRates) {
      foreach ($oRates as $k => $v) {
        $aRates[$v->id] = $v;
        $rPrices[$v->id] = $v->price;
        $rNames[$v->id] = $v->name;
        if (isset($typeRates[$v->type])) {
          $rNames[$v->id] = $typeRates[$v->type] . '<br>' . $v->name;
        }
      }
    }
    //----------------------//
    foreach ($months as $k => $v) {
      $totalUser[$k] = 0;
      $totalUserNPay[$k] = 0;
    }

    //----------------------//
    $oDates = Dates::where('customer_id', $customerID)->OrderBy('date')->get();
    //----------------------//
    $oNotes = CustomersNotes::where('customer_id', $customerID)->OrderBy('created_at')->get();
    //----------------------//
    $oCharges = Charges::where('customer_id', $customerID)
                    ->pluck('import', 'id')->toArray();

    $uLstRates = [];
    $usedRates = $detail = [];
    $cRateIds = CustomersRates::where('customer_id', $customerID)
                    ->where('rate_year', $year)
                    ->pluck('rate_id')->toArray();
    if ($cRateIds) {
      $cRateIds = array_unique($cRateIds);
      foreach ($cRateIds as $rid)
        if (isset($aRates[$rid]))
          $usedRates[$rid] = $aRates[$rid]->name;
    }

    $uLstRates = [];
    if ($cRateIds) {
      for ($i = 1; $i < 13; $i++) {
        $resp = $this->getRatesByMonth($i, $year, [$id], $rPrices, $rNames);
        $uLstRates[$i] = (count($resp[0])) ? $resp[0][$id] : [];
        $totalUser[$i] = $resp[1];
        $totalUserNPay[$i] = $resp[2];
        $detail[] = $resp[3];
        $uLstRates[$i]['bonos'] = [];
      }
    }

    //*************************************************//
    //----------------------//
    $oRatesSubsc = Rates::select('rates.*', 'types_rate.type')
                    ->join('types_rate', 'rates.type', '=', 'types_rate.id')
                    ->whereIn('types_rate.type', ['gral', 'pt'])->get();

    $subscrLst = $customer->suscriptions;
    //----------------------//
    $aUsers = User::whereBy_role('teach')->orderBy('name')->pluck('name', 'id')->toArray();
    $allUsers = User::getUsersWithRoles();
    //----------------------//
    // CONSENTIMIENTOS

    $fileName = $customer->getMetaContent('sign_fisioIndiba');
    $fisioIndiba = false;
    if ($fileName) {
      $path = storage_path('/app/' . $fileName);
      $fisioIndiba = File::exists($path);
    }

    $fileName = $customer->getMetaContent('sign_sueloPelvico');
    $sueloPelvico = false;
    if ($fileName) {
      $path = storage_path('/app/' . $fileName);
      $sueloPelvico = File::exists($path);
    }
    //----------------------//
    //Invoices
    $invoices = \App\Models\Invoices::whereYear('date', '=', $year)
                    ->where('customer_id', $customerID)
                    ->orderBy('date', 'DESC')->get();
    $totalInvoice = $invoices->sum('total_price');
    $invoiceModal = true;
    //----------------------//
    //----------------------//
    //Invoices
    $valoracion = $this->get_valoracion($customer);
    //----------------------//

    if (count($detail) > 0) {
      $aux = '';
      foreach ($detail as $item) {
        foreach ($item as $k => $d) {
          $aux .= $k . ':{';
          foreach ($d as $k2 => $i2) {
            $aux .= "$k2: '$i2',";
          }
          $aux .= '},';
        }
      }
      $detail = "{ $aux }";
    } else {
      $detail = null;
    }

    /*     * ***************************** */

    $encNutr = $customer->getMetaContent('nutri_q1');
    $code = encriptID($customer->id) . '-' . encriptID(time() * rand());
    $btnEncuesta = $code . '/' . getKeyControl($code);

    /*     * ***************************** */
    return view('/admin/usuarios/clientes/informe', [
        'aRates' => $aRates,
        'atypeRates' => $typeRates,
        'rNames' => $rNames,
        'usedRates' => $usedRates,
        'uLstRates' => $uLstRates,
        'totalUser' => $totalUser,
        'totalUserNPay' => $totalUserNPay,
        'subscrLst' => $subscrLst,
        'subscrRates' => $oRatesSubsc,
        'months' => $months,
        'detail' => $detail,
        'year' => $year,
        'customer' => $customer,
        'aUsers' => $aUsers,
        'allUsers' => $allUsers,
        'oDates' => $oDates,
        'oNotes' => $oNotes,
        'tab' => $tab,
        'fisioIndiba' => $fisioIndiba,
        'sueloPelvico' => $sueloPelvico,
        'invoices' => $invoices,
        'totalInvoice' => $totalInvoice,
        'invoiceModal' => $invoiceModal,
        'valora' => $valoracion,
        'u_current' => Auth::user()->id,
        'encNutr' => $encNutr,
        'btnEncuesta' => $btnEncuesta,
        'lstMetas' => $lstMetas,
    ]);
  }

  public function exportClients() {
    return Excel::download(new UsersExport, 'clientes_' . date('Y_m_d') . '.xlsx');
  }

  public function rateCharge(Request $request) {
    $stripe = null;
    $oCustomer = Customers::find($request->customer_id);
    $rateFamily = \App\Models\Rates::getTypeRatesGroups(false);

    return view('admin.usuarios.clientes._rate_charge', [
        'customer' => $oCustomer,
        'users' => User::getusers(),
        'rates' => Rates::orderBy('status', 'desc')->orderBy('name', 'asc')->get(),
        'rateFamily' => $rateFamily,
    ]);
  }

  public function addNotes(Request $request) {
    $uID = $request->input('uid');
    $id = $request->input('id');
    $note = $request->input('note');
    $idCoach = $request->input('coach', Auth::user()->id);
    $oCoach = User::find($idCoach);
    $oNote = null;
    if ($id > 0)
      $oNote = CustomersNotes::find($id);
    if (!$oNote) {
      $oNote = new CustomersNotes();
      $oNote->customer_id = $uID;
    }

    $oNote->user_id = $idCoach;
    $oNote->type = ($oCoach) ? $oCoach->role : '';
    $oNote->note = $note;
    $oNote->save();

    return redirect('/admin/cliente/informe/' . $uID . '/notes')->with(['success' => 'Nota Guardada']);
  }

  public function delNotes(Request $request) {
    $uID = $request->input('uid');
    $id = $request->input('id');
    $oNote = CustomersNotes::find($id);
    if ($oNote) {
      if ($oNote->delete()) {
        return redirect('/admin/cliente/informe/' . $uID . '/notes')->with(['success' => 'Nota eliminada']);
      }
    }

    return redirect('/admin/cliente/informe/' . $uID . '/notes')->withErrors(['Nota no eliminada']);
  }

  function getSign($file) {

    $path = storage_path('/app/signs/' . $file);
    if (!File::exists($path)) {
      abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = \Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
  }

}
