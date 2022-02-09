<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\Dates;
use App\Models\Rates;
use App\Models\CustomersRates;
use App\Models\CoachTimes;
use Illuminate\Support\Facades\Mail;

class DatesController extends Controller {

  public function index($month = "") {

    if ($month == "") {
      $month = Carbon::now()->startOfMonth();
      $startWeek = Carbon::now()->startOfWeek();
      $endWeek = Carbon::now()->endOfWeek();
    } else {
      $startWeek = Carbon::createFromFormat('Y-m-d', $month)->startOfWeek();
      $endWeek = Carbon::createFromFormat('Y-m-d', $month)->endOfWeek();

      $month = Carbon::createFromFormat('Y-m-d', $month)->startOfMonth();
    }

    return view('admin/dates/index', [
        'month' => $month,
        'week' => $startWeek,
        'selectedWeek' => $startWeek->format("W")
    ]);
  }

  public function delete($id) {
    $object = Dates::find($id);
    if ($object) {
      if ($object->date_type != 'pt') {
        /* Busca y elimina el user_rate */
        $uRate = $object->uRates;
        if ($uRate) {
          /* Buscar y elimnar cobro */
          $charge = $uRate->charges;
          if ($charge)
            $charge->delete();
          $uRate->delete();
        }
      }
      $object->delete();
      return redirect()->back();
    }
  }

  public function create(Request $request) {

    $ID = $request->input('idDate', null);
    $blocked = $request->input('blocked', null);
    $customer_id = $request->input('customer_id', null);
    $uEmail = $request->input('email');
    $uPhone = $request->input('phone');
    $type = $request->input('date_type');
    $importe = $request->input('importe', 0);
    $rate_id = $request->input('rate_id');
    $user_id = $request->input('user_id');
    $date = $request->input('date');
    $hour = $request->input('hour');
    $cHour = $request->input('customTime');
    $isGroup = ($request->input('is_group') == 'on');
    $timeCita = strtotime($date);
    /* -------------------------------------------------------------------- */
    $oCarbon = Carbon::createFromFormat('d-m-Y H:00:00', "$date $hour:00:00");
    $date_compl = $oCarbon->format('Y-m-d H:i:00');

    /* -------------------------------------------------------------------- */
    if ($blocked && !$isGroup) {
      $alreadyExit = Dates::where('date', $date_compl)
              ->where('id', '!=', $ID)
              ->where('user_id', $user_id)
              ->first();
      if ($alreadyExit) {
        $msg = 'Personal ocupado';
        return redirect()->back()->withErrors([$msg]);
      }
      $oObj = Dates::find($ID);
      $oObj->user_id = $user_id;
      $oObj->date = $date_compl;
      $oObj->updated_at = $date;
      $oObj->save();
      return redirect()->back();
    }
    /* -------------------------------------------------------------------- */
    $validated = $this->validate($request, [
        'date' => 'required',
        'rate_id' => 'required',
        'user_id' => 'required',
            ], [
        'date.required' => 'Fecha requerida',
        'rate_id.required' => 'Tarifa requerida',
        'user_id.required' => 'Coach requerido',
    ]);
    /* -------------------------------------------------------------------- */
    $alreadyExit = Dates::where('date', $date_compl)
                    ->where('id', '!=', $ID)
                    ->where('date_type', $type)
                    ->where('blocked', 1)
                    ->where('user_id', $user_id)->first();
    if ($alreadyExit) {
      return redirect()->back()->withErrors(['Horario bloqueado']);
    }
    $alreadyExit = Dates::where('date', $date_compl)
                    ->where('id', '!=', $ID)
                    ->where('user_id', $user_id)->count();
    if ($alreadyExit > 1) {
      return redirect()->back()->withErrors(['Personal ocupado']);
    }
    /* -------------------------------------------------------------------- */
    if (!$isGroup) {
      if (!$customer_id) {
        $issetUser = User::where('email', $uEmail)->first();
        if ($issetUser) {
          return redirect()->back()->withErrors(["email duplicado"])->withInput();
        } else {
          $oUser = new User();
          $oUser->name = $request->input('u_name');
          $oUser->email = $uEmail;
          $oUser->password = str_random(60); //bcrypt($request->input('password'));
          $oUser->remember_token = str_random(60);
          $oUser->role = 'user';
          $oUser->phone = $uPhone;
          $oUser->save();
          $customer_id = $oUser->id;
        }
      } else {
        $oUser = User::find($customer_id);

        if ($oUser && $oUser->email != $uEmail) {
          $oUser->email = $uEmail;
          $oUser->save();
        }
        if ($uPhone && $oUser->phone != $uPhone) {
          $oUser->phone = $uPhone;
          $oUser->save();
        }
      }

      $alreadyExit = Dates::where('date', $date_compl)
                      ->where('id', '!=', $ID)
                      ->where('customer_id', $customer_id)->count();
      if ($alreadyExit > 1) {
        return redirect()->back()->withErrors(['Usuario ocupado']);
      }
    }
    /* -------------------------------------------------------------------- */
    $coachTimes = CoachTimes::where('user_id', $user_id)->first();
    if ($coachTimes) {
      $t_control = json_decode($coachTimes->times, true);
      $aux_d = $oCarbon->format('w');
      $aux_h = $oCarbon->format('H');
      if (isset($t_control[$aux_d])) {
        if (isset($t_control[$aux_d][$aux_h]) && $t_control[$aux_d][$aux_h] == 0)
          return redirect()->back()->withErrors(['Horario no disponible']);
      }
    }
    /* -------------------------------------------------------------------- */
    if ($ID) {
      $oObj = Dates::find($ID);
    } else {
      $oObj = new Dates();
    }
    /* -------------------------------------------------------------------- */
    //nueva cita => crear userRate
    $customer_rate_ids = null;
    if (!$isGroup) {
      $uRate = CustomersRates::find($oObj->customer_rate_ids);
      if ($uRate) {
        $uRate->rate_year = date('Y', $timeCita);
        $uRate->rate_month = date('n', $timeCita);
        $uRate->price = $importe;
        $uRate->rate_id = $rate_id;
        $uRate->coach_id = $user_id;
        $uRate->save();
      } else {

        $uRate = new CustomersRates();
        $uRate->customer_id = $oUser->id;
        $uRate->rate_id = $rate_id;
        $uRate->rate_year = date('Y', $timeCita);
        $uRate->rate_month = date('n', $timeCita);
        $uRate->price = $importe;
        $uRate->coach_id = $user_id;
        $uRate->save();
      }
      if (!$uRate)
        return redirect()->back()->withErrors(['Servicio no encontrado']);

      $customer_rate_ids = $uRate->id;
    }
    /* -------------------------------------------------------------------- */
    $oObj->rate_id = $rate_id;
    $oObj->customer_id = $customer_id;
    $oObj->user_id = $user_id;
    $oObj->customer_rate_ids = $customer_rate_ids;
    $oObj->date_type = $type;
    $oObj->date = $date_compl;
    $oObj->customTime = $cHour;
    $oObj->updated_at = $date;

    if ($isGroup) {
      $oObj->price = $importe;
      $oObj->customer_id = 0;
      $oObj->blocked = 1;
      $oObj->is_group = 1;
      $oObj->save();

      if ($type == 'nutri')
        return redirect('/admin/citas/edit/' . $oObj->id);
      if ($type == 'fisio')
        return redirect('/admin/citas-fisioterapia/edit/' . $oObj->id);
    }



    if ($oObj->save()) {
      $timeCita = strtotime($oObj->date);
      $service = Rates::find($oObj->rate_id);
      $coach = User::find($oObj->user_id);
      $oRate = Rates::find($oObj->rate_id);

      /*       * BEGIN: prepare iCAL * */
      $uID = str_pad($oObj->id, 7, "0", STR_PAD_LEFT);
      $invite = new \App\Services\InviteICal($uID);
      $dateTime = $oObj->date;
      if ($oObj->customTime) {
        $dateTime = explode(' ', $oObj->date);
        $dateTime = $dateTime[0] . ' ' . $oObj->customTime;
      }
      $dateZone = 'Europe/Madrid';
      //$dateZone = 'America/Argentina/Buenos_Aires';
      $dateStart = new \DateTime($dateTime, new \DateTimeZone($dateZone));
      $dateEnd = new \DateTime($dateTime, new \DateTimeZone($dateZone));
      $dateEnd->modify('+1 hours');
      $dateStart->setTimezone(new \DateTimeZone('UTC'));
      $dateEnd->setTimezone(new \DateTimeZone('UTC'));
      $icsDetail = 'Tienes una cita con tu ';
      switch ($type) {
        case 'nutri':
          $icsDetail .= 'Nutricionista ';
          break;
        case 'fisio':
          $icsDetail .= 'Fisioterapeuta ';
          break;
      }
      $icsDetail .= $coach->name;
      $invite->setSubject($oRate->name)
              ->setDescription($icsDetail)
              ->setStart($dateStart)
              ->setEnd($dateEnd)
              ->setCreated(new \DateTime());
      $calFile = $invite->save();
      /** END:  prepare iCAL * */
      /* -------------------------------------------------------------------- */
      $subjet = 'Nueva cita en Araknet';
      if ($ID)
        $subjet = 'Actualización de su cita';

      //BEGIN: entrevista nutrición
      $urlEntrevista = null;
      if ($type == 'nutri') {
        $already = $oUser->getMetaContent('nutri_q1');
        if (!$already) {
          $code = encriptID($oUser->id) . '-' . encriptID(time() * rand());
          $keys = $code . '/' . getKeyControl($code);
          $urlEntrevista = \URL::to('/encuesta-nutricion') . '/' . $keys;
        }
      }
      //END: entrevista nutrición

      $pStripe = null;
      $return = MailController::sendEmailPayDateByStripe($oObj, $oUser, $oRate, $coach,$importe, $subjet, $calFile, $urlEntrevista);
      /* -------------------------------------------------------------------- */

      if ($type == 'nutri')
        return redirect('/admin/citas/edit/' . $oObj->id);
      if ($type == 'fisio')
        return redirect('/admin/citas-fisioterapia/edit/' . $oObj->id);
    }
  }

  public function chargeAdvanced(Request $req) {
    $oDates = Dates::find($req->idDate);
    if (!$oDates)
      return redirect()->back()->with(['error' => 'Cita no encontada']);

    $uRate = $oDates->uRates;
    if (!$uRate)
      return redirect()->back()->with(['error' => 'Cita no encontada']);

    $oUser = $uRate->user;
    $service = $oDates->service;
    $oRate = $uRate->rate;
    $payType = $req->input('type_payment');
    if (!$oRate)
      return redirect()->back()->with(['error' => 'Tarifa no encontada']);


    $value = $uRate->price;
    //Save payment
    $ChargesDate = new \App\Services\ChargesDateService();
    $ChargesDate->generatePayment($oDates, $payType, $value);
    return redirect()->back()->with(['success' => 'Cobro guadado']);
  }

  function openChargeDate($id) {
    $obj = Dates::find($id);
    if (!$obj) {
      CustomersRates::where('id_appointment', $id)->delete();
      echo 'Registro no encontrado.';
    } else {
      $date_type = $obj->date_type;
      switch ($date_type) {
        case 'nutri':
          header('Location: /admin/citas/edit/' . $id);
          exit();
          break;
        case 'fisio':
          header('Location: /admin/citas-fisioterapia/edit/' . $id);
          exit();
          break;
      }
      dd($obj);
    }
  }

  function blockDates($type) {
    $coachs = \App\Services\CitasService::getCoachs($type);
    $cNames = [];
    if ($coachs) {
      foreach ($coachs as $item) {
        $cNames[$item->id] = $item->name;
      }
    }

    return view('calendars.blockDates', [
        'coachs' => $cNames,
        'type' => $type
    ]);
  }

  function blockDatesSave(Request $req) {

    $type = $req->input('date_type');
    $user_id = $req->input('user_id');
    $start = $req->input('start');
    $end = $req->input('end');
    $hours = $req->input('hours');

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
            $oObj->customer_rate_ids = -1;
            $oObj->date_type = $type;
            $oObj->date = $dateHour;
            $oObj->save();
          }
        }
      }
    }
    return redirect()->back()->with(['success' => 'Horarios bloqueados']);
  }

  function cloneDates($id) {
    $obj = Dates::find($id);
    $cNames = [];
    $uRate = $obj->uRates;

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
    $rslt['uRate'] = $uRate;

    $times = [];
    return view('calendars.cloneDates', $rslt);
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
    $uRate = $oDate->uRates;

    foreach ($aDates as $d) {
      $timeCita = strtotime($d);
      $urClone = new CustomersRates();
      $urClone->customer_id = $uRate->customer_id;
      $urClone->rate_id = $uRate->rate_id;
      $urClone->rate_year = date('Y', $timeCita);
      $urClone->rate_month = date('n', $timeCita);
      $urClone->price = $uRate->price;
      $urClone->coach_id = $uRate->coach_id;
      $urClone->save();
      $customer_rate_ids = $urClone->id;

      $clone = new Dates();
      $clone->date = $d;
      $clone->rate_id = $oDate->rate_id;
      $clone->customer_id = $oDate->customer_id;
      $clone->user_id = $oDate->user_id;
      $clone->date_type = $oDate->date_type;
      $clone->customer_rate_ids = $customer_rate_ids;
      $clone->save();
    }

    return redirect()->back()->with(['success' => 'Citas Creadas']);
  }

  public function checkDateDisp(Request $req) {

    $date = $req->input('date');
    $time = $req->input('time');
    $ID = $req->input('id');
    $uID = $req->input('uID');
    $type = $req->input('type');
    $cID = $req->input('cID'); //user_id


    $aux = explode('-', $date);
    if (is_array($aux) && count($aux) == 3) {
      $date = $aux[2] . '-' . $aux[1] . '-' . $aux[0];
    }

    $dateCompl = $date . " $time:00:00";

    $sqlCoach = Dates::where('date', $dateCompl)->where('user_id', $cID);
    $sqlUser = Dates::where('date', $dateCompl)->where('customer_id', $uID);
    $sqlBloq = Dates::where('date', $dateCompl)->where('user_id', $cID);

    if ($ID && $ID != 'undefined') {
      $sqlCoach->where('id', '!=', $ID);
      $sqlBloq->where('id', '!=', $ID);
      $sqlUser->where('id', '!=', $ID);
    }

    if ($sqlBloq->where('date_type', $type)->where('blocked', 1)->first()) {
      return 'bloqueo';
    }

    $useCoach = $sqlCoach->count();
    $useUser = $sqlUser->count();

//    dd($useCoach,$useUser,$req->all());
    return ($useCoach > $useUser) ? $useCoach : $useUser;
  }

}
