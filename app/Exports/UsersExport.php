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
    $oUserRates = \App\Models\UserRates::whereIn('id_rate',array_keys($aRates))->orderBy('id','desc')->get();
    $aUserRates = [];
    if ($oUserRates) {
      foreach ($oUserRates as $i) {
        if (!isset($aUserRates[$i->id_user]))
            $aUserRates[$i->id_user] = $aRates[$i->id_rate];
      }
    }
    
    
    $users = \App\Models\User::where('role', 'user')->get();
    foreach ($users as $user) {
        $array_excel[] = [
            $user->name,
            $user->email,
            $user->telefono,
            $user->status ? 'ACTIVO' : 'NO ACTIVO',
            isset($aUserRates[$user->id]) ? $aUserRates[$user->id] : '-'
        ];
    }
    
    $collection = collect($array_excel);

    return $collection;
  }

}
