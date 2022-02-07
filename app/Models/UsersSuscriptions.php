<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersSuscriptions extends Model
{
        
    use SoftDeletes; //Implementamos 
    public function user()
    {
        return $this->hasOne('\App\Models\User', 'id', 'id_user');
    }
    public function rate()
    {
        return $this->hasOne('\App\Models\Rates', 'id', 'id_rate');
    }
}
