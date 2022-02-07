<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachLiquidation extends Model
{
	protected $table = "coach_liquidation";
    public function coach()
    {
        return $this->hasOne('\App\Models\User', 'id', 'id_coach');
    }
}
