<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Dates;
use App\Models\User;
use App\Models\Customers;
use App\Models\CustomersRates;
use App\Models\Rates;
use App\Models\UsersTimes;
use App\Models\TypesRate;
use App\Services\CitasService;
use \App\Traits\Citas\CloneTraits;
use \App\Traits\Citas\BlocksTraits;

class CitasController extends Controller {

  use CloneTraits,BlocksTraits;
  function typeControl($type) {
    if (!in_array($type, ['comercial', 'instalador'])) {
      abort(404);
      exit();
    }

    if ($type == 'comercial')
      $role = 'commercial';
    if ($type == 'instalador')
      $role = 'installer';

    return $role;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($type = null, $month = null, $user = 0, $serv = 0) {
    $role = $this->typeControl($type);

    if (!$month) {
      $yearActive = getYearActive();
      $month = $yearActive . date('-m');
    }
    $date = $month . '-01';

    $oCalendar = new \App\Services\CalendarService($date);
    $calendar = $oCalendar->getCalendarWeeks();
    $start = $calendar['firstDay'];
    $finish = $calendar['lastDay'];
    $rslt = CitasService::get_calendars($start, $finish, $serv, $user, $role, $calendar['days']);
    $rslt['calendar'] = $calendar['days'];
    $rslt['month'] = $month;

    $rslt['type'] = $type;
    if ($type == 'comercial')
      $rslt['title'] = 'Comercial';
    if ($type == 'instalador')
      $rslt['title'] = 'Instalador';
    /*     * **************************************** */
    return view('citas.index', $rslt);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function indexWeek($type, $week = null, $user = 0, $serv = 0) {

    $role = $this->typeControl($type);

    $yearActive = getYearActive();
    if (!$week)
      $week = date('W');
    if ($week < 10)
      $week = '0' . intVal($week);

    $time = strtotime($yearActive . 'W' . $week);
    $date = date('Y-m-d', $time);

    $oCalendar = new \App\Services\CalendarService($date);
    $calendar = $oCalendar->getCalendarOneWeek();

    $start = $calendar['firstDay'];
    $finish = $calendar['lastDay'];
    $rslt = CitasService::get_calendars($start, $finish, $serv, $user, $role, $calendar['days']);
    $rslt['calendar'] = $calendar['days'];
    $rslt['week'] = $week;
    $rslt['time'] = $time;

    $rslt['type'] = $type;
    if ($type == 'comercial')
      $rslt['title'] = 'Comercial';
    if ($type == 'instalador')
      $rslt['title'] = 'Instalador';
    /*     * **************************************** */
    return view('citas.indexWeek', $rslt);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($type, $date = null, $time = null) {
    $role = $this->typeControl($type);
    

    $data = CitasService::get_create($date, $time, $role);
    $data['type'] = $type;
    if ($type == 'comercial')
      $data['title'] = 'Comercial';
    if ($type == 'instalador')
      $data['title'] = 'Instalador';
    return view('citas.form', $data);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($type,$id) {
    $role = $this->typeControl($type);
    $data = CitasService::get_edit($id);
    if ($data) {
      $data['type'] = $type;
      if ($type == 'comercial')
        $data['title'] = 'Comercial';
      if ($type == 'instalador')
        $data['title'] = 'Instalador';
      return view('citas.form', $data);
    } else {
      return $this->create($type);
    }
  }

  public function listado($type,$uID = 0, $rate = 0) {
    $role = $this->typeControl($type);
    $year = getYearActive();
    $month = null;
    /*     * ************************************************** */
    $servic = Rates::getByTypeRate($role)->pluck('name', 'id');
    /*     * ************************************************** */
    $aLst = [];
    $aCustomer = $cIDs = [];
    $sql = Dates::where('date_type',$role)
            ->whereYear('date', '=', $year);
    if ($rate && $rate != 0)
      $sql->where('rate_id', $rate);
    if ($uID && $uID > 0)
      $sql->where('user_id', $uID);

    $oLst = $sql->orderBy('date')->get();

    if ($oLst) {
      foreach ($oLst as $item) {
        $time = strtotime($item->date);
        $month = date('n', $time);
        $date = date('d / H', $time);
        $cID = $item->customer_id;
        $cIDs[] = $cID;
        if (!isset($aLst[$cID]))
          $aLst[$cID] = [];
        if (!isset($aLst[$cID][$month]))
          $aLst[$cID][$month] = [];

        $aLst[$cID][$month][] = $date . ':00';
      }
    }
    /*     * ************************************************** */
    $oCustomers = Customers::whereIn('id', $cIDs)->get();
    /*     * ************************************************** */
    $lstMonts = lstMonthsSpanish();

    /*     * ************************************************** */
    $users = User::whereBy_role($role)->where('status', 1)->get();
    $tColors = [];
    if ($users) {
      $auxColors = colors();
      $i = 0;
      foreach ($users as $item) {
        if (!isset($auxColors[$i]))
          $i = 0;
        $tColors[$item->id] = $auxColors[$i];
        $i++;
      }
    }


    $rslt['type'] = $type;
    if ($type == 'comercial')
      $title = 'Comercial';
    if ($type == 'instalador')
      $title = 'Instalador';

    $rslt = [
        'type' => $type,
        'aLst' => $aLst,
        'aMonths' => $lstMonts,
        'year' => $year,
        'month' => $month,
        'rate' => $rate,
        'types' => $servic,
        'tColors' => $tColors,
        'users' => $users,
        'title' => $title,
        'userID' =>$uID,
        'oCustomers' => $oCustomers,
    ];

    return view('citas.listado', $rslt);
  }

  public function informe($uID) {
    $year = getYearActive();
    $customer = User::find($uID);
    $servic = TypesRate::where('type', 'fisio')->pluck('name', 'id');
    $users = User::whereBy_role('fisio')->pluck('name', 'id');
    $lstMonts = lstMonthsSpanish();
    /*     * ************************************************** */
    $aLst = [];
    $oLst = Dates::where('date_type', 'fisio')
                    ->where('customer_id', $uID)
                    ->whereYear('date', '=', $year)
                    ->orderBy('date')->get();

    if ($oLst) {
      foreach ($oLst as $i) {
        $time = strtotime($i->date);
        $month = date('n', $time);
        $date = date('d', $time);
        $hour = date('H', $time);
        $uID = $i->customer_id;
        $tm = isset($lstMonts[$month]) ? $lstMonts[$month] : '';
        $aLst[] = [
            'id' => $i->id,
            'hour' => $hour . ':00',
            'date' => $date . ' ' . $tm,
            'rate' => isset($servic[$i->id_type_rate]) ? $servic[$i->id_type_rate] : '',
            'coach' => isset($users[$i->user_id]) ? $users[$i->user_id] : '',
            'charged' => $i->charged,
        ];
      }
    }

    $lstRates = [];
    $CustomersRates = CustomersRates::where('customer_id', $customer->id)
                    ->whereYear('created_at', "=", $year)->get();
    if ($CustomersRates) {
      foreach ($CustomersRates as $i) {
        $lstRates[] = $i->rate->name;
      }
      $lstRates = array_unique($lstRates);
      sort($lstRates);
    }
    /*     * ************************************************** */
    return view('fisioterapia.informe', [
        'user' => $customer,
        'aLst' => $aLst,
        'lstRates' => $lstRates,
        'id' => $uID,
        'year' => $year,
    ]);
  }

  public function toggleIcon(Request $request) {

    $id = $request->input('id');
    $ico = $request->input('ico');

    $oDate = Dates::find($id);
    if (!$oDate)
      return 'Cita no encontrada';

    $ecogr = $oDate->getMetaContent($ico);
    if ($ecogr && $ecogr == 1)
      $ecogr = 0;
    else
      $ecogr = 1;

    $oDate->setMetaContent($ico, $ecogr);

    return 'OK';
  }



  public function save(Request $request) {

    $ID = $request->input('idDate', null);
    $blocked = $request->input('blocked', null);
    $customer_id = $request->input('customer_id', null);
    $cEmail = $request->input('email');
    $cPhone = $request->input('phone');
    $type = $request->input('date_type');
    $importe = $request->input('importe', 0);
    $rate_id = $request->input('rate_id');
    $user_id = $request->input('user_id');
    $date = $request->input('date');
    $hour = $request->input('hour');
    $cHour = $request->input('customTime');
    $isGroup = ($request->input('is_group') == 'on');
    $timeCita = strtotime($date);
    $role = $this->typeControl($type);
    /* -------------------------------------------------------------------- */
    $oCarbon = Carbon::createFromFormat('d-m-Y H:00:00', "$date $hour:00:00");
    $date_compl = $oCarbon->format('Y-m-d H:i:00');

    /* -------------------------------------------------------------------- */
    if ($blocked) {
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
        'user_id.required' => 'Usuario requerido',
    ]);
    /* -------------------------------------------------------------------- */
    $alreadyExit = Dates::where('date', $date_compl)
                    ->where('id', '!=', $ID)
                    ->where('date_type', $role)
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
    if (!$customer_id) {
      $issetUser = Customers::where('email', $cEmail)->first();
      if ($issetUser) {
        return redirect()->back()->withErrors(["email duplicado"])->withInput();
      } else {
        $oCustomer = new Customers();
        $oCustomer->name = $request->input('c_name');
        $oCustomer->email = $cEmail;
        $oCustomer->password = str_random(60); //bcrypt($request->input('password'));
        $oCustomer->remember_token = str_random(60);
        $oCustomer->phone = $cPhone;
        $oCustomer->save();
        $customer_id = $oCustomer->id;
      }
    } else {
      $oCustomer = Customers::find($customer_id);

      if ($oCustomer && $oCustomer->email != $cEmail) {
        $oCustomer->email = $cEmail;
        $oCustomer->save();
      }
      if ($cPhone && $oCustomer->phone != $cPhone) {
        $oCustomer->phone = $cPhone;
        $oCustomer->save();
      }
    }

    $alreadyExit = Dates::where('date', $date_compl)
                    ->where('id', '!=', $ID)
                    ->where('customer_id', $customer_id)->count();
    if ($alreadyExit > 1) {
      return redirect()->back()->withErrors(['Cliente ocupado']);
    }
    /* -------------------------------------------------------------------- */
    $UsersTimes = UsersTimes::where('user_id', $user_id)->first();
    if ($UsersTimes) {
      $t_control = json_decode($UsersTimes->times, true);
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
    //nueva cita => crear customerRate
    $customers_rate_id = null;
    $customerRate = CustomersRates::find($oObj->customers_rate_id);
    if ($customerRate) {
      $customerRate->rate_year = date('Y', $timeCita);
      $customerRate->rate_month = date('n', $timeCita);
      $customerRate->price = $importe;
      $customerRate->rate_id = $rate_id;
      $customerRate->user_id = $user_id;
      $customerRate->save();
    } else {
      $customerRate = new CustomersRates();
      $customerRate->customer_id = $customer_id;
      $customerRate->rate_id = $rate_id;
      $customerRate->rate_year = date('Y', $timeCita);
      $customerRate->rate_month = date('n', $timeCita);
      $customerRate->price = $importe;
      $customerRate->user_id = $user_id;
      $customerRate->save();
    }
    if (!$customerRate)
      return redirect()->back()->withErrors(['Servicio no encontrado']);

    $customers_rate_id = $customerRate->id;
    /* -------------------------------------------------------------------- */
    $oObj->rate_id = $rate_id;
    $oObj->customer_id = $customer_id;
    $oObj->user_id = $user_id;
    $oObj->customers_rate_id = $customers_rate_id;
    $oObj->date_type = $role;
    $oObj->date = $date_compl;
    $oObj->customTime = $cHour;
    $oObj->updated_at = $date;

    if ($oObj->save()) {
      $timeCita = strtotime($oObj->date);
      $oUser = User::find($oObj->user_id);
      $oRate = Rates::find($oObj->rate_id);

      /*       * BEGIN: prepare iCAL * */
      $calID = str_pad($oObj->id, 7, "0", STR_PAD_LEFT);
      $invite = new \App\Services\InviteICal($calID);
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
      switch ($role) {
        case 'commercial':
          $icsDetail .= 'Agente Comercial ';
          break;
        case 'commercial':
          $icsDetail .= 'Instalador ';
          break;
      }
      $icsDetail .= $oUser->name;
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
      $pStripe = null;
      $return = MailController::sendEmailPayDateByStripe($oObj, $oCustomer, $oRate, $oUser, $importe, $subjet, $calFile);
      /* -------------------------------------------------------------------- */

      return redirect('/admin/citas/' . $type . '/edit/' . $oObj->id);
    }
  }
  
  public function checkDateDisp(Request $req) {

    $date = $req->input('date');
    $time = $req->input('time');
    $ID = $req->input('id');
    $uID = $req->input('uID');
    $type = $req->input('type');
    $cID = $req->input('cID'); //customer


    $aux = explode('-', $date);
    if (is_array($aux) && count($aux) == 3) {
      $date = $aux[2] . '-' . $aux[1] . '-' . $aux[0];
    }

    $dateCompl = $date . " $time:00:00";

    $sqlUser = Dates::where('date', $dateCompl)->where('user_id', $uID);
    $sqlCustomer = Dates::where('date', $dateCompl)->where('customer_id', $cID);
    $sqlBloq = Dates::where('date', $dateCompl)->where('user_id', $uID);

    if ($ID && $ID != 'undefined') {
      $sqlCustomer->where('id', '!=', $ID);
      $sqlBloq->where('id', '!=', $ID);
      $sqlUser->where('id', '!=', $ID);
    }

    if ($sqlBloq->where('date_type', $type)->where('blocked', 1)->first()) {
      return 'bloqueo';
    }

    $sqlCustomer = $sqlCustomer->count();
    $useUser = $sqlUser->count();

    return ($sqlCustomer > $useUser) ? $sqlCustomer : $useUser;
  }

  public function chargeAdvanced(Request $req) {
    $oDates = Dates::find($req->idDate);
    if (!$oDates)
      return redirect()->back()->with(['error' => 'Cita no encontada']);

    $customerRate = $oDates->cRates;
    if (!$customerRate)
      return redirect()->back()->with(['error' => 'Cita no encontada']);

    $oCustomer = $customerRate->customer;
    $service = $oDates->service;
    $oRate = $customerRate->rate;
    $payType = $req->input('type_payment');
    if (!$oRate)
      return redirect()->back()->with(['error' => 'Tarifa no encontada']);


    $value = $customerRate->price;
    //Save payment
    $ChargesDate = new \App\Services\ChargesDateService();
    $ChargesDate->generatePayment($oDates, $payType, $value);
    return redirect()->back()->with(['success' => 'Cobro guadado']);
  }
  
  
   public function delete($id) {
    $object = Dates::find($id);
    if ($object) {
        /* Busca y elimina el user_rate */
        $cRate = $object->cRates;
        if ($cRate) {
          /* Buscar y elimnar cobro */
          $charge = $cRate->charges;
          if ($charge)
            $charge->delete();
          $cRate->delete();
        }
      }
      $object->delete();
      return redirect()->back();
  }
  
  public function sendNotification(Request $request) {
        $dID = $request->input('idDate');
        
        $oDate = \App\Models\Dates::find($dID);
        if (!$oDate || $oDate->id != $dID) {
            return response()->json(['error','Cita No encontrada']);
        }
        
        $oUser = $oDate->user;
        
        $email = ($request->input('c_email'));
        $phone = ($request->input('c_phone'));
        $type = ($request->input('type'));
        
        $cRates = $oDate->cRates;
        if (!$cRates){
          return ['error', 'Servicio no encontrado'];
        }
        $oCustomer = $cRates->customer;
        if (!$oCustomer){
          return ['error', 'Cliente no encontrado'];
        }
        $oRate = $cRates->rate;
        $importe = $cRates->price;
        $data = [$dID,$oCustomer->id,$importe*100,$oRate->id];
        
        $icsDetail = 'Tienes una cita con tu ';
        switch ($oDate->date_type) {
          case 'commercial':
            $icsDetail .= 'Agente Comercial ';
            break;
          case 'commercial':
            $icsDetail .= 'Instalador ';
            break;
        }
        $icsDetail .= $oUser->name;
                
        switch ($type){
            case 'mail':
              
                /** BEGIN: prepare iCAL * */
                $calID = str_pad($dID, 7, "0", STR_PAD_LEFT);
                $invite = new \App\Services\InviteICal($calID);
                $dateTime = $oDate->date;
                if ($oDate->customTime) {
                  $dateTime = explode(' ', $oDate->date);
                  $dateTime = $dateTime[0] . ' ' . $oDate->customTime;
                }
                $dateZone = 'Europe/Madrid';
                //$dateZone = 'America/Argentina/Buenos_Aires';
                $dateStart = new \DateTime($dateTime, new \DateTimeZone($dateZone));
                $dateEnd = new \DateTime($dateTime, new \DateTimeZone($dateZone));
                $dateEnd->modify('+1 hours');
                $dateStart->setTimezone(new \DateTimeZone('UTC'));
                $dateEnd->setTimezone(new \DateTimeZone('UTC'));
                
                $invite->setSubject($oRate->name)
                        ->setDescription($icsDetail)
                        ->setStart($dateStart)
                        ->setEnd($dateEnd)
                        ->setCreated(new \DateTime());
                $calFile = $invite->save();
                /** END:  prepare iCAL * */
      
                $resp = MailController::sendEmailPayDateByStripe($oDate, $oCustomer, $oRate,$oUser,$importe,$subj="Link de pago de Evolutio",$calFile);
                if ($resp == 'OK')  return response()->json(['OK', 'Se ha enviado un email con el link de pago']);
                  return response()->json(['error', $resp]);
                break;
            case 'wsp':
                $msg = 'Hola, te recordamos que '.$icsDetail;
                return response()->json(['OK',$msg]);
                break;
            case 'copy':
                $msg = 'Hola, te recordamos que '.$icsDetail;
                return response()->json(['OK',$msg]);
                break;
        }
            
        return response()->json(['error','error']);

    }
}
