<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\UserRates;
use App\Models\User;
use App\Models\Charges;

class IncomesService {

  private $year;
  private $mm;
  private $crLst;

  public function __construct($year, $mm) {
    $this->year = $year;
    $this->mm = $mm;
    $this->crLst = [];
  }

  function getUserRatesLst() {
    $crLst = [];
    $uRates = \App\Models\UserRates::where('rate_year', $this->year)->get();
    foreach ($uRates as $item) {
      $c = $item->charges;
      $rID = $item->id_rate;
      if (!isset($crLst[$rID]))
          $crLst[$rID] = $this->mm;
      $m = $item->rate_month;
      if ($c){
        $crLst[$rID][$m] += $c->import;
      } else {
        $crLst[$rID][$m] += $item->price;
      }
    }
    $this->crLst = $crLst;
  }
  function getTypeRatesLst() {
    $oRateTypes = \App\Models\TypesRate::orderBy('name')->get();
    $lst = [];
    foreach ($oRateTypes as $t) {
      $lst[$t->id] = $this->mm;
      $lst[$t->id]['name'] = $t->name;
      $lst[$t->id]['slst'] = [];
      $lst[$t->id]['lst'] = [];
      $lst[$t->id]['blst'] = [];
    }
    return $lst;
  }

  function processURates($rType, $item) {
    $oRates = Rates::where('type', $rType)
                    ->orderBy('subfamily')->orderBy('name')->get();

    foreach ($oRates as $r) {
      $rData = isset($this->crLst[$r->id]) ? $this->crLst[$r->id] : $rData = $this->mm;
      $rData['name'] = '';
      if ($r->subfamily) {

        if (!isset($item['slst'][$r->subfamily]))
          $item['slst'][$r->subfamily] = [];
        if (str_contains(strtolower($r->name), 'bono')) {
          $item['slst'][$r->subfamily]['bonos'][$r->id] = $rData;
          $item['slst'][$r->subfamily]['bonos'][$r->id]['name'] = ' -- '.$r->name;
        } else {
          $item['slst'][$r->subfamily][$r->id] = $rData;
          $item['slst'][$r->subfamily][$r->id]['name'] = $r->name;
        }
      } else {
        if (str_contains(strtolower($r->name), 'bono')) {
          $item['blst'][$r->id] = $rData;
          $item['blst'][$r->id]['name'] = $r->name;
        } else {
          $item['lst'][$r->id] = $rData;
          $item['lst'][$r->id]['name'] = $r->name;
        }
      }
    }
    return $item;
  }

  function prepareBonos($aBonos){
    
    $aux_lst  = [];
    foreach ($aBonos as $b){
      $aux_lst[$b->id] = $this->mm;
      $aux_lst[$b->id]['name'] = $b->name;
    }
    //-------------------------------------------
    $oBonos = Charges::whereYear('date_payment', '=', $this->year)
              ->where('bono_id','>',0)->get();
    
    foreach ($oBonos as $item){
      $m = intval(substr($item->date_payment,5,7));
      if (!isset($aux_lst[$item->bono_id][$m])) $aux_lst[$item->bono_id][$m] = 0;
      $aux_lst[$item->bono_id][$m] += $item->import;
    }
    
    return $aux_lst;
  }
}
