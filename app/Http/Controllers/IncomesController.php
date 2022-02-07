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
    foreach ($monts as $k=>$v) $mm[$k] = 0;
    
    $sIncomes = new \App\Services\IncomesService($year,$mm);
    $sIncomes->getUserRatesLst();
    $lst = $sIncomes->getTypeRatesLst();
    
    //----------------------------------//
    $family = \App\Models\TypesRate::subfamily();
    $familyTotal = [];
    foreach ($family as $k=>$v) $familyTotal[$k] = $mm;
    $familyTotal['gral'] = $mm;
    $family['gral'] = 'Generales';
    //----------------------------------//
    foreach ($lst as $k=>$item){
      $lst[$k] = $sIncomes->processURates($k,$item);
    }
    //----------------------------------//
    //BONOS
    $aBonos = \App\Models\Bonos::orderBy('name')->get();
    $lstBonos = $sIncomes->prepareBonos($aBonos);
    
    $auxB = $mm;
    $auxB['name'] = 'BONOS';
    $auxB['lst']  = [];
    $auxB['slst']  = [];
    $lst['bonos'] = $auxB;
    //-------------------------------------------
   
    //rate_type or rate_subf
    foreach ($aBonos as $k=>$v){
      $rateType = 'gral';
      $rate_subf = null;
      if (isset($lst[$v->rate_type])){
        $rateType = $v->rate_type;
      } else {
        if ($v->rate_subf){
          $rate_subf = $v->rate_subf;
          if(str_contains($v->rate_subf,'f'))
                  $rateType = 8;
          if(str_contains($v->rate_subf,'v'))
                  $rateType = 11;
        }
      }
      if ($rateType && isset($lst[$rateType])){
        if ($rate_subf && isset($lst[$rateType]['slst'][$rate_subf])){
          $lst[$rateType]['slst'][$rate_subf]['bonos'][$v->id] = $lstBonos[$v->id];
          $lst[$rateType]['slst'][$rate_subf]['bonos'][$v->id]['name'] = ' -- '.$v->name;
        } else {
          $lst[$rateType]['lst'][$v->id] = $lstBonos[$v->id];
          $lst[$rateType]['lst'][$v->id]['name'] = '*'.$v->name;
        }
      } else {
          //Es del item Bonos cuando no se le puede asignar a otro
        $lst['bonos']['lst'][$v->id] = $lstBonos[$v->id];
        $lst['bonos']['lst'][$v->id]['name'] = $v->name;
      }
    }
    
    //calcular totals
    $bonosTotal = [];
    foreach ($lst as $k=>$v){
      foreach ($v['lst'] as $k1=>$v1){
        for($i=1;$i<13; $i++){
          $v[$i] += $v1[$i];
        }
      }
      foreach ($v['slst'] as $k1=>$v1){
        $bKey = $k.$k1;
        $bonosTotal[$bKey] = $mm;
        foreach ($v1 as $k2=>$v2){
          if ($k2 == 'bonos'){
            foreach ($v2 as $k3=>$v3){
              for($i=1;$i<13; $i++){
                if ($v3[$i]>0){
                  $v[$i] += $v3[$i];
                  $bonosTotal[$bKey][$i]+= $v3[$i];
                  $familyTotal[$k1][$i] += $v3[$i];
                }
              }
            }
          } else {
            for($i=1;$i<13; $i++){
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
    for($i=2;$i>=0;$i--){
      $yAux = $year-$i;
      $byYears[$yAux] = $mm;
      $tByYears[$yAux] = 0;
      $oCharges = Charges::whereYear('date_payment','=',$yAux)->get();
      foreach ($oCharges as $c){
        $m = intval(substr($c->date_payment,5,2));
        $byYears[$yAux][$m] += $c->import;
        $tByYears[$yAux] += $c->import;
      }
    }
    //----------------------------------//
    $totals = $mm;
    foreach ($lst as $i){
      foreach ($mm as $k=>$v){
        if (isset($i[$k])){
          $totals[$k] += $i[$k];
        }
      }
    }
    //----------------------------------//
    return view('admin.contabilidad.incomes.index',[
        'year'=>$year,
        'monts'=>$monts,
        'lst'=>$lst,
        'family'=>$family,
        'familyTotal'=>$familyTotal,
        'bTotal'=>$bonosTotal,
        'totals'=>$totals,
        'byYears'=>$byYears,
        'tByYears'=>$tByYears,
    ]);
  }

  function byRate($rateID){
    $year = getYearActive();
    $pMeth = payMethod();
    
    if ($rateID == 'bono'){
      $oBonoCharges = Charges::whereYear('date_payment', '=', $year)
              ->where('bono_id','>',0)->get();
      $aBonos = \App\Models\Bonos::pluck('name','id')->toArray();
      
      include_once app_path().'/Blocks/BonosDetail.php';
      return '';
    }
    
    $oType = \App\Models\TypesRate::find($rateID);
    $servic = \App\Models\Rates::getByTypeRateID($rateID)
            ->pluck('name', 'id')->toArray();
    
    $oCharges = null;
    if (count($servic)>0){
      $oCharges = Charges::whereYear('date_payment', '=', $year)
            ->whereIn('id_rate', array_keys($servic))
            ->orderBy('date_payment')->get();
    }
     
    
    include_once app_path().'/Blocks/IncomesDetail.php';

  }
}
