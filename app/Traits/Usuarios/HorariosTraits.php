<?php

namespace App\Traits\Usuarios;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UsersTimes;
use App\Models\Rates;

trait HorariosTraits {

public function horarios($id = null) {


    $aUsers = User::whereBy_role()->orderBy('name')->pluck('name', 'id')->toArray();
    //---------------------------------------------------------------//
    $days = listDaysSpanish(false);
    $horarios = [];
    $aux = ['', '', '', ''];
    foreach ($days as $k => $v) {
      $horarios[$k] = $aux;
    }
    //---------------------------------------------------------------//
    $UsersTimes = UsersTimes::where('user_id', $id)->first();
    if ($UsersTimes) {
      $t = json_decode($UsersTimes->horarios, true);
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

    $UsersTimes = UsersTimes::where('user_id', $uID)->first();
    if (!$UsersTimes) {
      $UsersTimes = new UsersTimes();
      $UsersTimes->user_id = $uID;
    }
    $UsersTimes->horarios = json_encode($horarios);
    $UsersTimes->times = json_encode($h2);
    $UsersTimes->save();
    return redirect()->back()->with(['success' => 'Horario actualizado']);
  }
  }