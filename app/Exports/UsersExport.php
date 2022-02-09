<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection {

  public function collection() {

    $array_excel = [];
    $array_excel[] = [
        'Nombre',
        'Email',
        'Telefono',
        'Estado',
        'Servicios'
    ];
    
    $aRates = \App\Models\Rates::getByTypeRate('pt')->pluck('name', 'id')->toArray();
    $oCustomersRates = \App\Models\CustomersRates::whereIn('rate_id',array_keys($aRates))->orderBy('id','desc')->get();
    $aCustomersRates = [];
    if ($oCustomersRates) {
      foreach ($oCustomersRates as $i) {
        if (!isset($aCustomersRates[$i->customer_id]))
            $aCustomersRates[$i->customer_id] = $aRates[$i->rate_id];
      }
    }
    
    
    $customers = \App\Models\User::where('role', 'user')->get();
    foreach ($customers as $customer) {
        $array_excel[] = [
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->status ? 'ACTIVO' : 'NO ACTIVO',
            isset($aCustomersRates[$customer->id]) ? $aCustomersRates[$customer->id] : '-'
        ];
    }
    
    $collection = collect($array_excel);

    return $collection;
  }

}
