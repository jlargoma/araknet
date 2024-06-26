<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria
use Illuminate\Support\Facades\DB;

class Dates extends Model
{
    use SoftDeletes; //Implementamos 
    protected $table = "appointment";
    
    public function service()
    {
        return $this->hasOne('\App\Models\Rates', 'id', 'rate_id');
    }

    public function user()
    {
        return $this->hasOne('\App\Models\User', 'id', 'user_id');
    }

    public function cRates()
    {
        return $this->hasOne('\App\Models\CustomersRates', 'id', 'customers_rate_id');
    }
    public function getHour()
    {
      if ($this->customTime){
        $hour= $this->customTime;
        $aux = explode(':', $hour);
        if (is_array($aux) && count($aux)>2) $hour = $aux[0].':'.$aux[1];
        return $hour;
      }
      
      $dateTime = strtotime($this->date);
      return date('H:i',$dateTime);
    }
    public function customer()
    {
        return $this->hasOne('\App\Models\Customers', 'id', 'customer_id');
    }
    
    
     /**********************************************************************/
  /////////  user_meta //////////////
  public function setMetaContent($key,$content) {
    
    $oMeta = DB::table('appointment_meta')
            ->where('appoin_id',$this->id)->where('meta_key',$key)->first();
    if ($oMeta){
      DB::table('appointment_meta')->where('appoin_id',$this->id)
              ->where('meta_key',$key)
              ->update(['meta_value' => $content]);
    } else {
       DB::table('appointment_meta')->insert(
            ['appoin_id' => $this->id, 'meta_key' => $key,'meta_value' => $content]
        );
    }
    
    return null;
  }
  
  public function getMetaContent($key) {
    
    $oMeta = DB::table('appointment_meta')
            ->where('appoin_id',$this->id)->where('meta_key',$key)->first();
    
    if ($oMeta) {
      return $oMeta->meta_value;
    }
    return null;
  }
}
