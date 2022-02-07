<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Models\User;
use App\Models\Rates;
use App\Models\TypesRate;

class FunctionalControler extends Controller {

  function userRates() {

    $oTRates = TypesRate::all();

    $aRates = [];
    foreach ($oTRates as $i) {
      $aux = [];
      $aRates[] = [
          'lst' => $oRates = Rates::where('type', $i->id)->orderBy('name')->get(),
          'name' => $i->name
      ];
    }

    $aUsers = User::where('role', 'user')->orderBy('name')->pluck('name', 'id');

    return view('functional.user-rates', [
        'aRates' => $aRates,
        'aUsers' => $aUsers
    ]);
  }

}
