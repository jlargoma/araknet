<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\User;
use App\Models\Bonos;
use App\Models\UserBonos;
use App\Models\Charges;
use App\Models\UserBonosLogs;

class BonoService {

  /**
   * 
   * @param type $user_id
   * @param type $pID
   * @param type $cID
   * @param type $aData
   */
  function asignBono3DS($user_id,$pID,$cID,$aData) {
    
    $alreadyCharge = Charges::where('id_stripe',$pID)
            ->where('customer_stripe',$cID)->first();
    if (!$alreadyCharge){
      $oUser = User::find($user_id);
      $oBono = Bonos::find($aData->bono);
      $this->asignBono($oUser, $oBono, $aData->tpay, $pID, $cID);
    }
  }
  
  /**
   * 
   * @param type $oUser
   * @param type $oBono
   * @param type $tpay
   * @param type $idStripe
   * @param type $cStripe
   * @return type
   */
  function asignBono($oUser, $oBono, $tpay, $idStripe = null, $cStripe = null,$price=null) {
    if (!$price) $price = $oBono->price;
    $date = date('Y-m-d');
    //BEGIN PAYMENTS
    $oCobro = new Charges();
    $oCobro->id_user = $oUser->id;
    $oCobro->date_payment = $date;
    $oCobro->id_rate = 0;
    $oCobro->type_payment = $tpay;
    $oCobro->type = 1;
    $oCobro->import = $price;
    $oCobro->discount = 0;
    $oCobro->type_rate = 0;
    $oCobro->bono_id = $oBono->id;
    $oCobro->id_stripe = $idStripe;
    $oCobro->customer_stripe = $cStripe;
    $oCobro->save();
    //END PAYMENTS

    $oUsrBono = $oBono->getBonoUser($oUser->id);
    if ($oUsrBono) {
      $oUsrBono->qty = $oUsrBono->qty + $oBono->qty;
    } else {
      $oUsrBono = new UserBonos();
      $oUsrBono->user_id = $oUser->id;
      $oUsrBono->rate_type = $oBono->rate_type;
      $oUsrBono->rate_id = $oBono->rate_id;
      $oUsrBono->rate_subf = $oBono->rate_subf;
      $oUsrBono->qty = $oBono->qty;
    }

    $oUsrBono->save();
    $oUsrBono->saveLogIncr($oBono, $oCobro->id);
    $statusPayment = 'Pago realizado correctamente, por ' . payMethod($tpay);
    /*     * ********************************************************** */
    $sent = MailsService::sendEmailPayBono($oUser, $oBono, $tpay);
    return ['OK', $statusPayment];
    if ($sent == 'OK')
      return ['OK', $statusPayment, $oCobro->id];
    else
      return ['error', $sent, $oCobro->id];
  }
  
  /**
   * 
   * @param type $oUser
   * @param type $oBono
   * @param type $tpay
   * @param type $idStripe
   * @param type $cStripe
   * @return type
   */
  function asignBonoAuto($oUser, $oBono, $tpay,$date,$price,$uses) {
    //BEGIN PAYMENTS
    $oCobro = new Charges();
    $oCobro->id_user = $oUser->id;
    $oCobro->date_payment = $date;
    $oCobro->id_rate = 0;
    $oCobro->type_payment = $tpay;
    $oCobro->type = 1;
    $oCobro->import = $price;
    $oCobro->discount = 0;
    $oCobro->type_rate = 0;
    $oCobro->bono_id = $oBono->id;
    $oCobro->id_stripe = null;
    $oCobro->customer_stripe = null;
    $oCobro->save();
    //END PAYMENTS

    $oUsrBono = $oBono->getBonoUser($oUser->id);
    if ($oUsrBono) {
      $oUsrBono->qty = $oUsrBono->qty + $oBono->qty;
    } else {
      $oUsrBono = new UserBonos();
      $oUsrBono->user_id = $oUser->id;
      $oUsrBono->rate_type = $oBono->rate_type;
      $oUsrBono->rate_id = $oBono->rate_id;
      $oUsrBono->rate_subf = $oBono->rate_subf;
      $oUsrBono->qty  = $oBono->qty;
    }

    $total = $oUsrBono->qty;
    $oUsrBono->save();
    
    
//    $total = $total; //UserBonosLogs::getTotal($oUsrBono->id);
    //-----------------------------------//
    $obj = new UserBonosLogs();
    $obj->user_bonos_id = $oUsrBono->id;
    $obj->charge_id = $oCobro->id;
    $obj->bono_id = $oBono->id;
    $obj->price = $price;
    $obj->incr = $oBono->qty;
    $obj->total = $total;
    $obj->text = 'Compra: '.$oBono->name;
    $obj->created_at = $date;
    $obj->save();
    
    
    foreach ($uses as $d){
      $obj = new UserBonosLogs();
      $total--;
      $text = 'Bono utilizado (Migración)';
      $obj->user_bonos_id = $oUsrBono->id;
      $obj->charge_id = null;
      $obj->decr = 1;
      $obj->total = $total;
      $obj->text = $text;
      $obj->created_at = $d;
      $obj->save();
    }
    
        
    $oUsrBono->qty -= count($uses);
    $oUsrBono->save();
    
  }
  
   /**
   * 
   * @param type $oUser
   * @param type $oBono
   * @param type $tpay
   * @param type $idStripe
   * @param type $cStripe
   * @return type
   */
  function bonoCompartido($oUserFrom, $oUserTo, $oUsrBono,$qty) {
    //decremento el bono del usuario
    $total = $oUsrBono->qty - $qty;

    if ($total<0) return 'Error: Bonos insuficientes';
    $oUsrBono->qty = $total;
    $oUsrBono->save();
    //-----------------------------------//
    $obj = new UserBonosLogs();
    $obj->user_bonos_id = $oUsrBono->id;
    $obj->charge_id = -1;
    $obj->bono_id = null;
    $obj->price = 0;
    $obj->decr = $qty;
    $obj->total = $total;
    $obj->text = 'Compartido a: '.$oUserTo->name;
    $obj->save();
    
    //incremento el bono del otro usuario
    $oUsrBonoTo = $oUsrBono->getBonoToOtherUser($oUserTo->id,$oUsrBono);
    $oUsrBonoTo->qty = $oUsrBonoTo->qty + $qty;
    $total = $oUsrBonoTo->qty;
    $oUsrBonoTo->save();
    //-----------------------------------//
    $obj = new UserBonosLogs();
    $obj->user_bonos_id = $oUsrBonoTo->id;
    $obj->charge_id = -1;
    $obj->bono_id = null;
    $obj->price = 0;
    $obj->incr = $qty;
    $obj->total = $total;
    $obj->text = 'Compartido por: '.$oUserFrom->name;
    $obj->save();
    
    return 'OK';
    
  }

  
  function restoreBonoCharge($chargeID) {
    $obj = UserBonosLogs::where('charge_id',$chargeID)->first();
    if ($obj){
      $oUsrBono = UserBonos::find($obj->user_bonos_id);
      if ($oUsrBono){
        //reintegro la cantidad utilizada
        $total = ($oUsrBono->qty) + ($obj->decr);
        $oUsrBono->qty  = $total;
        $oUsrBono->save();
        
        //Agrego el nuevo log
        
        $newObj = new UserBonosLogs();
        $newObj->user_bonos_id = $oUsrBono->id;
        $newObj->charge_id = -1;
        $newObj->bono_id = null;
        $newObj->price = 0;
        $newObj->incr = $obj->decr;
        $newObj->total = $total;
        $newObj->text = 'Cobro eliminado -> '. $obj->text;
        $newObj->save();
      }
    }
  }
  
  function fidelityADD($userID,$rTypeID) {
    
    $oUsrBono = UserBonos::where('user_id',$userID)
              ->where('rate_type',$rTypeID)->first();
    if (!$oUsrBono){
        //reintegro la cantidad utilizada
      $oUsrBono = new UserBonos();
      $oUsrBono->user_id = $userID;
      $oUsrBono->rate_type = $rTypeID;
      $oUsrBono->qty = 0;
    }
    
    //agrego la cantidad utilizada
    $total = ($oUsrBono->qty) + 1;
    $oUsrBono->qty  = $total;
    $oUsrBono->save();
    
    //Agrego el nuevo log
    $newObj = new UserBonosLogs();
    $newObj->user_bonos_id = $oUsrBono->id;
    $newObj->charge_id = -1;
    $newObj->bono_id = null;
    $newObj->price = 0;
    $newObj->incr = 1;
    $newObj->total = $total;
    $newObj->text = 'Tarifa FIDELITY';
    $newObj->save();
      
  }
}
