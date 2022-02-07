<?php

namespace App\Services;

use App\Models\UserRates;
use App\Models\Dates;

class CoachsService {

  function getCoachsCharge($chargesID){
    $result = [];
    $lst = UserRates::whereIn('id_charges', $chargesID)->get();
    if ($lst){
      $idUR = [];
      foreach ($lst as $i){
        if (!$i->coach_id) $idUR[$i->id] = $i->id_charges;
        $result[$i->id_charges] = $i->coach_id;
      }
      //-------------------------------------------------------/
      //--- BEGIN busca las citas           -------------------/
      if (count($idUR)>0){
        $dates = Dates::whereIn('id_user_rates', array_keys($idUR))
                ->pluck('id_coach','id_user_rates');
        foreach ($dates as $id_user_rates=>$id_coach){
            $id_charge = $idUR[$id_user_rates];
            $result[$id_charge] = $id_coach;
        }
      }
      //--- END: busca las citas           -------------------/
      //-------------------------------------------------------/
      return $result;
    }
    
  }
}
