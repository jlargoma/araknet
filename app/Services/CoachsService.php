<?php

namespace App\Services;

use App\Models\CustomersRates;
use App\Models\Dates;

class usersService {

  function getusersCharge($chargesID){
    $result = [];
    $lst = CustomersRates::whereIn('charge_id', $chargesID)->get();
    if ($lst){
      $idUR = [];
      foreach ($lst as $i){
        if (!$i->coach_id) $idUR[$i->id] = $i->charge_id;
        $result[$i->charge_id] = $i->coach_id;
      }
      //-------------------------------------------------------/
      //--- BEGIN busca las citas           -------------------/
      if (count($idUR)>0){
        $dates = Dates::whereIn('customers_rate_id', array_keys($idUR))
                ->pluck('user_id','customers_rate_id');
        foreach ($dates as $customers_rate_id=>$user_id){
            $id_charge = $idUR[$customers_rate_id];
            $result[$id_charge] = $user_id;
        }
      }
      //--- END: busca las citas           -------------------/
      //-------------------------------------------------------/
      return $result;
    }
    
  }
}
