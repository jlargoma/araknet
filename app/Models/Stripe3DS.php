<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stripe3DS extends Model
{
    protected $table = 'stripe3DS';
    
//    id_user - cStripe - idStripe - action - jdata

  static function addNew($uID,$idStripe,$cStripe,$acc,$aData){
    $obj = new self();
    $obj->user_id  = $uID;
    $obj->idStripe = $idStripe;
    $obj->cStripe  = $cStripe;
    $obj->action   = $acc;
    $obj->jdata    = json_encode($aData);
    $obj->save();
  }
}