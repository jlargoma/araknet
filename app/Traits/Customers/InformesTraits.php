<?php

namespace App\Traits\Customers;

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
use App\Models\CustomersHnts;

trait InformesTraits {

  public function index(Request $request, $month = false) {
    if (!$month)
      $month = date('n');

    $year = getYearActive();
    $months = lstMonthsSpanish();
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

    $resp = $this->getRatesByYear($year, $customerIDs, $rPrices, $rNames);
    $cRates = $resp[0];
    $toPay = $resp[2];
    $noPay = array_sum($resp[2]);
    $detail = $resp[3];
    $custPays = $resp[4];
    if (count($detail) > 0) {
      $aux = '';
      foreach ($detail as $k => $d) {
        $aux .= $k . ':{';
        foreach ($d as $k2 => $i2) {
          $aux .= "$k2: '$i2',";
        }
        $aux .= '},';
      }
      $detail = "{ $aux }";
    } else {
      $detail = null;
    }





    return view('/admin/clientes/index', [
        'customers' => $customers,
        'month' => $month,
        'year' => $year,
        'status' => $status,
        'toPay' => $toPay,
        'noPay' => $noPay,
        'cRates' => $cRates,
        'detail' => $detail,
        'months' => $months,
        'aHntsDash' => $this->getHntsDashboard(),
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

      /* ------------------------------------------- */
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

  public function getRatesByYear($year, $customerIDs, $rPrices, $rNames) {

    $detail = [];
    $RateIDs = array_keys($rPrices);
    $cRates = CustomersRates::whereIN('customer_id', $customerIDs)
            ->where('rate_year', $year)
            ->whereIn('rate_id', $RateIDs)
            ->with('charges')
            ->get();

    $payments = $noPay = $uLstRates = $custPays = [];
    for ($i = 1; $i < 13; $i++) {
      $payments[$i] = 0;
      $noPay[$i] = 0;
      $uLstRates[$i] = [];
    }
    if ($cRates) {

      /* ------------------------------------------- */
      $aDates = Dates::whereIn('customers_rate_id', $cRates->pluck('id'))
                      ->pluck('date', 'customers_rate_id')->toArray();

      foreach ($cRates as $k => $v) {
        $idRate = $v->rate_id;
        $idCust = $v->customer_id;
        $aAux = [];

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
          $aAux[] = [
              'price' => $auxCharges->import,
              'id' => $v->id,
              'paid' => true,
              'cid' => $v->charge_id
          ];
          $payments[$v->rate_month] += $auxCharges->import;
          $detail[$v->id]['mc'] = payMethod($auxCharges->type_payment);
          $detail[$v->id]['dc'] = dateMin($auxCharges->date_payment);

          if (!isset($custPays[$idCust]))
            $custPays[$idCust] = 0;
          $custPays[$idCust] += $auxCharges->import;
        } else {
          $importe = $v->price;
//          $importe = ($v->price === null) ? $rPrices[$idRate]:$v->price;
          $noPay[$v->rate_month] += $importe;

          $aAux[] = [
              'price' => $importe,
              'id' => $v->id,
              'paid' => false,
              'cid' => -1,
          ];
        }

        if (!isset($uLstRates[$v->rate_month][$idCust])) {
          $uLstRates[$v->rate_month][$idCust] = [];
        }
        if (!isset($uLstRates[$v->rate_month][$idCust][$idRate])) {
          $uLstRates[$v->rate_month][$idCust][$idRate] = [];
        }
        $uLstRates[$v->rate_month][$idCust][$idRate] = $aAux;
      }
    }
    return [$uLstRates, $payments, $noPay, $detail, $custPays];
  }

  public function clienteRateCharge($cRateID) {
    $cRates = CustomersRates::find($cRateID);
    if (!$cRates) {
      return view('admin.popup_msg', ['msg' => 'Servicio no asignada']);
    }
    $oCustomer = $cRates->customer;
    $oRates = $cRates->rate;

    return view('/admin/clientes/cobro', [
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
    // CONTRACTS
    $ContractsService = new \App\Services\ContractsService();
    $lstContracts = $ContractsService->getContracts();
    foreach ($lstContracts as $kCont => $contract) {
      $fileName = $customer->getMetaContent('file_' . $kCont);
      if ($fileName) {
        $path = storage_path('/app/' . $fileName);
        if (File::exists($path)) {
          $lstContracts[$kCont]['signed'] = true;
        }
      }
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

    //---------------------------------------------//


    /*     * ***************************** */
    return view('/admin/clientes/informe', [
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
        'lstContracts' => $lstContracts,
        'invoices' => $invoices,
        'totalInvoice' => $totalInvoice,
        'invoiceModal' => $invoiceModal,
        'valora' => $valoracion,
        'u_current' => Auth::user()->id,
        'encNutr' => $encNutr,
        'btnEncuesta' => $btnEncuesta,
        'lstMetas' => $lstMetas,
        'hnts' => $this->getHnts($customer->id, $customer->hotspot_date)
    ]);
  }

  public function exportClients() {
    return Excel::download(new UsersExport, 'clientes_' . date('Y_m_d') . '.xlsx');
  }

  public function rateCharge(Request $request) {
    $stripe = null;
    $oCustomer = Customers::find($request->customer_id);
    $rateFamily = \App\Models\Rates::getTypeRatesGroups(false);

    return view('admin.clientes._rate_charge', [
        'customer' => $oCustomer,
        'users' => User::getusers(),
        'rates' => Rates::orderBy('status', 'desc')->orderBy('name', 'asc')->get(),
        'rateFamily' => $rateFamily,
    ]);
  }

  public function addNotes(Request $request) {
    $cID = $request->input('c_id');
    $id = $request->input('id');
    $note = $request->input('note');
    $idUser = intVal($request->input('uid'));
    if (!$idUser)
      $idUser = Auth::user()->id;
    $oUser = User::find($idUser);
    $oNote = null;
    if ($id > 0)
      $oNote = CustomersNotes::find($id);
    if (!$oNote) {
      $oNote = new CustomersNotes();
      $oNote->customer_id = $cID;
    }

    $oNote->user_id = $idUser;
    $oNote->type = ($oUser) ? $oUser->role : '';
    $oNote->note = $note;
    $oNote->save();

    return redirect('/admin/cliente/informe/' . $cID . '/notes')->with(['success' => 'Nota Guardada']);
  }

  public function delNotes(Request $request) {
    $cID = $request->input('c_id');
    $id = $request->input('id');
    $oNote = CustomersNotes::find($id);
    if ($oNote) {
      if ($oNote->delete()) {
        return redirect('/admin/cliente/informe/' . $cID . '/notes')->with(['success' => 'Nota eliminada']);
      }
    }

    return redirect('/admin/cliente/informe/' . $cID . '/notes')->withErrors(['Nota no eliminada']);
  }

  function getLinkContracts(Request $request) {
    $cID = $request->input('customer_id', null);
    $type = $request->input('type', null);
    if (!$cID) {
      return response()->json(['error', 'Cliente no encontrado']);
    }
    $oCustomer = Customers::find($cID);
    if (!$oCustomer) {
      return response()->json(['error', 'Cliente no encontrado']);
    }

    $email = $oCustomer->email;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return response()->json(['error', $email . ' no es un mail válido']);
    }


    $ContractsService = new \App\Services\ContractsService();
    $oContracts = $ContractsService->getContract($type);
    if (!$oContracts)
      return response()->json(['error', 'Contrato no encontrado']);

    $link = $ContractsService->getLinkContracts($cID, $type);
    if ($link) {
      return response()->json(['OK', $link]);
    } else {
      return response()->json(['error', 'link no válido']);
    }
  }

  function seeContracts($cID, $type) {
    $sContract = new \App\Services\ContractsService();
    $contract = $sContract->getContract($type);
    if (!$contract)
      return 'Contrato no encontrado';

    $oCustomer = Customers::find($cID);
    if (!$oCustomer)
      return 'Cliente no encontrado';
    // Already Signed  -------------------------------------------
    $fileName = $oCustomer->getMetaContent('file_' . $type);
    $path = storage_path('app/' . $fileName);
    if ($fileName && File::exists($path)) {
      return response()->file($path, [
                  'Content-Disposition' => str_replace('%name', $contract['title'], "inline; filename=\"%name\"; filename*=utf-8''%name"),
                  'Content-Type' => 'application/pdf'
      ]);
    } else {
      return 'Contrato no firmado';
    }
  }

  function sendContract(Request $request) {
    $cID = $request->input('customer_id', null);
    $type = $request->input('type', null);
    if (!$cID) {
      return response()->json(['error', 'Cliente no encontrado']);
    }
    $oCustomer = Customers::find($cID);
    if (!$oCustomer) {
      return response()->json(['error', 'Cliente no encontrado']);
    }

    $email = $oCustomer->email;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return response()->json(['error', $email . ' no es un mail válido']);
    }


    $ContractsService = new \App\Services\ContractsService();
    $oContracts = $ContractsService->getContract($type);
    if (!$oContracts)
      return response()->json(['error', 'Contrato no encontrado']);

    $link = $ContractsService->getLinkContracts($cID, $type);
    if ($link) {

      $dataContent = ['contrato_link' => $link, 'contrato_nombre' => $oContracts['title']];
      $dataContent['cliente_nombre'] = $oCustomer->name;
      $dataContent['cliente_correo'] = $oCustomer->email;
      $dataContent['cliente_dni'] = $oCustomer->dni;
      $dataContent['cliente_tel'] = $oCustomer->phone;
      $dataContent['cliente_domicilio'] = $oCustomer->address . ' (' . $oCustomer->province . ')';

      $sMails = new \App\Services\MailsService();
      $sMails->sendMailBasic('contracts_mails', 'Firma de contrato Araknet', $oCustomer->email, $dataContent);

      return response()->json(['OK', 'Solicitud de firma enviada']);
    } else {
      return response()->json(['error', 'link no válido']);
    }
  }

  function getHnts($cID, $dateStart) {

    $lastDay = date('Y-m-d');
    $arrayDays = arrayDays($dateStart, $lastDay, 'Y-m-d', 0);
    $days = [];
    foreach ($arrayDays as $d => $v) {
      $days[] = substr($d, 8, 2);
    }

    $cLstHNTs = \App\Models\CustomersHnts::where('customer_id', $cID)
                    ->where('date', '>=', $dateStart)->get();
    $hnts = $arrayDays;
    if (count($cLstHNTs) > 0) {
      foreach ($cLstHNTs as $cHnt) {
        $hnts[$cHnt->date] = $cHnt->hnt;
      }
    }


    return ['"' . implode('","', $days) . '"', $hnts];
  }

  function getHntsDashboard() {


    $sHelium = new \App\Services\HeliumService();

    if (isset($_COOKIE['hBalance'])) {
      $balance = $_COOKIE['hBalance'];
    } else {
      $hAccount = $sHelium->getData_accounts();
      if ($hAccount) {
        $hAccount = $sHelium->response->data->balance;
        setcookie('hBalance', $balance, time() + (180), "/"); // 86400 = 1 day
      }
    }

    $todayHnts = CustomersHnts::where('date', date('Y-m-d'))->sum('hnt');
    $yHnts = CustomersHnts::where('date', date('Y-m-d', strtotime('-1 day')))->sum('hnt');
    $wHnts = CustomersHnts::where('date', '>=', date('Y-m-d', strtotime('-7 day')))->sum('hnt');
    $mHnts = CustomersHnts::whereYear('date', '=', date('Y'))->whereMonth('date', '=', date('m'))->sum('hnt');
    $tHotspots = Customers::whereNotNull('hotspot_imac')->count();
    if ($tHotspots < 1)
      $tHotspots = 1;

    $oHnts = CustomersHnts::where('date', '>=', date('Y-m-d', strtotime('-30 day')))->get();
    $hnt_days = [];
    if ($oHnts) {
      foreach ($oHnts as $i) {
        $d = dateMin($i->date);
        if (!isset($hnt_days[$d]))
          $hnt_days[$d] = 0;
        $hnt_days[$d] += $i->hnt;
      }
    }
    return [
        'today' => round($todayHnts, 3),
        'yest' => round($yHnts, 3),
        'week' => round($wHnts, 3),
        'month' => round($mHnts, 3),
        'avg' => round($mHnts / $tHotspots, 3),
        'hnt_days' => $hnt_days,
        'balance' => round($balance / 100000000, 3)
    ];
  }

}
