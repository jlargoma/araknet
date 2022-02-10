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
    $customerRate = $oDate->cRates;
    $oCustomer = $customerRate->customer;
    $oRate = $customerRate->rate;
    
    if($value === null){
      $value = $customerRate->price;
    }
    
    
    $time = strtotime($oDate->date);
    $oCobro = new Charges();
    $oCobro->customer_id = $oCustomer->id;
    $oCobro->date_payment = date('Y-m-d');
    $oCobro->rate_id = $oRate->id;
    $oCobro->type_payment = $tpay;
    $oCobro->type = 1;
    $oCobro->import = $value;
    $oCobro->discount = 0;
    $oCobro->type_rate = $oRate->type;
    $oCobro->save();
    ///--------------------------------//
    if (!$customerRate->charge_id){
      $customerRate->charge_id = $oCobro->id;
      $customerRate->save();
    } else {
      $urClone = new CustomersRates();
      $urClone->customer_id = $customerRate->customer_id;
      $urClone->rate_id = $customerRate->rate_id;
      $urClone->rate_year = $customerRate->rate_year;
      $urClone->rate_month = $customerRate->rate_month;
      $urClone->price = $customerRate->price;
      $urClone->user = $customerRate->user_id;
      $urClone->charge_id = $oCobro->id;
      $urClone->save();
    }
    ///--------------------------------//
    $dataMail = [
        'fecha_pago' => date('Y-m-d'),
        'type_payment' => $tpay,
        'importe' => $value,
    ];
    MailsService::sendEmailPayRate($dataMail, $oCustomer, $oRate);
    return ['OK'];
  }

}
