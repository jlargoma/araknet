<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\User;
use App\Models\Charges;

class CardService {
  
  function processCard($oUser,$req) {
      $cc_number = $req->input('cc_number', null);
      $cc_expide_mm = $req->input('cc_expide_mm', null);
      $cc_expide_yy = $req->input('cc_expide_yy', null);
      $cc_cvc = $req->input('cc_cvc', null);
      $sStripe = new StripeService();

      /***********************************/
      /** GUARDAR TARJETA **/
      /***********************************/
      
      $validate = StripeCardValidation::validate($req);
      if ($validate !== 'OK'){
          return $validate;
      }
      $resp = $sStripe->subscription_changeCard($oUser, $cc_number, $cc_expide_mm, $cc_expide_yy, $cc_cvc);
      if ( $resp != 'updated'){
          return [$resp];
      }
      return 'OK';
    }
    
}
