<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use DB;
use App\Models\Charges;

class IncomesController extends Controller {

  public function index($year = "") {
    $year = getYearActive();
    $monts = lstMonthsSpanish();
    unset($monts[0]);
    $mm = [];
    foreach ($monts as $k => $v)
      $mm[$k] = 0;

    $sIncomes = new \App\Services\IncomesService($year, $mm);
    $sIncomes->getUserRatesLst();
    $lst = $sIncomes->getTypeRatesLst();

    //----------------------------------//
    $family = \App\Models\TypesRate::subfamily();
    $familyTotal = [];
    foreach ($family as $k => $v)
      $familyTotal[$k] = $mm;
    $familyTotal['gral'] = $mm;
    $family['gral'] = 'Generales';
    //----------------------------------//
    foreach ($lst as $k => $item) {
      $lst[$k] = $sIncomes->processURates($k, $item);
    }
    //----------------------------------//
    //calcular totals
    $bonosTotal = [];
    foreach ($lst as $k => $v) {
      foreach ($v['lst'] as $k1 => $v1) {
        for ($i = 1; $i < 13; $i++) {
          $v[$i] += $v1[$i];
        }
      }
      foreach ($v['slst'] as $k1 => $v1) {
        $bKey = $k . $k1;
        $bonosTotal[$bKey] = $mm;
        foreach ($v1 as $k2 => $v2) {
          for ($i = 1; $i < 13; $i++) {
            if (isset($v2[$i])){
              $v[$i] += $v2[$i];
              $familyTotal[$k1][$i] += $v2[$i];
            }
          }
        }
      }
      $lst[$k] = $v;
    }
    //----------------------------------//
    //INGRESOS TOTAL ANUAL
    $byYears = $tByYears = [];
    for ($i = 2; $i >= 0; $i--) {
      $yAux = $year - $i;
      $byYears[$yAux] = $mm;
      $tByYears[$yAux] = 0;
      $oCharges = Charges::whereYear('date_payment', '=', $yAux)->get();
      foreach ($oCharges as $c) {
        $m = intval(substr($c->date_payment, 5, 2));
        $byYears[$yAux][$m] += $c->import;
        $tByYears[$yAux] += $c->import;
      }
    }
    //----------------------------------//
    $totals = $mm;
    foreach ($lst as $i) {
      foreach ($mm as $k => $v) {
        if (isset($i[$k])) {
          $totals[$k] += $i[$k];
        }
      }
    }

    //----------------------------------//
    return view('admin.contabilidad.incomes.index', [
        'year' => $year,
        'monts' => $monts,
        'lst' => $lst,
        'family' => $family,
        'familyTotal' => $familyTotal,
        'totals' => $totals,
        'byYears' => $byYears,
        'tByYears' => $tByYears,
    ]);
  }

  function byRate($rateID) {
    $year = getYearActive();
    $pMeth = payMethod();

    $oType = \App\Models\TypesRate::find($rateID);
    $servic = \App\Models\Rates::getByTypeRateID($rateID)
                    ->pluck('name', 'id')->toArray();

    $oCharges = null;
    if (count($servic) > 0) {
      $oCharges = Charges::whereYear('date_payment', '=', $year)
                      ->whereIn('id_rate', array_keys($servic))
                      ->orderBy('date_payment')->get();
    }


    include_once app_path() . '/Blocks/IncomesDetail.php';
  }

}
