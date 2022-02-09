<?php

namespace App\Services;

use App\Models\Rates;
use App\Models\CustomersRates;
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

  function getCustomersRatesLst() {
    $crLst = [];
    $uRates = \App\Models\CustomersRates::where('rate_year', $this->year)->get();
    foreach ($uRates as $item) {
      $c = $item->charges;
      $rID = $item->rate_id;
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
          $item['slst'][$r->subfamily][$r->id] = $rData;
          $item['slst'][$r->subfamily][$r->id]['name'] = $r->name;
      } else {
          $item['lst'][$r->id] = $rData;
          $item['lst'][$r->id]['name'] = $r->name;
      }
    }
    return $item;
  }
}
