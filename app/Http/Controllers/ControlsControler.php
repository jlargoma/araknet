<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Stripe;
use App\Models\Bonos;
use App\Models\User;
use App\Models\UserBonos;
use App\Models\UserRates;
use App\Models\Rates;
use App\Models\Charges;

class ControlsControler extends Controller {

  public function contabilidad(Request $req) {

    $year = $req->input('year', date('Y'));
    $mes = $req->input('mes', date('m'));
    
    $year = intval($year);
    if (!($year<=(date('Y')+1) && $year>2019 )){
      $year = date('Y');
    }
    $mes = intval($mes);
    if ($mes<1 ||  $mes>12 ){
      $mes = date('m');
    }
    
    
    
    $active = $req->input('active', '');
    $type_payment = $req->input('type_payment', '');
    $showEmpty = $req->input('showEmpty', 'NO');
    $lst = [];
    $uIDs = null;
    $qry = UserRates::where('rate_year',$year)
            ->where('rate_month',$mes)
            ->with('charges','user');
        
    if ($active != ''){
      $uIDs = User::where('status',$active)->pluck('id');
      $qry->whereIn('id_user',$uIDs);
    }
            
    if ($type_payment != ''){
      $cIDs = Charges::where('type_payment',$type_payment)->pluck('id');
      $qry->whereIn('id_charges',$cIDs);
    }
    
            
            
    $uRates = $qry->get();
    
    $aRateTypes = \App\Models\TypesRate::pluck('name','id')->toArray();
    $family = \App\Models\TypesRate::subfamily();
    $oRates = Rates::pluck('name','id')->toArray();
    $oRfamily = Rates::pluck('subfamily','id')->toArray();
    $oRtype = Rates::pluck('type','id')->toArray();
    
    foreach ($oRtype as $k=>$v){
      if (isset($aRateTypes[$v])){
        $oRtype[$k] = $aRateTypes[$v];
      }
    }
    foreach ($oRfamily as $k=>$v){
      if (isset($family[$v])){
        $oRfamily[$k] = $family[$v];
      }
    }
    
    
    //*************************************************//
    //******  AGREGO LA COMPRA DE BONOS ***************//
    $usedRates['bonos'] = "BONOS";
    $lstBonos = \App\Models\Bonos::all()->pluck('name','id')->toArray();
    $qry = Charges::where('bono_id','>',0)
            ->whereYear('date_payment','=',$year)
            ->whereMonth('date_payment','=',$mes)
            ->with('user');
    
    if ($uIDs != ''){
      $qry->whereIn('id_user',$uIDs);
    }
     if ($type_payment != ''){
      $qry->where('type_payment',$type_payment);
    }
    
    $bonoCharges = $qry->get();
//    dd($bonoCharges);
    //*************************************************//
    
//    dd($uRates);
    return view('controls.contabilidad', [
      'lst' => $lst,
      'mes' => $mes,
      'year' => $year,
      'active' => $active,
      'type_payment' => $type_payment,
      'showEmpty' => $showEmpty,
      'rTypes' => $oRtype,
      'oRates' => $oRates,
      'rfamily' => $oRfamily,
      'uRates' => $uRates,
      'bonoCharges' => $bonoCharges,
      'lstBonos' => $lstBonos,
    ]);

    die('usuario no encontrado');
  }

}
