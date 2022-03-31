<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use Stripe;
use App\Models\Bonos;
use App\Models\User;
use App\Models\UserBonos;
use App\Models\CustomersRates;
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
    $qry = CustomersRates::where('rate_year',$year)
            ->where('rate_month',$mes)
            ->with('charges','user');
        
    if ($active != ''){
      $uIDs = User::where('status',$active)->pluck('id');
      $qry->whereIn('customer_id',$uIDs);
    }
            
    if ($type_payment != ''){
      $cIDs = Charges::where('type_payment',$type_payment)->pluck('id');
      $qry->whereIn('charge_id',$cIDs);
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
    ]);

    die('usuario no encontrado');
  }

  
  function pingHotpots($idStatus){
    $obj = \App\Models\HotspotStatus::find($idStatus);
    if (!$obj) die('err01');
    try {
      $sHelium = new \App\Services\HeliumService();
      $resp = $sHelium->get_hotspot($obj->hotspot_imac);
      if ($resp) {
        if (isset($sHelium->response->data)) {
          $hp = $sHelium->response->data;
          
          $status = $hp->status->online;
          $geocode = $hp->geocode->short_street;
          if ($hp->geocode->short_city) $geocode .= "( {$hp->geocode->short_city} )";

          //Busco si tiene un cliente asociado
          $idCust = -1;
          $oCust = \App\Models\Customers::where('hotspot_imac', $hp->address)->first();
          if ($oCust)
            $idCust = $oCust->id;
          //guardo/actualizo el registro
          $obj->customer_id = $idCust;
          $obj->name = $hp->name;
          $obj->street = $geocode;
          $obj->status = $status;
          $obj->save();
          die('OK');
      
        }
      } 
      die('err03');
      
    } catch (\Exception $e) {
      die('err02');
    }
  }
}
