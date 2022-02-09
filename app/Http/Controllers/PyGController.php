<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use DB;
use App\Models\Expenses;
use App\Models\Charges;
use App\Services\CoachLiqService;
use App\Models\User;

class PyGController extends Controller {

  public function index() {
    //---------------------------------------------------------//
    $year = getYearActive();
    $lstMonths = lstMonthsSpanish(false);
    unset($lstMonths[0]);
    $months_empty = array();
    for ($i = 0; $i < 13; $i++)  $months_empty[$i] = 0;

    
    //---------------------------------------------------------//
    $gastos = Expenses::whereYear('date', '=', $year)->get();
    $gType = Expenses::getTypes();
    $gTypeGroup = Expenses::getTypesGroup();
    $gTypeGroup_g = $gTypeGroup['groups'];
    $ggMonth = [];
    $crLst = [];
    foreach ($gTypeGroup_g as $k=>$v) $ggMonth[$v] = $months_empty;
    $ggMonth['otros'] = $months_empty;
    //---------------------------------------------------------//
    $oRateTypes = \App\Models\TypesRate::orderBy('name')->pluck('name','id')->toArray();
    $aRates = \App\Models\Rates::orderBy('name')->pluck('type','id')->toArray();
    foreach ($oRateTypes as $k=>$v) $crLst[$k] = $months_empty;
    //---------------------------------------------------------//
    $incomesYear = $expensesYear = [];
    $currentY = [];
    //---------------------------------------------------------//
    for ($i = 2; $i >= 0; $i--) {
      $yAux = $year - $i;
      $incomesYear[$yAux] = Charges::getSumYear($yAux);
//      $incomesYear[$yAux] = Charges::whereYear('date_payment', '=', $yAux)->sum('import');
    }
    //----------------------------------------------------------//
    $uRates = \App\Models\CustomersRates::where('charge_id', '>', 0)
              ->where('rate_year',$year)->get();
      
    $aux = $months_empty;
    $pay_method = ['c'=>$months_empty,'b'=>$months_empty,'v'=>$months_empty];
    foreach ($uRates as $item){
      $c = $item->charges;
      if (!$c)        continue;
      $m = $item->rate_month;
      switch ($c->type_payment){
        case 'cash':
          $pay_method['c'][$m] += $c->import;
          break;
        case 'card':
          $pay_method['v'][$m] += $c->import;
          break;
        case 'banco':
          $pay_method['b'][$m] += $c->import;
          break;
      }
      $rateGr = isset($aRates[$c->rate_id]) ? $aRates[$c->rate_id] : 3;
      $crLst[$rateGr][$m] += $c->import;
    }
    //--------------------------------------------------------------------//
    $oBonos = Charges::whereYear('date_payment', '=', $year)
              ->where('bono_id','>',0)->get();
    $oRateTypes['bono'] = 'BONOS SUELTOS';
    $crLst['bono'] = $months_empty;
    foreach ($oBonos as $c){
      $m = intval(substr($c->date_payment,5,7));
      $rateType = isset($lstBonos[$c->bono_id]) ? $lstBonos[$c->bono_id] : null;
      
      if (isset($crLst[$rateType])){
        $crLst[$rateType][$m] += $c->import;
      } else {
        $crLst['bono'][$m] += $c->import;
      }
      
      switch ($c->type_payment){
        case 'cash':
          $pay_method['c'][$m] += $c->import;
          break;
        case 'card':
          $pay_method['v'][$m] += $c->import;
          break;
        case 'banco':
          $pay_method['b'][$m] += $c->import;
          break;
      }
    }
    //--------------------------------------------------------------------//
    $aux = $months_empty;
    foreach ($crLst as $k=>$v){
      for ($i = 0; $i < 13; $i++){
        $aux[$i] += $v[$i];
      }
    }
    $aux[0] = array_sum($aux);
    $currentY['Ingresos'] = $aux;
    
    $tIncomes = 0;
    foreach ($crLst as $k=>$v){
        $t=0;
        foreach ($v as $k1=>$v1){
          if (is_numeric($k1)){
            $t += $v1;
          }
        }
        $crLst[$k][0] = $t;
        $tIncomes += $t;
    }


    /********************************************************** */
    for ($i = 2; $i > 0; $i--) {
      $yAux = $year - $i;
      $expensesYear[$yAux] = Expenses::whereYear('date', '=', $yAux)->sum('import');
    }
    
    $oExpenses = Expenses::whereYear('date', '=', $year)->get();
    $aux = $months_empty;
    $expensesYear[$year] = 0;
    if ($oExpenses) {
      foreach ($oExpenses as $e) {
        $m = intval(substr($e->date, 5, 2));
     
        $aux[$m] += $e->import;
        $aux[0] += $e->import;
        $expensesYear[$year] += $e->import;
        $g = isset($gTypeGroup_g[$e->type]) ? $gTypeGroup_g[$e->type] : 'otros';
        if (isset($ggMonth[$g])){
          $ggMonth[$g][$m] += $e->import;
        } else {
          $ggMonth['otros'][$m] += $e->import;
        }
      }
    }
    //---------------------------------------------------------//
    $ggMonth['pt'] = $months_empty;
    $gTypeGroup['names']['pt'] = 'SUELDOS Y SALARIOS';
    $CoachLiqService = new \App\Services\CoachLiqService();
    for($i=0;$i<3;$i++){
      $auxYear = $year-$i;
      $sCoachLiq = $CoachLiqService->liqByMonths($auxYear);
      
      foreach ($sCoachLiq['aLiq'] as $liq){
        foreach ($liq as $m=>$t){
          $expensesYear[$auxYear]  += $t;
          if ($i == 0){
            $ggMonth['pt'][$m] += $t;
            $aux[$m] += $t;
            $aux[0] += $t;
          }
        }
      }
    }
    //---------------------------------------------------------//
    $currentY['Gastos'] = $aux;
    
    /***************************************/
    $oUser = new User();
    $subscs = \App\Models\UsersSuscriptions::count();
    $uActivs = User::where('status',1)->count();
    /***************************************/
        
    $aux_i = $aux_e = $months_empty; 
    /***************************************/
    return view('admin.contabilidad.pyg.index',[
        'year'=>$year,
        'monts'=>$lstMonths,
        'currentY'=>$currentY,
        'incomesYear'=>$incomesYear,
        'expensesYear'=>$expensesYear,
        'subscs'=>$subscs,
        'uActivs'=>$uActivs,
        'ggMonth'=>$ggMonth,
        'ggNames'=>$gTypeGroup['names'],
        'oRateTypes'=>$oRateTypes,
        'crLst'=>$crLst,
        'aux_i'=>$aux_i,
        'aux_e'=>$aux_e,
        'tIncomes'=>$tIncomes,
        'pay_method'=>$pay_method,
  ]);
  }

}
