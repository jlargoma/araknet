<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\User;
use App\Models\Bonos;
use App\Models\UserBonos;
use App\Models\CustomersRates;
use App\Models\Charges;

class ValoracionService {

  private static $bIDV = 45;
  
  function isRate($ID) {
//    return $ID.'='.self::$bIDV;
    return ($ID == self::$bIDV);
  }
  static function RateLstID($uID,&$aLst) {
    if (in_array(self::$bIDV, $aLst)) return;
//    $bono = UserBonos::where('user_id',$uID)
//            ->where('qty','>',0)
//            ->where('rate_subf','v01')->first();
//    if ($bono)  
      
    $aLst[] = self::$bIDV;
  }
  
  static function bonosServ($uID,$oRate, &$total, &$lst) {
    dd($oRate);
  }
  
  static function getURate($uID,$rID,$timeCita,$cID) {
    if ($rID != self::$bIDV) return  null;
    
//    $customerBonos = UserBonos::where('user_id',$uID)
//              ->where('rate_subf','v01')->first();
//    if (!$customerBonos) return null;
    
    $oRate = Rates::find($rID);
    if (!$oRate) return null;
    
    // crear $uRate
    $uRate = new CustomersRates();
    $uRate->customer_id  = $uID;
    $uRate->rate_id  = $rID;
    $uRate->coach_id = $cID;
    $uRate->active   = 0;
    $uRate->price    = $oRate->price;
    $uRate->rate_year  = date('Y', $timeCita);
    $uRate->rate_month = date('m', $timeCita);
    $uRate->save();
    return $uRate;
  }
}
