<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\User;
use App\Models\Customers;
use App\Models\Charges;
use App\Models\CustomersRates;

class ChargesService {

  function generatePayment3DS($uID,$pID,$cID,$oData) {
    $time = $oData->time;
    $rID = $oData->rID;
    $tpay = 'card';//$oData->tpay;
    $value = $oData->value;
    $disc = intval($oData->disc);
    $user_id = intval($oData->user_id);
    return $this->generatePayment($time, $uID, $rID, $tpay, $value, $disc, $pID, $cID, $user_id);
  }
  
  /**
   * 
   * @param type $time
   * @param type $uID
   * @param type $rID
   * @param type $tpay
   * @param int $value
   * @param type $disc
   * @param type $idStripe
   * @param type $cStripe
   * @param type $user_id
   * @return type
   */
  function generatePayment($time, $customerID, $rID, $tpay, $value, $disc = 0, $user_id = null) {
    $month = date('Y-m-d', $time);
    $oCustomer = Customers::find($customerID);
    if ($user_id == 'null') $user_id = null;
    if (!$oCustomer)
      return ['error', 'Cliente no encontrado'];

    $oRate = Rates::find($rID);
    if (!$oRate)
      return ['error', 'Tarifa no encontrada'];
    
    $dataMail = [
        'fecha_pago' => $month,
        'type_payment' => $tpay,
        'importe' => $value,
    ];
    if (!$disc)
      $disc = 0;
    //BEGIN PAYMENTS MONTH
    for ($i = 0; $i < $oRate->mode; $i++) {

      $oCobro = new Charges();
      $oCobro->customer_id = $oCustomer->id;
      $oCobro->date_payment = date('Y-m-d');
      $oCobro->rate_id = $oRate->id;
      $oCobro->type_payment = $tpay;
      $oCobro->type = 1;
      $oCobro->import = $value;
      $oCobro->discount = $disc;
      $oCobro->type_rate = $oRate->type;
      $oCobro->user_id = $user_id;
      $oCobro->save();

      /*       * ************************************************** */

      $oUserRate = CustomersRates::where('customer_id', $oCustomer->id)
              ->where('rate_id', $oRate->id)
              ->where('rate_month', date('n', $time))
              ->where('rate_year', date('Y', $time))
              ->whereNull('charge_id')
              ->first();
      if ($oUserRate) {
        $oUserRate->charge_id = $oCobro->id;
        $oUserRate->user_id = $user_id;
        $oUserRate->save();
      } else { //si no tenia asignada la tarifa del mes
        $oUserRate = new CustomersRates();
        $oUserRate->customer_id = $oCustomer->id;
        $oUserRate->rate_id = $oRate->id;
        $oUserRate->rate_year = date('Y', $time);
        $oUserRate->rate_month = date('n', $time);
        $oUserRate->charge_id = $oCobro->id;
        $oUserRate->user_id = $user_id;
        $oUserRate->price = $value;
        $oUserRate->save();
      }
      /*       * *********************************************** */
      //Next month
      $time = strtotime($month . ' +1 month');
      $month = date('Y-m-d', $time);
      $value = 0; //solo se factura el primer mes
      $disc = 0; //solo se factura el primer mes
    }
    //END PAYMENTS MONTH
    $statusPayment = 'Pago realizado correctamente, por ' . payMethod($tpay);
    /*     * ********************************************************** */
    MailsService::sendEmailPayRate($dataMail, $oCustomer, $oRate);
    return ['OK', $statusPayment, $oCobro->id];
  }
  
   
  /**
   * 
   * @param type $time
   * @param type $uID
   * @param type $rID
   * @param type $tpay
   * @param int $value
   * @param type $disc
   * @param type $idStripe
   * @param type $cStripe
   * @param type $user_id
   * @return type
   */
  function generateRate($time, $customerID, $rID, $value, $disc = 0, $user_id = null) {
    $month = date('Y-m-d', $time);
    $oCustomer = Customers::find($customerID);
    if ($user_id == 'null') $user_id = null;
    if (!$oCustomer)
      return ['error', 'Cliente no encontrado'];

    $oRate = Rates::find($rID);
    if (!$oRate)
      return ['error', 'Tarifa no encontrada'];
    if (!$disc)
      $disc = 0;
    //BEGIN PAYMENTS MONTH
    for ($i = 0; $i < $oRate->mode; $i++) {


      $oUserRate = CustomersRates::where('customer_id', $oCustomer->id)
              ->where('rate_id', $oRate->id)
              ->where('rate_month', date('n', $time))
              ->where('rate_year', date('Y', $time))
              ->first();
      if ($oUserRate) {
        $oUserRate->user_id = $user_id;
        $oUserRate->save();
      } else { //si no tenia asignada la tarifa del mes
        $oUserRate = new CustomersRates();
        $oUserRate->customer_id = $oCustomer->id;
        $oUserRate->rate_id = $oRate->id;
        $oUserRate->rate_year = date('Y', $time);
        $oUserRate->rate_month = date('n', $time);
        $oUserRate->user_id = $user_id;
        $oUserRate->price = $value;
        $oUserRate->save();
      }
      /*       * *********************************************** */
      //Next month
      $time = strtotime($month . ' +1 month');
      $month = date('Y-m-d', $time);
      $value = 0; //solo se factura el primer mes
      $disc = 0; //solo se factura el primer mes
    }
    //END PAYMENTS MONTH
    $statusPayment = 'Servicio agregado correctamente.';
    return ['OK', $statusPayment,null];
  }

}
