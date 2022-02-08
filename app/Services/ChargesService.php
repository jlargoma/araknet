<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\User;
use App\Models\Charges;
use App\Models\UserRates;

class ChargesService {

  function generatePayment3DS($uID,$pID,$cID,$oData) {
    $time = $oData->time;
    $rID = $oData->rID;
    $tpay = 'card';//$oData->tpay;
    $value = $oData->value;
    $disc = intval($oData->disc);
    $id_coach = intval($oData->id_coach);
    return $this->generatePayment($time, $uID, $rID, $tpay, $value, $disc, $pID, $cID, $id_coach);
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
   * @param type $id_coach
   * @return type
   */
  function generatePayment($time, $uID, $rID, $tpay, $value, $disc = 0, $id_coach = null) {
    $month = date('Y-m-d', $time);
    $oUser = User::find($uID);
    if ($id_coach == 'null') $id_coach = null;
    if (!$oUser)
      return ['error', 'Usuario no encontrado'];

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
      $oCobro->id_user = $oUser->id;
      $oCobro->date_payment = date('Y-m-d');
      $oCobro->id_rate = $oRate->id;
      $oCobro->type_payment = $tpay;
      $oCobro->type = 1;
      $oCobro->import = $value;
      $oCobro->discount = $disc;
      $oCobro->type_rate = $oRate->type;
      $oCobro->save();

      /*       * ************************************************** */

      $oUserRate = UserRates::where('id_user', $oUser->id)
              ->where('id_rate', $oRate->id)
              ->where('rate_month', date('n', $time))
              ->where('rate_year', date('Y', $time))
              ->whereNull('id_charges')
              ->first();
      if ($oUserRate) {
        $oUserRate->id_charges = $oCobro->id;
        $oUserRate->coach_id = $id_coach;
        $oUserRate->save();
      } else { //si no tenia asignada la tarifa del mes
        $oUserRate = new UserRates();
        $oUserRate->id_user = $oUser->id;
        $oUserRate->id_rate = $oRate->id;
        $oUserRate->rate_year = date('Y', $time);
        $oUserRate->rate_month = date('n', $time);
        $oUserRate->id_charges = $oCobro->id;
        $oUserRate->coach_id = $id_coach;
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
    MailsService::sendEmailPayRate($dataMail, $oUser, $oRate);
    return ['OK', $statusPayment, $oCobro->id];
  }

}
