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

trait CloneTraits {

   function cloneDates($id) {
    $obj = Dates::find($id);
    $cNames = [];
    $customerRate = $obj->cRates;

    $user_id = $obj->user_id;
    $alreadyUsed = [];

    $start = substr($obj->date, 0, 10);
    $oCalendar = new \App\Services\CalendarService($start);
    $oCalendar->setLastDayWeeks(6);
    $calendar = $oCalendar->getCalendarWeeks();

    $rslt = \App\Services\CitasService::get_calendars($calendar['firstDay'], $calendar['lastDay'], null, $user_id, $obj->date_type);
//      dd($rslt);

    $rslt['calendar'] = $calendar['days'];
    $rslt['obj'] = $obj;
    $rslt['cRate'] = $customerRate;

    $times = [];
    return view('citas.calendars.cloneDates', $rslt);
  }

  function cloneDatesSave(Request $req) {
    $datelst = $req->input('datelst');
    $idDate = $req->input('idDate');

    $aux = explode(';', $datelst);
    $aDates = [];
    if (is_array($aux)) {
      foreach ($aux as $d) {
        if (!empty($d)) {
          $aux2 = explode('-', $d);
          $aDates[] = date('Y-m-d H', ($aux2[0] + ($aux2[1] * 3600))) . ':00:00';
        }
      }
    }

    $oDate = Dates::find($idDate);
    $customerRate = $oDate->cRates;

    foreach ($aDates as $d) {
      $timeCita = strtotime($d);
      $urClone = new CustomersRates();
      $urClone->customer_id = $customerRate->customer_id;
      $urClone->rate_id = $customerRate->rate_id;
      $urClone->rate_year = date('Y', $timeCita);
      $urClone->rate_month = date('n', $timeCita);
      $urClone->price = $customerRate->price;
      $urClone->user_id = $customerRate->user_id;
      $urClone->save();
      $customer_rate_ids = $urClone->id;

      $clone = new Dates();
      $clone->date = $d;
      $clone->rate_id = $oDate->rate_id;
      $clone->customer_id = $oDate->customer_id;
      $clone->user_id = $oDate->user_id;
      $clone->date_type = $oDate->date_type;
      $clone->customers_rate_id = $customer_rate_ids;
      $clone->save();
    }

    return redirect()->back()->with(['success' => 'Citas Creadas']);
  }

}
