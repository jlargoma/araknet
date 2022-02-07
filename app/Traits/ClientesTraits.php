<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Controllers\StripeController;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Rates;
use App\Models\TypesRate;
use App\Models\Dates;
use App\Models\UsersNotes;
use App\Models\UserRates;
use App\Models\Charges;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

trait ClientesTraits {

  public function clientes(Request $request, $month = false) {
    if (!$month)
      $month = date('n');

    $year = getYearActive();
    $months = lstMonthsSpanish(false);
    unset($months[0]);

    $oUser = new User();
    $detail = [];
    $payments = $noPay = 0;
    $status = isset($request->status) ? $request->status : 1;
    if ($status == 'all') {
      $sqlUsers = User::where('role', 'user');
    } else {
      if ($status == 2){
        $uPlan = DB::table('user_meta')
                ->where('meta_key','plan')
                ->where('meta_value','fidelity')
                ->pluck('user_id');
        
        $sqlUsers = User::select('users.*')->where('role', 'user')
              ->where('status', 1)
              ->whereIn('id',$uPlan);
                
      } else {
        $sqlUsers = User::where('role', 'user')
              ->where('status', $status);
      }
    }
    $sqlUsers->with('userCoach');
    $users = $sqlUsers->orderBy('name', 'asc')->get();
    $userIDs = $sqlUsers->pluck('id');
    //---------------------------------------------//
    $aRates = [];
    $typeRates = TypesRate::pluck('name','id');
    $oRates = Rates::all();
//    $oRates = Rates::whereIn('type', $typeRates)->get();
    $rPrices = $rNames = [];
    if ($oRates) {
      foreach ($oRates as $r) {
        $aRates[$r->id] = $r;
        $rPrices[$r->id] = $r->price;
        $rNames[$r->id] = $r->name;
        if (isset($typeRates[$r->type])){
          $rNames[$r->id] = $typeRates[$r->type].'<br>'.$r->name;
        }
      }
    }

    //---------------------------------------------//
    $arrayPaymentMonthByUser = array();
    $date = date('Y-m-d', strtotime($year . '-' . $month . '-01' . ' -1 month'));
    $toPay = $uRates = $uCobros = [];
    $total_pending = 0;
    $monthAux = date('m', strtotime($date));
    $yearAux = date('Y', strtotime($date));
    for ($i = 0; $i < 3; $i++) {
      $resp = $this->getRatesByMonth($monthAux, $yearAux, $userIDs, $rPrices,$rNames);
      $uRates[$i] = $resp[0];
      $toPay[$i] = $resp[2];
      $noPay += $resp[2];
      $detail[] = $resp[3];
      $next = strtotime($date . ' +1 month');
      $date = date('Y-m-d', $next);
      $monthAux = date('m', $next);
      $yearAux = date('Y', $next);
    }

    if (count($detail)>0){
      $aux = '';
      foreach ($detail as $item){
        foreach ($item as $k=>$d){
          $aux .= $k.':{';
          foreach ($d as $k2=>$i2){
            $aux .= "$k2: '$i2',";
          }
          $aux .= '},';
        }
      }
      $detail = "{ $aux }";
    } else {
      $detail = null;
    }
    $aCoachs = $oUser->whereCoachs('teach')->orderBy('name')->pluck('name', 'id')->toArray();

    /**/
    $uPlan = $oUser->getMetaUserID_byKey('plan','fidelity');
    $sql = DB::table('user_meta')
            ->where('meta_key','plan')
            ->where('meta_value','basic')
            ->where('created_at','>=',date('Y-m-d', strtotime('-12 months')));
     
    $uPlanPenal =  $sql->pluck('user_id')->toArray();
//    dd($uPlanPenal);
    /**/
    return view('/admin/usuarios/clientes/index', [
        'users' => $users,
        'month' => $month,
        'year' => $year,
        'status' => $status,
        'toPay' => $toPay,
        'noPay' => $noPay,
        'uRates' => $uRates,
        'detail' => $detail,
        'months' => $months,
        'aCoachs' => $aCoachs,
        'uPlan' => $uPlan,
        'uPlanPenal' => $uPlanPenal,
        'total_pending' => array_sum($arrayPaymentMonthByUser),
    ]);
  }

  public function getRatesByMonth($month, $year, $userIDs, $rPrices, $rNames) {

    $detail = [];
    $RateIDs = array_keys($rPrices);
    $uRates = UserRates::whereIN('id_user', $userIDs)
            ->where('rate_year', $year)
            ->where('rate_month', $month)
            ->whereIn('id_rate', $RateIDs)
            ->with('charges')
            ->get();
    
    $payments = $noPay = 0;
    $uLstRates = [];
    if ($uRates) {
      /******************************** */
      $aDates = Dates::whereIn('id_user_rates',$uRates->pluck('id'))
              ->pluck('date','id_user_rates')->toArray();

      foreach ($uRates as $k => $v) {
        $idRate = $v->id_rate;
        $idUser = $v->id_user;
        if (!isset($uLstRates[$idUser])) {
          $uLstRates[$idUser] = [];
        }
        if (!isset($uLstRates[$idUser][$idRate])) {
          $uLstRates[$idUser][$idRate] = [];
        }
        
        
        
        $dateCita = '';
        if(isset($aDates[$v->id])){
          $auxDate  = explode(' ', $aDates[$v->id]);
          $dateCita = dateMin($auxDate[0]);
        }
        
        
        $detail[$v->id] = [
            'n' => '',
            'p'=>moneda($rPrices[$idRate]),
            's'=>$rNames[$idRate],
            'mc'=>'', //Metodo pago
            'dc'=>'', // fecha pago
            'date'=>$dateCita
        ];
        // si esta pagado
        $auxCharges = $v->charges;
        if ($auxCharges) {
          $uLstRates[$idUser][$idRate][] = [
              'price' => $auxCharges->import,
              'id' => $v->id,
              'paid' => true,
              'cid' => $v->id_charges
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
    return [$uLstRates,$payments,$noPay,$detail];
  }

  public function clienteRateCharge($uRateID) {
    $uRates = UserRates::find($uRateID);

    if (!$uRates) {
      return view('admin.popup_msg', ['msg' => 'Servicio no asignada']);
    }
    $oUser = $uRates->user;
    $oRates = $uRates->rate;
    /*     * *************************** */
    /** BEGIN: STRIPE              ***** */
    $pStripe = null;
    $card = null;
    $paymentMethod = $oUser->getPayCard();
    if ($paymentMethod) {
      $aux = $paymentMethod->toArray();
      $card['brand'] = $aux['card']['brand'];
      $card['exp_month'] = $aux['card']['exp_month'];
      $card['exp_year'] = $aux['card']['exp_year'];
      $card['last4'] = $aux['card']['last4'];
    }

    /** END: STRIPE              ***** */
    /*     * *************************** */

    $uPlan = $oUser->getPlan();
        
    return view('/admin/usuarios/clientes/cobro', [
        'rate' => $oRates,
        'user' => $oUser,
        'importe' => ($uRates->price == null) ? $oRates->price : $uRates->price,
        'year' => $uRates->rate_year,
        'month' => $uRates->rate_month,
        'id_appointment' => $uRates->id_appointment,
        'pStripe' => $pStripe,
        'card' => $card,
        'uRate' => $uRates->id,
        'coach_id' => $uRates->coach_id,
        'coachs' => User::getCoachs(),
        'uPlan'=>$uPlan
    ]);
  }

  public function informe($id, $tab = 'datos') {
    $year = getYearActive();
    $months = lstMonthsSpanish(false);
    unset($months[0]);
    $user = User::find($id);
    $userID = $user->id;

    $typeRates = TypesRate::pluck('name','id');
    $aRates = $rPrices = $rNames =[];
//    $oRates = Rates::where('status', 1)->get();
    $oRates = Rates::all();

    if ($oRates) {
      foreach ($oRates as $k => $v) {
        $aRates[$v->id] = $v;
        $rPrices[$v->id]= $v->price;
        $rNames[$v->id] = $v->name;
        if (isset($typeRates[$v->type])){
          $rNames[$v->id] = $typeRates[$v->type].'<br>'.$v->name;
        }
      }
    }
    //----------------------//
    foreach ($months as $k => $v) {
      $totalUser[$k] = 0;
      $totalUserNPay[$k] = 0;
    }

    //----------------------//
    $oDates = Dates::where('id_user', $userID)->OrderBy('date')->get();
    //----------------------//
    $oNotes = UsersNotes::where('id_user', $userID)->OrderBy('created_at')->get();
    //----------------------//
    $oCharges = Charges::where('id_user', $userID)
                    ->pluck('import', 'id')->toArray();

    $uLstRates = [];
    $usedRates = $detail = [];
    $uRateIds = UserRates::where('id_user', $userID)
                    ->where('rate_year', $year)
                    ->pluck('id_rate')->toArray();
    if ($uRateIds){
      $uRateIds = array_unique($uRateIds);
      foreach ($uRateIds as $rid)
        if (isset ($aRates[$rid]))
          $usedRates[$rid] = $aRates[$rid]->name;
    }
    
    $uLstRates =  [];
    if ($uRateIds) {
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
    //******  AGREGO LA COMPRA DE BONOS ***************//
    $usedRates['bonos'] = "BONOS";
    $lstBonos = \App\Models\Bonos::all()->pluck('name','id')->toArray();
    $bonoCharges = Charges::where('id_user',$userID)
            ->where('bono_id','>',0)
            ->whereYear('date_payment','=',$year)
            ->get();
    foreach ($bonoCharges as $item){
      $mounth = intVal(substr($item->date_payment, 5,2));
      $uLstRates[$mounth]['bonos'][] =  ['price'=>$item->import,'paid'=>1,'cid'=>$item->id,'id'=>'bono'];
    }
    //*************************************************//
    //----------------------//
    $oRatesSubsc = Rates::select('rates.*', 'types_rate.type')
                    ->join('types_rate', 'rates.type', '=', 'types_rate.id')
                    ->whereIn('types_rate.type', ['gral', 'pt'])->get();

    $subscrLst = $user->suscriptions;
    //----------------------//
    $aCoachs = User::whereCoachs('teach')->orderBy('name')->pluck('name', 'id')->toArray();
    $allCoachs = User::getCoachs()->pluck('name', 'id');
    //----------------------//
    // CONSENTIMIENTOS
    
    $fileName = $user->getMetaContent('sign_fisioIndiba');
    $fisioIndiba = false;
    if ($fileName){
      $path = storage_path('/app/' . $fileName);
      $fisioIndiba = File::exists($path);
    }
    
    $fileName = $user->getMetaContent('sign_sueloPelvico');
    $sueloPelvico = false;
    if ($fileName){
      $path = storage_path('/app/' . $fileName);
      $sueloPelvico = File::exists($path);
    }
    
    //----------------------//
    // TARIFAS FIDELITY
    $uPlan = $user->getPlan();
    // Already Signed  -------------------------------------------
     $sing_contrato = false;
    if ($uPlan !== null){
      $fileName = $user->getMetaContent('contrato_FIDELITY_'.$uPlan);
      $path = storage_path('app/'.$fileName);
      if ($fileName && File::exists($path)){
        $sing_contrato = true;
      }
    }
    //END: Already Signed  -------------------------------------------
    //----------------------//
    //Invoices
    $invoices = \App\Models\Invoices::whereYear('date', '=', $year)
            ->where('user_id',$userID)
            ->orderBy('date', 'DESC')->get();
    $totalInvoice = $invoices->sum('total_price');
    $invoiceModal = true;
    //----------------------//
    //----------------------//
    //Invoices
    $valoracion = $this->get_valoracion($user);
    //----------------------//

    if (count($detail)>0){
      $aux = '';
      foreach ($detail as $item){
        foreach ($item as $k=>$d){
          $aux .= $k.':{';
          foreach ($d as $k2=>$i2){
            $aux .= "$k2: '$i2',";
          }
          $aux .= '},';
        }
      }
      $detail = "{ $aux }";
    } else {
      $detail = null;
    }

    /********************************/
    
    $encNutr = $user->getMetaContent('nutri_q1');
    $code = encriptID($user->id).'-'.encriptID(time()*rand());
    $btnEncuesta = $code.'/'.getKeyControl($code);
        
    /********************************/
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
        'user' => $user,
        'aCoachs' => $aCoachs,
        'allCoachs' => $allCoachs,
        'oDates' => $oDates,
        'oNotes' => $oNotes,
        'tab' => $tab,
        'fisioIndiba' => $fisioIndiba,
        'sueloPelvico' => $sueloPelvico,
        'invoices' => $invoices,
        'totalInvoice' => $totalInvoice,
        'invoiceModal' => $invoiceModal,
        'valora' => $valoracion,
        'uPlan' => $uPlan,
        'sing_contrato' => $sing_contrato,
        'u_current'=>Auth::user()->id,
        'encNutr'=>$encNutr,
        'btnEncuesta'=>$btnEncuesta,
    ]);
  }

  public function addSubscr(Request $request) {
    $uID = $request->input('id');
    $rID = $request->input('id_rate');
    $price = $request->input('r_price');
    $oUser = User::find($uID);
    $oRate = Rates::find($rID);
    if (!$oUser) {
      return redirect()->action('UsersController@clientes')->withErrors(['Usuario no encontrado']);
    }
    if (!$oRate) {
      return redirect('/admin/usuarios/informe/' . $uID . '/servic')->withErrors(['Servicio no encontrada']);
    }
    
    $uPlan = $oUser->getPlan();
    $tarifa = ($uPlan == 1 && $oRate->tarifa == 'fidelity') ? 'fidelity' : '';
              

    $oObj = new UserRates();
    $oObj->id_user = $uID;
    $oObj->id_rate = $rID;
    $oObj->rate_year = date('Y');
    $oObj->rate_month = date('m');
    $oObj->tarifa = $tarifa;
    $oObj->price = $price;
    $oObj->coach_id = $request->input('id_rateCoach');
    $oObj->save();

    $oObj = new \App\Models\UsersSuscriptions();
    $oObj->id_user = $uID;
    $oObj->id_rate = $rID;
    $oObj->price = $price;
    $oObj->tarifa = $tarifa;
    $oObj->id_coach = $request->input('id_rateCoach');
    $oObj->save();

    return redirect('/admin/usuarios/informe/' . $uID . '/servic')->with('success', $oRate->name . ' asignado a ' . $oUser->name . '.');
  }

  /**
   * 
   * @param Request $request
   * @return type
   */
  public function changeSubscr(Request $request) {
    $subscr_id = $request->input('subscr_id');
    $price = $request->input('price');
    $oObj = \App\Models\UsersSuscriptions::find($subscr_id);
    if( !is_numeric($price) || $price<0 ) 
      return response()->json(['error','El valor debe ser mayor o igual a 0€']);

    if(!$oObj || $oObj->id != $subscr_id ) 
      return response()->json(['error','Suscripcion no encontrada']);
    
    $oObj->price = $price;
    if($oObj->save())  return response()->json(['OK','Suscripcion cambiada']);
      
    return response()->json(['error','Error al cambiar la Suscripcion']);
  }
  /**
   * Remove subscription
   * @param Request $request
   */
  public function rmSubscr($uID, $id) {
    $oObj = \App\Models\UsersSuscriptions::find($id);
    if (!$oObj || $oObj->id_user != $uID) {
      return redirect('/admin/usuarios/informe/' . $uID . '/servic')->withErrors(['Servicio no encontrada']);
    }
    $oRate = $oObj->rate;
    $oObj->delete();
    $msg = ' Se eliminó la suscripción.';
    if ($oRate) {
      $msg = 'Se eliminó la suscripción a ' . $oRate->name . '.';
    }
    return redirect('/admin/usuarios/informe/' . $uID . '/servic')->with('success', $msg);
  }

  public function unassignedMontly($idUser, $idRate, $date) {
    $aDate = explode('-', $date);
    if (count($aDate) != 2) {
      return redirect()->action('UsersController@clientes')->withErrors(['Periodo inválido']);
    }
    $userRate = UserRates::where('id_user', $idUser)
                    ->where('id_rate', $idRate)
                    ->where('active', 1)->first();

    if ($userRate) {
      $userRate->active = 0;
      $userRate->save();
      return redirect()->action('UsersController@clientes')->with('success', 'Cliente desuscripto del Servicio ' . $date);
    }

    return redirect()->action('UsersController@clientes')->withErrors(['No se ha podido desuscribir']);
  }

  public function exportClients() {
    return Excel::download(new UsersExport, 'clientes_'.date('Y_m_d').'.xlsx');
  }

  public function rateCharge(Request $request) {
    $stripe = null;
    $oUser = User::find($request->id_user);
   
    $card = null;
    $paymentMethod = $oUser->getPayCard();
    if ($paymentMethod) {
      $aux = $paymentMethod->toArray();
      $card['brand'] = $aux['card']['brand'];
      $card['exp_month'] = $aux['card']['exp_month'];
      $card['exp_year'] = $aux['card']['exp_year'];
      $card['last4'] = $aux['card']['last4'];
    }
    
    $rateFamily = \App\Models\Rates::getTypeRatesGroups(false);
    $uPlan = $oUser->getPlan();
    
    return view('admin.usuarios.clientes._rate_charge', [
        'user' => $oUser,
        'coachs' => User::getCoachs(),
        'rates' => Rates::orderBy('status', 'desc')->orderBy('name', 'asc')->get(),
        'rateFamily' => $rateFamily,
        'stripe' => $stripe,
        'card' => $card,
        'uPlan'=>$uPlan
    ]);
  }

  public function addNotes(Request $request) {
    $uID = $request->input('uid');
    $id = $request->input('id');
    $note = $request->input('note');
    $idCoach = $request->input('coach',Auth::user()->id);
    $oCoach = User::find($idCoach);
    $oNote = null;
    if ($id > 0)
      $oNote = UsersNotes::find($id);
    if (!$oNote) {
      $oNote = new UsersNotes();
      $oNote->id_user = $uID;
    }

    $oNote->id_coach = $idCoach;
    $oNote->type = ($oCoach) ? $oCoach->role : '';
    $oNote->note = $note;
    $oNote->save();

    return redirect('/admin/usuarios/informe/' . $uID . '/notes')->with(['success' => 'Nota Guardada']);
  }

  public function delNotes(Request $request) {
    $uID = $request->input('uid');
    $id = $request->input('id');
    $oNote = UsersNotes::find($id);
    if ($oNote) {
      if ($oNote->delete()) {
        return redirect('/admin/usuarios/informe/' . $uID . '/notes')->with(['success' => 'Nota eliminada']);
      }
    }

    return redirect('/admin/usuarios/informe/' . $uID . '/notes')->withErrors(['Nota no eliminada']);
  }



  function downlConsent($uid,$type) {
    $view = $this->seeConsent($uid,$type);
    
//            $fileName = str_replace(' ','-','liquidacion '.$aData['mes'].' '. strtoupper($user->name));
//        $routePdf = storage_path('/app/liquidaciones/'. urlencode($fileName).'.pdf');
//        $pdf = PDF::loadView('pdfs.liquidacion', $aData);
//        $pdf->save($routePdf);
        
    $pdf = \Barryvdh\DomPDF\Facade::loadHTML($view);
    return $pdf->download('invoice.pdf');
        
        
  }
  function seeConsent($uid,$type) {
    
    $oUser = User::find($uid);
    if (!$oUser){
      abort(404);
      exit();
    }
    $u_name = $oUser->name;
    $fileName = $oUser->getMetaContent('sign_'.$type);
    $sign = false;
    if ($fileName){
      $path = storage_path('/app/' . $fileName);
      $sign = File::exists($path);
      if ($sign){
        $fileName = str_replace('signs/','', $fileName);
      }
    }
    if (!$sign){
      die('firma');
    }
    switch ($type){
      case 'fisioIndiba':
        $file = 'CONSENTIMIENTO-FISIOTERAPIA-CON-INDIBA';
        break;
      case 'sueloPelvico':
        $file = 'CONSENTIMIENTO-SUELO-PELVICO';
        break;
      default:
        $file = '';
        break;
    }
    
    
            
    return view('docs.concentimientos', [
        'fileName' => $fileName,
        'file' => $file,
        'u_name' => $u_name,
        'sign' => $sign
    ]);
  }
  function getSign($file) {

    $path = storage_path('/app/signs/' .$file);
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
