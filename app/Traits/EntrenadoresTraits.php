<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\CoachTimes;
use App\Models\Rates;

trait EntrenadoresTraits {

  public function entrenadores($type = null) {

    $year = getYearActive();
    $CoachLiqService = new \App\Services\CoachLiqService();
    $data = $CoachLiqService->liqByMonths($year, $type);
    $data['type'] = $type;
    $data['date'] = Carbon::now();
    
    $auxMonths = [0=>0];
    for($i=1;$i<13;$i++) $auxMonths[$i] = 0;
    //---------------------------------------------------------------//
    // get expenses asociated
    $oExpenses = \App\Models\Expenses::where('to_user', '>', 0)
            ->whereYear('date', '=', $year)
            ->orderBy('date')
            ->get();
    $lstExpType = \App\Models\Expenses::getTypes();
    if ($oExpenses) {
      foreach ($oExpenses as $item) {
        $auxM = intval(substr($item->date, 5, 2));
        if (!isset($data['aLiq'][$item->to_user])){
          $data['aLiq'][$item->to_user] = $auxMonths;
          $data['aLiqTotal'][$item->to_user] = 0;
        }
        
        $data['aLiq'][$item->to_user][$auxM] += $item->import;
        $data['aLiqTotal'][$item->to_user] += $item->import;
      }
    }
    return view('/admin/usuarios/entrenadores/index', $data);
  }

  public function updEntrenador($id) {

    $oUser = User::find($id);
    //---------------------------------------------------------------//
    $month = date('Y-m');
    $lstMonts = lstMonthsSpanish();
    $aMonths = [];
    $year = getYearActive();
    foreach ($lstMonts as $k => $v) {
      if ($k > 0)
        $aMonths[$year . '-' . str_pad($k, 2, "0", STR_PAD_LEFT)] = $v;
    }
    //---------------------------------------------------------------//
    $coachRates = \App\Models\CoachRates::where('id_user', $oUser->id)->first();
    $salario_base = $ppc = $comm = $pppt = $ppcg = 0;
    if ($coachRates) {
      $salario_base = $coachRates->salary;
      $ppc = $coachRates->ppc;
      $pppt = $coachRates->pppt;
      $ppcg = $coachRates->ppcg;
      $comm = $coachRates->comm;
    }

    //---------------------------------------------------------------//
    return view('/admin/usuarios/entrenadores/_form', [
        'rates' => Rates::all(),
        'user' => $oUser,
        'aMonths' => $aMonths,
        'year' => $year,
        'month' => $month,
        'salario_base' => $salario_base,
        'ppc' => $ppc,
        'pppt' => $pppt,
        'ppcg' => $ppcg,
        'comm' => $comm,
    ]);
  }

  public function horarios($id = null) {


    $aUsers = User::whereCoachs()->orderBy('name')->pluck('name', 'id')->toArray();
    //---------------------------------------------------------------//
    $days = listDaysSpanish(false);
    $horarios = [];
    $aux = ['', '', '', ''];
    foreach ($days as $k => $v) {
      $horarios[$k] = $aux;
    }
    //---------------------------------------------------------------//
    $coachTimes = CoachTimes::where('id_coach', $id)->first();
    if ($coachTimes) {
      $t = json_decode($coachTimes->horarios, true);
      if ($t) {
        foreach ($days as $k => $v) {
          if (isset($t[$k])) {
            $aux = $t[$k];
            $horarios[$k] = [
                isset($aux[0]) ? $aux[0] : '',
                isset($aux[1]) ? $aux[1] : '',
                isset($aux[2]) ? $aux[2] : '',
                isset($aux[3]) ? $aux[3] : '',
            ];
          }
        }
      }
    }
    //---------------------------------------------------------------//

    return view('/admin/usuarios/entrenadores/horarios', [
        'days' => $days,
        'id' => $id,
        'aUsers' => $aUsers,
        'times' => $horarios,
    ]);
  }

  public function updHorarios(Request $request) {
    $uID = $request->input('uid', null);
    if (!$uID) {
      return redirect()->back()->withErrors(['Usuario no encontrado']);
    }

    //---------------------------------------------------------------//
    $times = [];
    for ($i = 8; $i < 23; $i++)
      $times[$i] = 0;
    //---------------------------------------------------------------//
    $aData = $request->all();
    $days = listDaysSpanish(false);
    $horarios = [];
    $h2 = []; // array horarios on/off
    $aux = ['', '', '', ''];
    foreach ($days as $k => $v) {
      $auxt = $times; // array horarios
      $st_0 = isset($aData["d_$k-0"]) ? $aData["d_$k-0"] : '';
      $st_1 = isset($aData["d_$k-0"]) ? $aData["d_$k-1"] : '';
      $st_2 = isset($aData["d_$k-0"]) ? $aData["d_$k-2"] : '';
      $st_3 = isset($aData["d_$k-0"]) ? $aData["d_$k-3"] : '';
      $horarios[$k] = [$st_0, $st_1, $st_2, $st_3];
      if ($st_0 && $st_0 < $st_1) {
        while ($st_0 < $st_1) {
          if (isset($auxt[$st_0]))
            $auxt[$st_0] = 1;
          $st_0++;
        }
      }
      if ($st_2 && $st_2 < $st_3) {
        while ($st_2 < $st_3) {
          if (isset($auxt[$st_2]))
            $auxt[$st_2] = 1;
          $st_2++;
        }
      }
      $h2[$k] = $auxt;
    }
    //---------------------------------------------------------------//

    $coachTimes = CoachTimes::where('id_coach', $uID)->first();
    if (!$coachTimes) {
      $coachTimes = new CoachTimes();
      $coachTimes->id_coach = $uID;
    }
    $coachTimes->horarios = json_encode($horarios);
    $coachTimes->times = json_encode($h2);
    $coachTimes->save();
    return redirect()->back()->with(['success' => 'Horario actualizado']);
  }

}
