<?php

namespace App\Traits\Citas;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customers;
use App\Models\CustomersRates;
use Auth;
use App\Models\Rates;
use App\Models\Dates;

trait BlocksTraits {

   function blockDates($type) {
    $role = $this->typeControl($type);
    $users = \App\Services\CitasService::getusers($role);
    $cNames = [];
    if ($users) {
      foreach ($users as $item) {
        $cNames[$item->id] = $item->name;
      }
    }

    return view('citas.calendars.blockDates', [
        'users' => $cNames,
        'type' => $type
    ]);
  }

  function blockDatesSave(Request $req) {

    $type = $req->input('date_type');
    $user_id = $req->input('user_id');
    $start = $req->input('start');
    $end = $req->input('end');
    $hours = $req->input('hours');

    $role = $this->typeControl($type);
    
    $startTime = null;
    $aux = explode('-', $start);
    if (is_array($aux) && count($aux) == 3)
      $startTime = ($aux[2] . '-' . $aux[1] . '-' . $aux[0]);

    $endTime = null;
    $aux = explode('-', $end);
    if (is_array($aux) && count($aux) == 3)
      $endTime = ($aux[2] . '-' . $aux[1] . '-' . $aux[0]);

    $aDays = arrayDays($startTime, $endTime, 'Y-m-d', 'w');
    foreach ($aDays as $d => $wd) {
      if ($wd > 0) {
        foreach ($hours as $h) {
          $dateHour = $d . " $h:00:00";
          $exist = Dates::where('user_id', $user_id)
                          ->where('date_type', $type)
                          ->where('date', $dateHour)->first();
          if (!$exist) {
            $oObj = new Dates();
            $oObj->user_id = $user_id;
            $oObj->rate_id = 0;
            $oObj->customer_id = 0;
            $oObj->blocked = 1;
            $oObj->customers_rate_id = -1;
            $oObj->date_type = $role;
            $oObj->date = $dateHour;
            $oObj->save();
          }
        }
      }
    }
    return redirect()->back()->with(['success' => 'Horarios bloqueados']);
  }

}
