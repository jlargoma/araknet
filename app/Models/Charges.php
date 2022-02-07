<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Charges extends Model
{
    use SoftDeletes; //Implementamos 
    public function rate()
    {
        return $this->hasOne('\App\Models\Rates', 'id', 'id_rate');
    }

    public function user()
    {
        return $this->hasOne('\App\Models\User', 'id', 'id_user');
    }
    
    static function getSumYear($year)
    {
      
      $rates = self::join('users_rates', 'id_charges', 'charges.id')
                ->where('rate_year',$year)
                ->sum('import');
      $bono = self::whereYear('date_payment', '=', $year)
              ->where('bono_id','>',0)->sum('import');
      return ($rates+$bono);
    }
}
