@extends('layouts.admin-master')
@section('title') Gastos- EVOLUTIO  @endsection

@section('content')
@include('admin.contabilidad._button-contabiliad')
<h2>Gastos <button type="button" class="btn btn-success" id="addNew_ingr" type="button" data-toggle="modal" data-target="#modalAddNew"><i class="fa fa-plus-circle"></i> Añadir</button></h2>
  <div class="col-lg-12 col-md-12 col-xs-12">
  @include('admin.contabilidad.expenses.resume-by-month')
  </div>
  <div class="row">
    <div class="col-lg-4 col-sm-6 col-xs-12">
       <canvas id="chart_1" style="width: 100%; height: 250px;"></canvas>
    </div>
    <div class="col-lg-4 col-sm-6 col-xs-12">
      <canvas id="chartTotalByYear" style="width: 100%; height: 250px;"></canvas>
    </div>
    <div class="col-lg-4 col-sm-6 col-xs-12">
      <canvas id="chartTotalByMonth" style="width: 100%; height: 250px;"></canvas>
    </div>
  </div>

  <br/> <br/> <br/>

  <div class="col-md-8 col-xs-12">
    <div class="mbl-tabs">
    <ul class="selectDate">
      @foreach($lstMonths as $k=>$v)
      <li data-val="{{$k}}" class="<?php echo ($current == $k) ? 'active' : '' ?>">
        <?php echo ($k == 0) ? 'Todos' : $v; ?>
      </li>
      @endforeach
    </ul>
  </div>
  </div>
  <div class="col-md-2 col-xs-6">
    <h3>Total Selec. <span id="totalMounth">0</span></h3>
  </div>
  <div class="col-md-2 col-xs-6">
    <h3>Total Año {{moneda($total_year_amount)}}
  </div>

  <div class="col-md-12 col-xs-12" style="padding-right: 0; min-height: 0.43em;">
    <input type="hidden" id="year" value="">
    <input type="hidden" id="month" value="">
    <div class="table-responsive">
      <table class="table">
        <thead >
        <th class="text-center bg-complete text-white col-md-1">Fecha</th>
        <th class="text-center bg-complete text-white col-md-2">Concepto</th>
        <th class="text-center bg-complete text-white col-md-2">
          <select id="s_type">
            <option value="-1">Tipo</option>
            @foreach($gType as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </th>
        <th class="text-center bg-complete text-white col-md-1">
          <select id="s_payment">
            <option value="-1">Método de pago</option>
            @foreach($typePayment as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </th>
        <th class="text-center bg-complete text-white col-md-2">Importe</th>
        <th class="text-center bg-complete text-white">#</th>
        <th class="text-center bg-complete text-white col-md-2">Comentario</th>
        <th class="text-center bg-complete text-white col-md-2">
          <select id="s_usr">
            <option value="-1">Usuario</option>
            @foreach($lstUsr as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </th>
        </thead>
        <tbody id="tableItems" class="text-center">
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal fade" id="modalAddNew" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <strong class="modal-title" id="modalChangeBookTit" style="font-size: 1.4em;">Añadir Gasto</strong>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">@include('admin.contabilidad.expenses._form')</div>
      </div>
    </div>
  </div>

  <div class="col-md-12 col-xs-12" style="min-height:43em;"> 
    <div class="clearfix">
      <textarea id="importExcel" rows="1" placeholder="Pegar los registros del Excel"></textarea>
      <div class="btnImportExcel">Importar</div>
      <form method="post" action="/admin/gastos/importar">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <table class="table" id="excel_table"></table>
        <button class="btn btn-success btnSendImportExcel" >Enviar</button>
      </form>
    </div>
  </div>

  @endsection

  <?php
  $bColor_1 = array();
  $labels_1 = array();
  $values_1 = array();
  $ij = 0;
  if (count($listGasto_g) > 0) {
    foreach ($listGasto_g as $k => $v) {
      $bColor_1[] = '"'.printColor($ij).'"';
      $labels_1[] = "'$gTypeGroup[$k]'";
      $values_1[] = $v[0];
      $ij++;
    }
  }
  
  
    $tYearMonths = '';
    $aux = '';
    $ij = 0;
     foreach ($yearMonths as $Y=>$monthData){
      $aux .= '{
              data: [';
      foreach ($monthData as $k=>$v){
        if ($k>0) $aux .= "'" . round($v) . "',";
      }
      
      $aux .= '],
              label: "Año '.$Y.'",
              borderColor: "'.printColor($ij).'",
              fill: false
            },';
      $ij++;
    }
    $tYearMonths = $aux;
    unset($lstMonths[0]);
  ?>
  @section('scripts')
  
  
<link rel="stylesheet" href="{{ assetV('css/contabilidad.css') }}">
  <script type="text/javascript" src="/admin-css/assets/js/import-excel.js"></script>
  <script type="text/javascript" src="/admin-css/assets/js/plugins/chartJs/Chart.min.js"></script>
  <script type="text/javascript" src="/admin-css/assets/js/charts.js"></script>
  <script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/moment.min.js')}}"></script>
  @include('admin.contabilidad.expenses.scripts')
  
  <style>

    .pieChart{
      max-width: 270px;
      margin: 1em auto;
    }
    button.del_expense.btn.btn-danger.btn-xs {
      margin: 3px 14px;
    }

    .table-responsive>.table>tbody#tableItems>tr>td{
      white-space: normal;
      border-left: solid 1px #cacaca;
      padding: 8px !important;
    }
    .table-responsive>.table>tbody#tableItems>tr.selected {
      color: #000;
    }
    .table-responsive th select{
      padding: 6px 11px;
      background: transparent;
      width: 80%;
      font-weight: 800;
      letter-spacing: 1.7px;
      border-color: #fff;
    }
    .table-responsive th select option {
      color: #008ff7;
    }
    table.table-resumen tr th.static{
      background-color: #5c90d2;
    }
    table.table-resumen tr .static-2{
      left: 130px;
    }
  </style>
   
  @endsection
