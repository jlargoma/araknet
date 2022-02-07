<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonos extends Model
{
  public function users()
  {
    return $this->hasMany('\App\Models\UserBonos','id', 'id_rate');
  }
  
  public function getBonoUser($uID) {
    if ($this->rate_type){
      $obj = UserBonos::where('user_id',$uID)
              ->where('rate_type',$this->rate_type)->first();
      if ($obj) return $obj;
    }
    
    if ($this->rate_subf){
      $obj = UserBonos::where('user_id',$uID)
              ->where('rate_subf',$this->rate_subf)->first();
      if ($obj) return $obj;
    }
    
    return null;
  }

}
