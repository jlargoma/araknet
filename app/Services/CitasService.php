<?php

namespace App\Services;

use App\Models\Dates;
use App\Models\Rates;
use App\Models\User;
use App\Models\Customers;

class CitasService {

  static function get_edit($id) {
    $oDate = Dates::find($id);
    if ($oDate) {
      $date = explode(' ', $oDate->date);
      $customerRates = $oDate->cRates;
      $id_serv = $oDate->rate_id;
      $card    = null;
      $price   = $oDate->price;
      $customer_id = -1;
      $email   = null;
      $phone   = null;
      $charge  = null;
      $oCostumer  = null;
      if ($customerRates){
        $price   = $customerRates->price;
        $id_serv = $customerRates->rate_id;
        $oCostumer = $customerRates->customer;
        if ($oCostumer){
          $customer_id = $oCostumer->id;
          $email = $oCostumer->email;
          $phone = $oCostumer->phone;
          $charge = $customerRates->charges;
        }
      }
      $oServicios = Rates::getByTypeRate($oDate->date_type);
      $ecogr = $oDate->getMetaContent('ecogr');
      $indiba = $oDate->getMetaContent('indiba');
      return [
          'date' => date('d-m-Y', strtotime($date[0])),
          'time' => intval($date[1]),
          'id_serv' => $id_serv,
          'customer_id' => $customer_id,
          'user_id' => $oDate->user_id,
          'customTime' => $oDate->customTime,
          'email' => $email,
          'phone' => $phone,
          'price' => $price,
          'card' => $card,
          'id' => $oDate->id,
          'type' => $oDate->date_type,
          'charge' => $charge,
          'services' => $oServicios,
          'oCostumer' => $oCostumer,
          'allCustomers' => Customers::where('status', 1)->orderBy('name', 'ASC')->get(),
          'users' => self::getUsers($oDate->date_type),
          'blocked' => $oDate->blocked,
          'isGroup' => $oDate->is_group,
          'urlBack' => self::get_urlBack($oDate->date_type,$date[0]),
          'ecogr' => $ecogr,
          'indiba' => $indiba,
      ];
    }
    return null;
  }

  static function get_create($date,$time,$type) {
    if (!$date) $date = time();

    if($time>0) $time = '0'.$time;
    return [
      'date' => date('d-m-Y', $date),
      'time' => $time,
      'id_serv' => -1,
      'customer_id' => -1,
      'user_id' => -1,
      'customTime' => $time.':00',
      'email' => '',
      'phone' => '',
      'card' => null,
      'id' => -1,
      'charge' => null,
      'price' => 0,
      'type' => $type,
      'services' => Rates::getByTypeRate($type),
      'allCustomers' => Customers::where('status', 1)->orderBy('name', 'ASC')->get(),
      'users' => self::getUsers($type),
      'blocked' => false,
      'urlBack' => self::get_urlBack($type,date('Y-m-d', $date)),
     ];
  }
  
  static function get_calendars($start,$finish,$serv,$user,$type,$lstDays=null) {
        
    $times = [];    
    /**************************************************** */
    $servLst = Rates::getByTypeRate($type)->pluck('name', 'id');
    /**************************************************** */
    $users = self::getUsers($type);
    $tColors = [];
    $uNames = [];
    if ($users) {
        $auxColors = colors();
        $i = 0;
        foreach ($users as $item) {
            if (!isset($auxColors[$i]))
                $i = 0;
            $tColors[$item->id] = $auxColors[$i];
            $uNames[$item->id] = $item->name;
            $i++;
        }
    }

    /**************************************************** */
    $aLst = [];
    $sql = Dates::where('date_type', $type)
            ->where('date', '>=', date('Y-m-d', $start))
            ->where('date', '<=', date('Y-m-d', $finish));
    if ($serv && $serv != 0)
        $sql->where('rate_id', $serv);
    if ($user && $user > 0){
      $sql->where('user_id', $user);
      $UsersTimes = \App\Models\UsersTimes::where('user_id',$user)->first(); 
      if ($UsersTimes){
          $times = json_decode($UsersTimes->times,true);
          if (!is_array($times)) $times = [];
      }
    }

    /****************/
    $ecogrs = \DB::table('appointment_meta')
            ->where('meta_value',1)
            ->where('meta_key','ecogr')->pluck('appoin_id')->toArray();
    $indiba = \DB::table('appointment_meta')
            ->where('meta_value',1)
            ->where('meta_key','indiba')->pluck('appoin_id')->toArray();
    /****************/
    $oLst = $sql->get();
    $detail = [];
    $days = listDaysSpanish();
    $months = lstMonthsSpanish();
    $sValora = new ValoracionService();
    $daysUser = [];
    $countByUser = [];
    if ($oLst) {
        foreach ($oLst as $item) {
            $time = strtotime($item->date);
            $hour = date('G', $time);
            $date = date('Y-m-d', $time);
            $time = strtotime($date);

            $dTime = $hTime = $item->getHour();
            $dTime .= ' '.$days[date('w',$time)];
            $dTime .= ' '.date('d',$time).' '.$months[date('n',$time)];
            
            $hTime = substr($hTime, 3,2);
            
            
            if (!isset($aLst[$time])){
                $aLst[$time] = [];
                $daysUser[$time] = [];
            }
            if (!isset($aLst[$time][$hour])){
              $aLst[$time][$hour] = [];
              $daysUser[$time][$hour] = [];
            }
            $daysUser[$time][$hour][$item->user_id] = 1;
            if ($item->blocked){
              $aLst[$time][$hour][] = [
                'id' => $item->id,
                'charged' => ($item->is_group) ? 3 : 2,
                'type' => $item->rate_id,
                'user' => $item->user_id,
                'h'=>$hTime,
                'name' => ($item->is_group) ? 'grupo' : 'bloqueo',
                'ecogr' => false
              ];
              $detail[$item->id] = [
                  'n' => ($item->is_group) ? 'Cita Grupal' : 'bloqueo',
                  'p'=> '',
                  's'=> ($item->service) ? $item->service->name : '-',
                  'cn' => isset($uNames[$item->user_id]) ? $uNames[$item->user_id] : '-',
                  'mc'=>'', //Metodo pago
                  'dc'=>'', // fecha pago
                  'd'=>$dTime, // fecha 
              ];
              if (($item->is_group)){
                if (!isset($countByUser[$item->user_id])){
                  $countByUser[$item->user_id] = 1;
                } else {
                  $countByUser[$item->user_id]++;
                }
              }
              continue;
            }

            $customer_name = '';
            $customerRates = $item->cRates;
            $charge = null;
            if ($customerRates){
              $customer_name = ($customerRates->customer) ? $customerRates->customer->name : null;
              $charge = $customerRates->charges;
            }
            $charged = ($charge) ? 1 : 0;
            //------------------------------------
            $halfTime = false;
            if ($item->customTime){
              $dateTime = explode(' ', $item->date);
              $halfTime = ($dateTime[1] != $item->customTime);
            }
            //------------------------------------
            $aLst[$time][$hour][] = [
                'id' => $item->id,
                'charged' => $charged,
                'type' => $item->rate_id,
                'user' => $item->user_id,
                'name' => $customer_name,
                'halfTime'=>$halfTime,
                'h'=>$hTime,
                'ecogr' => (in_array($item->id,$ecogrs)),
                'indiba' => (in_array($item->id,$indiba)),
            ];
            $detail[$item->id] = [
                'n' => $customer_name,
                'p'=>($customerRates) ? moneda($customerRates->price): '--',
                's'=> ($item->service) ? $item->service->name : '-',
                'cn' => isset($uNames[$item->user_id]) ? $uNames[$item->user_id] : '-',
                'mc'=>'', //Metodo pago
                'dc'=>'', // fecha pago
                'd'=>$dTime, // fecha 
            ];

            if ($charge){
              $detail[$item->id]['mc'] = payMethod($charge->type_payment);
              $detail[$item->id]['dc'] = dateMin($charge->date_payment);
            }
            
            if (!isset($countByUser[$item->user_id])){
              $countByUser[$item->user_id] = 1;
            } else {
              $countByUser[$item->user_id]++;
            }
        }
    }
    /**************************************************** */
    $lstMonts = lstMonthsSpanish();
    $aMonths = [];
    $year = getYearActive();
    foreach ($lstMonts as $k => $v) {
        if ($k > 0)
            $aMonths[$year . '-' . str_pad($k, 2, "0", STR_PAD_LEFT)] = $v;
    }
    /**************************************************** */


    if (count($detail)>0){
      $aux = '';
      foreach ($detail as $k=>$d){
        $aux .= $k.':{';
        foreach ($d as $k2=>$i2){
          $aux .= "$k2: '$i2',";
        }
        $aux .= '},';
      }
      $detail = "{ $aux }";
    } else {
      $detail = null;
    }
    if ($type == 'pt') $avails = [];
    else $avails = self::timeAvails($daysUser,$users,$lstDays,$user);
    
    return  [
        'servLst' => $servLst,
        'serv' => $serv,
        'aLst' => $aLst,
        'aMonths'=> $aMonths,
        'year'   => $year,
        'tColors'=> $tColors,
        'users' => $users,
        'user'  => $user,
        'times'  => $times,
        'detail' => $detail,
        'avails' => $avails,
        'countByUser' => $countByUser,
    ];
  }
  
  static function getUsers($type) {
    return User::whereBy_role($type)->where('status', 1)->get();
  }
  
  static function timeAvails($daysUser,$users,$lstDays,$userID=null){
    $tCoach = [];
    if ($userID){
      $tCoach[$userID]=1;
    } else {
      foreach ($users as $i)  $tCoach[$i->id]=1;
    } 
    
    $disponibles = [];
    for($i=1; $i<7; $i++){
      $aux = [];
      for($j=8; $j<23; $j++){
        $aux[$j] = $tCoach;
      }
      $disponibles[$i] = $aux;
    }

    $UsersTimes = \App\Models\UsersTimes::whereIn('user_id', array_keys($tCoach))->pluck('times','user_id'); 
    if ($UsersTimes){
      foreach ($UsersTimes as $idCoach => $t){
        $times = json_decode($t,true);
        if (is_array($times)){
          foreach ($times as $d=>$hs){
            foreach ($hs as $h=>$enable){
              $disponibles[$d][$h][$idCoach] = $enable;
            }
          }
        }
      }
    }
    $wDay = listDaysSpanish(true);
    $used = [];
    if ($lstDays){
      foreach ($lstDays as $k=>$days){
       
          foreach($days as $k=>$d){
            $time = $d['time'];
            $wID = array_search($d['day'], $wDay);
            
            /////////////////////
            $aux = [];
            for($h=8; $h<23; $h++){
              $aux2 = [];
              foreach ($disponibles[$wID][$h] as $cID => $cAvail){
                if ($cAvail == 1) $aux2[] = $cID;
              }
              $aux[$h] = $aux2;
            }
            /////////////////////
            if (isset($daysUser[$time])){
              foreach ($daysUser[$time] as $h=>$item){
                $aux4 = isset($aux[$h]) ? $aux[$h] : [];
                foreach ($item as $cID=>$u){
                  $aux3 = array_search($cID, $aux4);
                  if ($aux3 !== false) unset($aux[$h][$aux3]);
                }
              }
            }
            /////////////////////
            
            $used[$time] = $aux;
        }
 
      }
    }
    return $used;
  }
  
  static function get_urlBack($type,$date){
    $urlBack = '/admin';
    if (isset($_GET['weekly'])){
      switch ($type) {
        case 'nutri':
          $urlBack = '/admin/citas-week/';
          break;
        case 'fisio':
          $urlBack = '/admin/citas-fisioterapia-week/';
          break;
        case 'pt':
          $urlBack = '/admin/citas-pt-week/';
          break;
    }
    
      $week = date('W', strtotime($date));
      if (date('W') != $week){
        $urlBack .= $week;
      }
      return $urlBack;
    }
    
    
    $date = substr($date,0,7);
    
    switch ($type) {
      case 'nutri':
        $urlBack = '/admin/citas/';
        break;
      case 'fisio':
        $urlBack = '/admin/citas-fisioterapia/';
        break;
      case 'pt':
        $urlBack = '/admin/citas-pt/';
        break;
    }
     
    if (date('Y-m') != $date)
      $urlBack .= $date;
    return $urlBack;
  }
}
