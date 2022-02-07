<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class UserBonos extends Model
{
    protected $table = 'users_bonos';
    use SoftDeletes; //Implementamos 
	public function user()
    {
        return $this->hasOne('\App\Models\User', 'id', 'id_user');
    }

    public function charges()
    {
        return $this->hasOne('\App\Models\Charges', 'id', 'id_charges');
    }
    public function logs()
    {
        return $this->hasOne('\App\Models\UserBonosLogs');
    }

    function check($uID){
      
      if (!$this->id || $this->id<1)
        return 'Debe seleccionar almenos un Bono';
      if($this->user_id != $uID) return 'Bono inválido';
      if ($this->qty<1) return 'Bono no disponible';
      return 'OK';
    }
    
    function usar($cID,$date_type,$date){
      $this->qty = $this->qty-1;
      $this->save();
      $this->saveLogDecr($cID,$date_type, $date);
      return 'OK';
    }
    
    function saveLogIncr($oBono,$cID){
      $total = UserBonosLogs::getTotal($this->id);
      //-----------------------------------//
      $obj = new UserBonosLogs();
      $obj->user_bonos_id = $this->id;
      $obj->charge_id = $cID;
      $obj->bono_id = $oBono->id;
      $obj->price = $oBono->price;
      $obj->incr = $oBono->qty;
      $obj->total = $total+$oBono->qty;
      $obj->text = 'Compra: '.$oBono->name;
      $obj->save();
      
    }
    
    function saveLogDecr($cID,$date_type, $date){
      $total = UserBonosLogs::getTotal($this->id);
      //-----------------------------------//
      $text = 'Cita ';
      switch ($date_type){
        case 'fisio':
          $text .= 'Fisioterapia';
          break;
        case 'nutri':
          $text .= 'Nutrición';
          break;
        case 'valora':
          $text .= 'Valoración';
          break;
      }
      $text .= ': '. dateMin($date);
      $obj = new UserBonosLogs();
      $obj->user_bonos_id = $this->id;
      $obj->charge_id = $cID;
      $obj->decr = 1;
      $obj->total = $total-1;
      $obj->text = $text;
      $obj->save();
      
    }
    
    /**
     * Busca un bono similar o crea uno para el $uID
     * @param type $uID
     * @param type $uBonoOrig
     */
    function getBonoToOtherUser($uID,$uBonoOrig){
        if ($uBonoOrig->rate_type && $uBonoOrig->rate_subf){
          $obj = UserBonos::where('user_id',$uID)
                  ->where('rate_type',$uBonoOrig->rate_type)
                  ->where('rate_subf',$uBonoOrig->rate_subf)
                  ->first();
        
          if ($obj) return $obj;
        }
        
        if ($uBonoOrig->rate_type){
          $obj = UserBonos::where('user_id',$uID)
                  ->where('rate_type',$uBonoOrig->rate_type)->first();
          if ($obj) return $obj;
        }

        if ($uBonoOrig->rate_subf){
          $obj = UserBonos::where('user_id',$uID)
                  ->where('rate_subf',$uBonoOrig->rate_subf)->first();
          if ($obj) return $obj;
        }
        
        $oUsrBono = new UserBonos();
        $oUsrBono->user_id = $uID;
        $oUsrBono->rate_type = $uBonoOrig->rate_type;
        $oUsrBono->rate_id = $uBonoOrig->rate_id;
        $oUsrBono->rate_subf = $uBonoOrig->rate_subf;
        $oUsrBono->qty = 0;
        return $oUsrBono;
    }
}
