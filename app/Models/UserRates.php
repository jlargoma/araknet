<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //línea necesaria

class UserRates extends Model
{
    protected $table = 'users_rates';
    use SoftDeletes; //Implementamos 
	public function user()
    {
        return $this->hasOne('\App\Models\User', 'id', 'id_user');
    }

    public function rate()
    {
        return $this->hasOne('\App\Models\Rates', 'id', 'id_rate');
    }
    public function charges()
    {
        return $this->hasOne('\App\Models\Charges', 'id', 'id_charges');
    }

}
