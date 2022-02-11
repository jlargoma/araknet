<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class Charges extends Model
{
    use SoftDeletes; //Implementamos 
    public function rate()
    {
        return $this->hasOne('\App\Models\Rates', 'id', 'rate_id');
    }

    public function user()
    {
        return $this->hasOne('\App\Models\User', 'id', 'user_id');
    }
    public function customer()
    {
        return $this->hasOne('\App\Models\Customers', 'id', 'customer_id');
    }
    
    static function getSumYear($year)
    {
      
      $rates = self::join('customers_rates', 'charge_id', 'charges.id')
                ->where('rate_year',$year)
                ->sum('import');
      return $rates;
    }
}
