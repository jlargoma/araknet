<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\User;
use App\Models\Charges;
use App\Models\UserRates;

class ChargesDateService {

  function generatePayment3DS($uID,$pID,$cID,$oData) {
    $dID = $oData->dID;
    $oDate = \App\Models\Dates::find($dID);
    return $this->generatePayment($oDate, 'card', null,$pID, $cID);
  }
  
  function generatePayment($oDate, $tpay, $value) {
    $uRate = $oDate->uRates;
    $oUser = $uRate->user;
    $oRate = $uRate->rate;
    
    if($value === null){
      $value = $uRate->price;
    }
    
    
    $time = strtotime($oDate->date);
    $oCobro = new Charges();
    $oCobro->id_user = $oUser->id;
    $oCobro->date_payment = date('Y-m-d');
    $oCobro->id_rate = $oRate->id;
    $oCobro->type_payment = $tpay;
    $oCobro->type = 1;
    $oCobro->import = $value;
    $oCobro->discount = 0;
    $oCobro->type_rate = $oRate->type;
    $oCobro->save();
    ///--------------------------------//
    if (!$uRate->id_charges){
      $uRate->id_charges = $oCobro->id;
      $uRate->save();
    } else {
      $urClone = new UserRates();
      $urClone->id_user = $uRate->id_user;
      $urClone->id_rate = $uRate->id_rate;
      $urClone->rate_year = $uRate->rate_year;
      $urClone->rate_month = $uRate->rate_month;
      $urClone->price = $uRate->price;
      $urClone->coach_id = $uRate->coach_id;
      $urClone->id_charges = $oCobro->id;
      $urClone->save();
    }
    ///--------------------------------//
    $dataMail = [
        'fecha_pago' => date('Y-m-d'),
        'type_payment' => $tpay,
        'importe' => $value,
    ];
    MailsService::sendEmailPayRate($dataMail, $oUser, $oRate);
    return ['OK'];
  }

}
