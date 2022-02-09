<?php

namespace App\Services;

use App\Models\CoachLiquidation;
use App\Models\User;
use App\Models\CoachRates;
use App\Models\Dates;

class CoachLiqService {

  function liqByMonths($year, $type = null) {

    $aLiq = $CommLst = $liqLst = $aLiqTotal = [];
    $months = lstMonthsSpanish();
    unset($months[0]);
    $sql = User::whereCoachs(null,true);
    if ($type == 'activos')
      $sql->where('status', 1);
    if ($type == 'desactivados')
      $sql->where('status', 0);

    $users = $sql->orderBy('status', 'DESC')->get();
    $aux = [];
    for ($i = 1; $i < 13; $i++)
      $aux[$i] = 0;

    foreach ($users as $u) {
      $aLiq[$u->id] = $aux;
    }
    //---------------------------------------------------------------//
    // Get Saved liquidations
    $oLiquidations = CoachLiquidation::whereYear('date_liquidation', '=', $year)->get();
    if ($oLiquidations) {
      foreach ($oLiquidations as $liq) {
        if (!isset($aLiq[$liq->id_coach])) {
          $aLiq[$liq->id_coach] = $aux;
        }
        $aux2 = intval(substr($liq->date_liquidation, 5, 2));
        $aLiq[$liq->id_coach][$aux2] += ($liq->commision + $liq->salary);
      }
    }

    //---------------------------------------------------------------//
    // Calculate total
    foreach ($users as $u) {
      $aLiqTotal[$u->id] = array_sum($aLiq[$u->id]);
    }

    return [
        'months' => $months,
        'year' => $year,
        'users' => $users,
        'aLiq' => $aLiq,
        'aLiqTotal' => $aLiqTotal,
    ];
  }
  
  function liqMensualBasic($id, $year, $month) {
    $lstMonts = lstMonthsSpanish();
    $typePT = 2;

    $taxCoach = CoachRates::where('id_user', $id)->first();

    $ppc = $salary = $comm = $pppt = $ppcg = 0;
    if ($taxCoach) {
      $ppc = $taxCoach->ppc;
      $pppt = $taxCoach->pppt;
      $ppcg = $taxCoach->ppcg;
      $comm = $taxCoach->comm / 100;
      $salary = $taxCoach->salary;
    }
    //---------------------------------------------------------------//

    $oLiq = CoachLiquidation::where('id_coach', $id)
            ->whereYear('date_liquidation', '=', $year)
            ->whereMonth('date_liquidation', '=', $month)
            ->first();
    if ($oLiq) {
      if ($oLiq->salary)
        $salary = $oLiq->salary;
    }
    //---------------------------------------------------------------//
    /** @ToDo ver si es sólo citas o todos los cobros */
    $oTurnos = Dates::where('id_coach', $id)
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->join('users_rates', 'users_rates.id', '=', 'id_user_rates')
            ->with('user')->with('service')->with('uRates')
            ->orderBy('date')
            ->get();
//            ->whereNotNull('users_rates.id_charges')

    $totalClase = array();
    $pagosClase = array();
    $classLst = [];

    if ($oTurnos) {
      foreach ($oTurnos as $item) {
        $key = $item->service->id;
        if (!isset($classLst[$key])) {
          $classLst[$key] = $item->service->name;
          $pagosClase[$key] = [];
          $totalClase[$key] = 0;
        }

        $import = 0;
        if ($item->uRates && $item->uRates->charges)
          $import = $item->uRates->charges->import;
        else{
          if ($item->uRates) $import = $item->uRates->price;
        }

        $totalClase[$key] += $import * $comm;

        if ($item->service->type == $typePT) {
          /* precio de entrenamiento personal */
          $totalClase[$key] += $pppt;
        } else {
          $totalClase[$key] += $ppc;
        }

        $time = strtotime($item->date);
        $className = date('d', $time) . ' de ' . $lstMonts[date('n', $time)];
        $className .= ' a las ' . date('h a', $time);
        $className .= ' (cliente : ' . $item->user->name . ')';
        $pagosClase[$key][] = $className;
      }
    }
    
    
    /**
     * Citas grupales
     */
    
    $oTurnos = Dates::where('id_coach', $id)
            ->where('is_group', 1)
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->with('service')
            ->orderBy('date')
            ->get();
    if ($oTurnos) {
      foreach ($oTurnos as $item) {
        $key = $item->service->id;
        if (!isset($classLst[$key])) {
          $classLst[$key] = $item->service->name;
          $pagosClase[$key] = [];
          $totalClase[$key] = 0;
        }
        
        $totalClase[$key] += $ppcg;
        $time = strtotime($item->date);
        $className = date('d', $time) . ' de ' . $lstMonts[date('n', $time)];
        $className .= ' a las ' . date('h a', $time);
        $className .= ' (Cita Grupal)';
        $pagosClase[$key][] = $className;
      }
    }
        
    /**
     * END: Citas grupales
     */
    
    
    
    
    return compact('pagosClase', 'totalClase', 'classLst', 'ppc', 'salary');
  }

  function liquMensual($id, $year, $month) {

    $data = $this->liqMensualBasic($id, $year, $month);
    
    //-----------------------------------------------------------//
    $oExpenses = \App\Models\Expenses::where('to_user', $id)
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->orderBy('date')
            ->get();
    $lstExpType = \App\Models\Expenses::getTypes();
    $totalExtr = $nExtr = [];
    if ($oExpenses) {
      foreach ($oExpenses as $item) {
        $key = $item->type;
        if (!isset($totalExtr[$key])) {
          $totalExtr[$key] = 0;
          $nExtr[$key] = $lstExpType[$key];
        }
        $totalExtr[$key] += $item->import;
      }
    }

    $data['totalExtr'] = $totalExtr;
    $data['nExtr'] = $nExtr;
    return $data;
  }

    
//---------------------------------------------------------------//
  function liqByCoachMonths($year) {

    $aux = ['username'=>'Usuario no encontrado','role'=>''];
    for ($i = 1; $i < 13; $i++)  $aux[$i] = 0;
    $aLiq = [];
    //---------------------------------------------------------------//
    // Get Saved liquidations
    $oLiquidations = CoachLiquidation::whereYear('date_liquidation', '=', $year)->get();
    if ($oLiquidations) {
      foreach ($oLiquidations as $liq) {
        if (!isset($aLiq[$liq->id_coach])) {
          $aLiq[$liq->id_coach] = $aux;
        }
        $aux2 = intval(substr($liq->date_liquidation, 5, 2));
        $aLiq[$liq->id_coach][$aux2] += ($liq->commision + $liq->salary);
      }
    }
    foreach ($aLiq as $k=>$v){
      $aLiq[$k][0] = array_sum($v);
    }
    
    //get users liq
    $lstUsers = User::whereIn('id',array_keys($aLiq))->get();
    foreach ($lstUsers as $u){
      if (isset($aLiq[$u->id])){
        $aLiq[$u->id]['username'] = $u->name;
        $aLiq[$u->id]['role'] = $u->role;
      }
    }
    $months = lstMonthsSpanish();
    unset($months[0]);
    
    return ['liq'=>$aLiq,'months'=>$months];
  }
}
