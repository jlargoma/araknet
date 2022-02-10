<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRates extends Model
{
   

    public function trainer()
    {
        return $this->hasOne('\App\Models\User', 'id', 'user_id');
    }
}
