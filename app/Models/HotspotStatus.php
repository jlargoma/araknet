<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotspotStatus extends Model
{
  protected $table = 'hotspot_status';
  
  public function customers()
  {
    return $this->have('\App\Models\customers','id', 'customers_id');
  }
  
  public function getBy_imac($imac) {
    $obj = self::where('hotspot_imac',$imac)->first();
    if ($obj) return $obj;
    
    $obj = new HotspotStatus();
    $obj->hotspot_imac = $imac;
    $obj->customer_id = -1;
    $obj->save();
    
    return $obj;
  }

}
