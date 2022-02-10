<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersLiquidation extends Model
{
	
    public function coach()
    {
        return $this->hasOne('\App\Models\User', 'id', 'user_id');
    }
}
