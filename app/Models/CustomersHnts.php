<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersHnts extends Model
{
        
    public function customers()
    {
        return $this->hasOne('\App\Models\Customers', 'id', 'customer_id');
    }
}
