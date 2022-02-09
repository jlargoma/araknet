<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class CustomersRates extends Model
{
    use SoftDeletes; //Implementamos 
	public function customer()
    {
        return $this->hasOne('\App\Models\Customers', 'id', 'customer_id');
    }

    public function rate()
    {
        return $this->hasOne('\App\Models\Rates', 'id', 'rate_id');
    }
    public function charges()
    {
        return $this->hasOne('\App\Models\Charges', 'id', 'charge_id');
    }

}
