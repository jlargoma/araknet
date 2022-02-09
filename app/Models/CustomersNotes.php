<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomersNotes extends Model
{
        
  use SoftDeletes; //Implementamos 
    public function customers()
    {
        return $this->hasOne('\App\Models\Customers', 'id', 'customer_id');
    }
}
