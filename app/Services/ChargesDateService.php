<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\User;
use App\Models\Charges;
use App\Models\CustomersRates;

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
    $oCobro->customer_id = $oUser->id;
    $oCobro->date_payment = date('Y-m-d');
    $oCobro->rate_id = $oRate->id;
    $oCobro->type_payment = $tpay;
    $oCobro->type = 1;
    $oCobro->import = $value;
    $oCobro->discount = 0;
    $oCobro->type_rate = $oRate->type;
    $oCobro->save();
    ///--------------------------------//
    if (!$uRate->charge_id){
      $uRate->charge_id = $oCobro->id;
      $uRate->save();
    } else {
      $urClone = new CustomersRates();
      $urClone->customer_id = $uRate->customer_id;
      $urClone->rate_id = $uRate->rate_id;
      $urClone->rate_year = $uRate->rate_year;
      $urClone->rate_month = $uRate->rate_month;
      $urClone->price = $uRate->price;
      $urClone->coach_id = $uRate->coach_id;
      $urClone->charge_id = $oCobro->id;
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
