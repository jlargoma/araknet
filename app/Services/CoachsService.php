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
        $dates = Dates::whereIn('customer_rate_ids', array_keys($idUR))
                ->pluck('user_id','customer_rate_ids');
        foreach ($dates as $customer_rate_ids=>$user_id){
            $id_charge = $idUR[$customer_rate_ids];
            $result[$id_charge] = $user_id;
        }
      }
      //--- END: busca las citas           -------------------/
      //-------------------------------------------------------/
      return $result;
    }
    
  }
}
