<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachUsers extends Model
{
    protected $table = 'coach_users';

    public function trainer()
    {
        return $this->hasOne('\App\Models\User', 'id', 'id_user');
    }
}
