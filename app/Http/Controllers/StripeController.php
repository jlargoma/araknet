<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stripe3DS;
use App\Models\Charges;

class StripeController extends Controller {

  private $eventID;
  private $pID;
  private $cID;

  public function __construct() {
    
  }

  public function processEvent(Request $req) {
    try {
      $this->eventID = $req->input('id');
      $data = $req->input('data');
      
      if ($data && isset($data['object'])) {
        $paid = $data['object']['paid'];
        $this->pID = $data['object']['payment_intent'];
        $this->cID = $data['object']['customer'];
        if ($paid) {
          $this->continuePayment();
          return 'Cargo exitoso!';
        }
      }
      return 'Cargo no encontrado';
    } catch (\Exception $ex) {
      return $ex->getMessage();
    }
  }

  public function continuePayment() {
    $obj = Stripe3DS::where('idStripe', $this->pID)
                    ->where('cStripe', $this->cID)->first();
        
    if ($obj) {
      $alreadyCharge = Charges::where('id_stripe',$this->pID)
            ->where('customer_stripe',$this->cID)->first();
      
      if ($alreadyCharge){
        echo 'Pago ya registrado'; 
        exit();
      }
      
      $acc = $obj->action;
      $oData = json_decode($obj->jdata);
      switch ($acc) {
        case 'asignBono':
          $oServ = new \App\Services\BonoService();
          $resp = $oServ->asignBono3DS($obj->user_id, $this->pID, $this->cID, $oData);
          $obj->events = $this->eventID;
          $obj->save();
          break;
        case 'generatePayment':
          $ChargesService = new \App\Services\ChargesService();
          $resp = $ChargesService->generatePayment3DS($obj->user_id, $this->pID, $this->cID, $oData);
          $obj->events = $this->eventID;
          $obj->save();
          break;
        case 'cita':
          $ChargesService = new \App\Services\ChargesDateService();
          $resp = $ChargesService->generatePayment3DS($obj->user_id, $this->pID, $this->cID, $oData);
          $obj->events = $this->eventID;
          $obj->save();
          break;
      }
    }
  }

}
