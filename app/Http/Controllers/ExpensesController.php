<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Carbon\Carbon;
use DB;
use App\Models\Expenses;
use App\Models\CoachLiquidation;
use App\Models\User;
use App\Services\CoachLiqService;

class ExpensesController extends Controller {

  public function index() {
    die('en construcción');
  }

  public function gastosBy_month($month) {
    $gastos = Expenses::where('date', '=', $year)->get();
    $gType = Expenses::getTypes();
    if ($gastos) {
      foreach ($gastos as $g) {
        
      }
    }
  }

  public function gastosDel(Request $request) {
    $id = $request->input('id');
    Expenses::where('id', $id)->delete();
    return 'ok';
  }

  public function gastos($current = null) {

    $year = getYearActive();
    $lstMonths = lstMonthsSpanish(false);
    $months_empty = array();
    for ($i = 0; $i < 13; $i++)
      $months_empty[$i] = 0;

    $yearMonths = [
        $year - 2 => $months_empty,
        $year - 1 => $months_empty,
        $year => $months_empty,
    ];

    $gastos = Expenses::whereYear('date', '=', $year)->get();
    $gType = Expenses::getTypes();
    $gTypeGroup = Expenses::getTypesGroup();
    $gTypeGroup_g = $gTypeGroup['groups'];

    $listGastos = array();
    if ($gType) {
      foreach ($gType as $k => $v) {
        $listGastos[$k] = $months_empty;
      }
    }
    $listGastos_g = array();
    if ($gTypeGroup_g) {
      foreach ($gTypeGroup_g as $k => $v) {
        $listGastos_g[$v] = $months_empty;
      }
      $listGastos_g['sueldos_y_salarios'] = $months_empty;
      $listGastos_g['otros'] = $months_empty;
    }
    $totalYearAmount = 0;
    if ($gastos) {
      foreach ($gastos as $g) {
        $month = date('n', strtotime($g->date));
        $totalYearAmount += $g->import;
        $yearMonths[$year][$month] += $g->import;

        $gTipe = isset($gTypeGroup_g[$g->type]) ? $gTypeGroup_g[$g->type] : 'otros';

        if (isset($listGastos_g[$gTipe])) {
          $listGastos_g[$gTipe][$month] += $g->import;
          $listGastos_g[$gTipe][0] += $g->import;
        }

        if (isset($listGastos[$g->type])) {
          $listGastos[$g->type][$month] += $g->import;
          $listGastos[$g->type][0] += $g->import;
        }
      }
    }
    $auxYear = ($year) - 2;
    $gastos = Expenses::whereYear('date', '=', $auxYear)->get();
    if ($gastos) {
      foreach ($gastos as $g) {
        $month = date('n', strtotime($g->date));
        $yearMonths[$auxYear][$month] += $g->import;
      }
    }
    $auxYear = ($year) - 1;
    $gastos = Expenses::whereYear('date', '=', $auxYear)->get();
    if ($gastos) {
      foreach ($gastos as $g) {
        $month = date('n', strtotime($g->date));
        $yearMonths[$auxYear][$month] += $g->import;
      }
    }


    //---------------------------------------------------------//
    $sCoachLiq = new \App\Services\CoachLiqService();
    for($i=0;$i<3;$i++){
      $auxYear = $year-$i;
      $aCoachLiq = $sCoachLiq->liqByMonths($auxYear);
      foreach ($aCoachLiq['aLiq'] as $liq){
        foreach ($liq as $month=>$t){
          $yearMonths[$auxYear][$month] += $t;
          if ($i == 0){
            $listGastos_g['sueldos_y_salarios'][$month] += $t;
            $listGastos_g['sueldos_y_salarios'][0] += $t;
          }
        }
      }
    }
    //---------------------------------------------------------//
    //First chart PVP by months
    $dataChartMonths = [];
    foreach ($lstMonths as $k => $v) {
      $val = isset($listGastos[$k]) ? $listGastos[$k] : 0;
      $dataChartMonths[getMonthSpanish($k)] = $val;
    }

    $totalYear = [];
    foreach ($yearMonths as $k=>$v){
      $totalYear[$k] = array_sum($v);
    }
    if (!$current) {
      $current = date('m');
    }

    return view('admin.contabilidad.expenses.index', [
        'year' => $year,
        'lstMonths' => $lstMonths,
        'dataChartMonths' => $dataChartMonths,
        'gType' => $gType,
        'gastos' => $listGastos,
        'gTypeGroup' => $gTypeGroup['names'],
        'listGasto_g' => $listGastos_g,
        'current' => $current,
        'totalYear' => $totalYear,
        'total_year_amount' => $totalYearAmount,
        'yearMonths' => $yearMonths,
        'tYear' => $yearMonths[$year],
        'typePayment' => Expenses::getTypeCobro(),
        'oCoachs' => User::getCoachs(),
        'lstUsr'  => User::getCoachs()->pluck('name','id')->toArray()
    ]);
  }

  public function create(Request $request) {

    $messages = [
        'concept.required' => 'El Concepto es requerido.',
        'import.required' => 'El Importe es requerido.',
        'fecha.required' => 'La Fecha es requerida.',
        'concept.min' => 'El Concepto debe tener un mínimo de :min caracteres.',
        'import.min' => 'El Importe debe ser mayor de :min.',
        'import.max' => 'El Importe no debe ser mayor de :max.',
    ];

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'concept' => 'required|min:3',
                'import' => 'required|min:1|max:3000',
                'fecha' => 'required',
                    ], $messages);

    if ($validator->fails()) {
      return $validator->errors()->first();
    }
    $comment = $request->input('comment','');
    $gasto = new Expenses();
    $gasto->concept = $request->input('concept');
    $gasto->date = Carbon::createFromFormat('d-m-Y', $request->input('fecha'))->format('Y-m-d');
    $gasto->import = $request->input('import');
    $gasto->typePayment = $request->input('type_payment');
    $gasto->type = $request->input('type');
    $gasto->to_user = $request->input('to_user');
    $gasto->comment = $comment ? $comment : '';
    if ($gasto->save()) {
      return 'ok';
    }

    return 'error';
  }

  public function updateGasto(Request $request) {

    $id = $request->input('id');
    $type = $request->input('type');
    $val = $request->input('val');
    $gasto = Expenses::find($id);
    if ($gasto) {
      $save = false;
      switch ($type) {
        case 'price':
          $gasto->import = $val;
          $save = true;
          break;
        case 'comm':
          $gasto->comment = ($val) ? $val : '';
          $save = true;
          break;
        case 'concept':
          $gasto->concept = $val;
          $save = true;
          break;
        case 'type':
          $gasto->type = $val;
          $save = true;
          break;
        case 'payment':
          $gasto->typePayment = $val;
          $save = true;
          break;
        case 'user':
          $gasto->to_user = $val;
          $save = true;
          break;
      }
      if ($save) {
        if ($gasto->save()) {
          return "ok";
        }
      }
    }

    return 'error';
  }

  /**
   * Get the Gastos by month-years to ajax table
   * 
   * @param Request $request
   * @return Json-Objet
   */
  public function getTableGastos(Request $request, $isAjax = true) {

    $year = getYearActive();;
    $month = $request->input('month', null);
    if (!$year) {
      return response()->json(['status' => 'wrong']);
    }

    $qry = Expenses::whereYear('date', '=', $year);
    if ($month && $month > 0)
      $qry->whereMonth('date', '=', $month);

    $gastos = $qry->orderBy('date')->get();
    $gType = Expenses::getTypes();
    $response = [
        'status' => 'false',
        'respo_list' => [],
    ];
    $totalMounth = 0;
    $typePayment = Expenses::getTypeCobro();
    
    $lstUsr = User::getCoachs()->pluck('name','id')->toArray();
    if ($gastos) {
      $respo_list = array();
      foreach ($gastos as $item) {
        $respo_list[] = [
            'id' => $item->id,
            'concept' => $item->concept,
            'date' => convertDateToShow_text($item->date),
            'typePayment' => isset($typePayment[$item->typePayment]) ? $typePayment[$item->typePayment] : '--',
            'typePayment_v' => $item->typePayment,
            'type' => isset($gType[$item->type]) ? $gType[$item->type] : '--',
            'type_v' => $item->type,
            'comment' => $item->comment,
            'import' => $item->import,
            'to_user' => $item->to_user,
            'usr' => isset($lstUsr[$item->to_user]) ? $lstUsr[$item->to_user] : '--',
        ];
        $totalMounth += $item->import;
      }

      $response = [
          'status' => 'true',
          'respo_list' => $respo_list,
          'totalMounth' => moneda($totalMounth),
      ];
    }

    if ($isAjax) {
      return response()->json($response);
    } else {
      return $response;
    }
  }
  
  
  
  function byType($type){
    $year = getYearActive();
    if ($type != 'pt'){
      $gTypeGroup = Expenses::getTypesGroup();
      $aTypeLst = Expenses::getTypes();
      if (!isset($gTypeGroup['names'][$type])){
        echo  '<p class="alert alert-warning">Sin Registros</p>';
        return '';
      }
      $gTypesNames = $gTypeGroup['names'];
      $gTypeGroup = $gTypeGroup['groups'];
      $auxTypes = [];
      foreach ($gTypeGroup as $k=>$v){
        if ($v == $type) $auxTypes[] = $k;
      }
      $payType = Expenses::getTypeCobro();

      $items = Expenses::whereYear('date', '=', $year)
              ->whereIn('type',$auxTypes)->orderBy('date')->get();
      if (count($items)== 0){
        echo  '<p class="alert alert-warning">Sin Registros</p>';
        return '';
      }
//      dd($gTypeGroup);
      include_once app_path().'/Blocks/PyG_Expenses.php';
    } else {
      $sCoachLiq = new \App\Services\CoachLiqService();
      $data = $sCoachLiq->liqByCoachMonths($year);
      include_once app_path().'/Blocks/PyG_Coachs.php';
    }
  }

  /**
   * 
   * @param Request $request
   * @return type
   */
  function gastos_import(Request $request){
    $data = $request->all();
   
    $campos = [
      'date' =>-1,  
      'concept' =>-1,  
      'type' =>-1, 
      'import' =>-1,  
      'typePayment' =>-1, 
      'comment' =>-1,
      'filter' =>-1,
    ];
    
    foreach ($data as $k=>$v){
      if ($v != '' && !is_array($v)){
        preg_match('/column_([0-9]*)/', $k, $colID);
        if (isset($colID[1]) && isset($campos[$v])){
          $campos[$v] = $colID[1];
        }
      } 
    }
          
    $info = [];
    foreach ($campos as $k=>$v){
      if (isset($data['cell_'.$v])){
        $info[$k] = $data['cell_'.$v];
      }
    }
    if (count($info) == 0) return back();
    
    /********   FILTRAR REGISTROS   *********************/
    if (isset($info['filter'])){
      foreach ($info['filter'] as $k=>$v){
        if ($v == 1){
          foreach ($campos as $k2=>$v2){
            $info[$k2][$k]=null;
          }
          
        }
      }
    }
    /***************************************************/
    $expensesType = Expenses::getTypes();
    /***************************************************/
    
    $campos = [
      'date' =>'Fecha',  
      'concept' =>'Concepto',  
      'type' =>'Tipo de Gasto', 
      'import' =>'Precio',  
      'typePayment' =>'Metodo de Pago', 
      'comment' =>'Comentario',
    ];
    
    $today = date('Y-m-d');
    $insert = [];
    $newEmpty = [
          'concept'=>null,'date'=>null,'import'=>null,'typePayment'=>null,
          'type'=>null,'comment'=>''
        ];
    
    
    $total = count(current($info));
    for($i = 0; $i<$total; $i++){
      $new = $newEmpty;
      foreach ($campos as $k=>$v){
        
        $value = '';
        if (!isset($info[$k])){ continue;}
        if (!isset($info[$k][$i])){  continue;}
        if (!($info[$k][$i])){  continue;}
        $variab = $info[$k][$i];
        
        switch ($k){
          case 'date':
            $new['date'] =  ($variab != '') ? convertDateToDB($variab) : $today;
            break;
          case 'import':
            $variab = floatval(str_replace(',','.',str_replace('.','', $variab)));
            $new['import'] = $variab;
            break;
          case 'typePayment':
            $aux = strtolower($variab);
            $idType = 0;
            if ($aux == 'banco'){ $idType = 3; }
            if ($aux == 'cash') { $idType = 2; }
            $new['typePayment'] = $idType;
            break;
          case 'type':
            $type = array_search($variab,$expensesType);
            $new['type'] = $type;
            break;
          default:
            $new[$k] = $variab;
            break;
        }
      }
      $hasVal = false;
      foreach ($new as $value){
        if ($value) $hasVal = true;
      }
      if ($hasVal)  $insert[] = $new;
    }
//    dd($insert);
    $countInsert = count($insert);
    if ($countInsert>0)
      Expenses::insert($insert);
    
    return back()->with(['success'=>$countInsert . ' Registros inportados']);
   
   
  }
  
}
