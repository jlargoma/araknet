<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachRates extends Model
{
    protected $table = 'coach_rates';

    public function trainer()
    {
        return $this->hasOne('\App\Models\User', 'id', 'id_user');
    }
}
