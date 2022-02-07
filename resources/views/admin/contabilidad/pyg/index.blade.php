@extends('layouts.admin-master')
@section('title') P&G- EVOLUTIO  @endsection
<?php 
function sumMonthValue($m){
  $t=0;
  foreach ($m as $k=>$v){
    if (is_numeric($k)){
      $t += $v;
    }
  }
  return ($t);
}?>
@section('content')
@include('admin.contabilidad._button-contabiliad')
<div class="content">
  <h2>Perdidas & Ganancias</h2>

  <div class="row">

    <div class="col-lg-4 col-sm-6 col-xs-12">
      <canvas id="chart_incomesYear" style="width: 100%; height: 250px;"></canvas>
    </div>
    <div class="col-lg-4 col-sm-6 col-xs-12">
      <canvas id="chart_expensesYear" style="width: 100%; height: 250px;"></canvas>
    </div>
    <div class="col-lg-4 col-sm-6 col-xs-12">
      <canvas id="chartTotalByMonth" style="width: 100%; height: 250px;"></canvas>
    </div>
  </div>
    <div class="row">
    <div class="col-md-4 col-xs-6">
      <div class="box"><h2>Ingresos {{$year}}</h2><span>{{moneda($incomesYear[$year])}}</span></div>
    </div>
    <div class="col-md-4 col-xs-6">
      <div class="box"><h2>Gastos {{$year}}</h2><span>{{moneda($expensesYear[$year])}}</span></div>
    </div>
    <div class="col-md-4 col-xs-12">
      <?php $result = $incomesYear[$year]-$expensesYear[$year]; ?>
      <div class="box">
        <h2>Resultado {{$year}}</h2>
        <span>{{moneda($result)}}
          @if($result<0 )
          <i class="fa fa-arrow-down text-danger"></i>
          @else
          <i class="fa fa-arrow-up text-success"></i>
          @endif
        </span></div>
    </div>
    <div class="col-lg-3 col-md-6 col-xs-12 col-smal">
      <div class="box">
        <h2>Suscripciones activas</h2><span>{{$subscs}}</span>
        <table class="table tFidelity">
          <tr>
            <th colspan="2">Fidelity</th>
            <th colspan="2">Plan Básico</th>
          </tr>
          <?php 
            $subscsFidelityPerc = ($subscs>0) ? round($subscsFidelity/$subscs*100):0;
          ?>
          <tr>
          <td> {{$subscsFidelity}}</td>
          <td class="text-danger">{{$subscsFidelityPerc}}%</td>
          <td> {{$subscs-$subscsFidelity}}</td>
          <td class="text-danger"> {{100-$subscsFidelityPerc}}%</td>
          </tr>
        </table>
      </div>
      <div class="box">
        <h2>Clientes activos</h2><span>{{$uActivs}}</span>
        <table class="table tFidelity">
          <tr>
            <th colspan="2">Fidelity</th>
            <th colspan="2">Plan Básico</th>
          </tr>
          <?php 
            $uActivsFidelityPerc = ($uActivs>0) ? round($uActivsFidelity/$uActivs*100):0;
          ?>
          <tr>
          <td> {{$uActivsFidelity}}</td>
          <td class="text-danger">{{$uActivsFidelityPerc}}%</td>
          <td> {{$uActivs-$uActivsFidelity}}</td>
          <td class="text-danger"> {{100-$uActivsFidelityPerc}}%</td>
          </tr>
        </table>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-xs-12 chart_1">
      <canvas id="chart_1" width="50" height="50"></canvas>
    </div>
    <div class="col-lg-4 col-sm-6 col-xs-12 chart_2">
      <canvas id="myChart" width="400" height="200"></canvas>
    </div>
    <div class="col-lg-2 col-sm-6 col-xs-12">
      <table class="table">
        <tr>
          <th>Total</th>
          <th>{{moneda($pay_method['c'][0]+$pay_method['b'][0]+$pay_method['v'][0])}}</th>
        </tr>
        <tr>
          <td class="square" ><span style="background-color: rgba(52,162,99,1);"></span>METALICO</td>
          <td>{{moneda($pay_method['c'][0])}}</td>
        </tr>
        <tr>
          <td class="square" ><span style="background-color: #5c90d2;"></span>BANCO</td>
          <td>{{moneda($pay_method['b'][0])}}</td>
        </tr>
        <tr>
          <td class="square" ><span style="background-color: #d25c93;"></span>VISA</td>
          <td>{{moneda($pay_method['v'][0])}}</td>
        </tr>
      </table>
    </div>
    <div class="col-xs-12">
@include('admin.contabilidad.pyg.table')
@include('admin.contabilidad.pyg.table2')
    </div>
  </div>
</div>

<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
        </div>
        <div class="row block-content" id="contentModalInfo">

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
  @include('admin.contabilidad.pyg.scripts')

<style>
  .box {
    border: 2px solid #5c90d2;
    margin: 1em;
    padding: 1em;
    text-align: center;
    border-radius: 29px;
}
.box span {
    font-size: 31px;
}
tr.subtotal td {
    background-color: #d6d6d6;
    font-weight: 700;
}
tfoot tr td {
    background-color: #5c90d2;
    font-weight: bold;
    color: #FFF;
    font-size: 1.3em;
}
.square{
  text-align: left;
}
.square span{
  height: 1em;
  width: 1em;
  display: inline-block;
  margin-right: 4px;
  margin-bottom: -2px;
}
.tFidelity {
  font-size: 21px;
  font-weight: 600;
}
.tFidelity th {
    background-color: #cfe4f3;
    color: #000;
    text-align: center;
}
@media(min-width:430px) {
  .chart_1{
    margin-top: -55px;
  }
}
@media(max-width:480px) {
  .chart_1{width: 100%;}
  .col-smal{width: 100%;}
  .col-smal .box{width: 94%; float: left;margin: 2%;}
  .box {
    padding: 1em 0px;
    margin: 1em auto;
    min-height: 9em;
  }
  .box h2{
    font-size: 14px;
  }
}
</style>
@endsection
