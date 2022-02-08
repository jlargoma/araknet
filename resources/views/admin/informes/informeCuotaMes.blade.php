<?php

use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') INFORME DE CUOTAS PAGADAS - Evolutio HTS @endsection

@section('externalScripts')
<style>
  .bg-complete {
    color: #fff !important;
    background-color: #5c90d2 !important;
    border-bottom-color: #5c90d2 !important;
    font-weight: 800;
    vertical-align: middle !important;
  }
  .btn-months{
    text-align: center;
  }
  .btn-months a{

  }
</style>
@endsection
@section('content')
<div class="content content-boxed bg-gray-lighter">
  <div class="row ">
    <div class="col-xs-12 push-20">
      <div class="row">
        <div class="col-md-12 col-xs-12 push-20">
          <h2 class="text-center">INFORME DE CUOTAS PAGADAS AL MES</h2>
        </div>
        <div class="col-xs-12 btn-months mx-1em">
          @foreach($months as $k=>$v)
          <a href="/admin/informes/cuotas-mes/{{$k}}" class=" btn btn-success <?php echo ($month == $k) ? 'active' : '' ?>">
            {{$v.' '.$year}}
          </a>
          @endforeach
        </div>

        <div class="col-md-6 col-xs-12 push-20">
          <table class="table table-striped table-header-bg">
            <thead>
              <tr>
                <th class="text-left bg-complete font-w800">Tarifa</th>
                <th class="text-center bg-complete font-w800">Total</th>
              </tr>
            </thead>
            <tbody>
<?php $total = 0; ?>
              @foreach($byRate as $rate=>$import)
              <tr>
                <td class="text-left">
                  <?php echo (isset($aRates[$rate])) ? $aRates[$rate] : ' - '; ?>
                </td>
                <td class="text-center">
<?php $total += $import;
echo moneda($import); ?>
                </td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th class="text-left bg-complete font-w800">Total</th>
                <th class="text-center bg-complete font-w800">{{moneda($total)}}</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="col-md-6 col-xs-12 push-20">
          <div class="table-responsive">
            <table class="table table-striped table-header-bg">
              <thead>
                <tr>
                  <th class="text-left bg-complete font-w800 static">Familia</th>
                  <th class="first-col bg-complete"></th>
                  <th class="text-center bg-complete font-w800">Total</th>
                  <th class="text-center bg-complete font-w800">Banco</th>
                  <th class="text-center bg-complete font-w800">Visa</th>
                  <th class="text-center bg-complete font-w800">Cash</th>
                </tr>
              </thead>
              <tbody>
                    <?php $total = $tbanco = $tcard = $tcash = 0; ?>
                @foreach($byTypeRate as $rt=>$import)
                <tr>
                  <td class="text-left static">
                    <?php echo (isset($aRType[$rt])) ? $aRType[$rt] : ' - '; ?>
                  </td>
                  <td class="first-col"></td>
                  <td class="text-center">
                    <?php $total += $import['t'];
                    echo moneda($import['t']); ?>
                  </td>
                  <td class="text-center">
<?php $tbanco += $import['banco'];
echo moneda($import['banco']); ?>
                  </td>
                  <td class="text-center">
<?php $tcard += $import['card'];
echo moneda($import['card']); ?>
                  </td>
                  <td class="text-center">
<?php $tcash += $import['cash'];
echo moneda($import['cash']); ?>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th class="text-left bg-complete font-w800 static">Total</th>
                  <th class="first-col bg-complete"></th>
                  <th class="text-center bg-complete font-w800">{{moneda($total)}}</th>
                  <th class="text-center bg-complete font-w800">{{moneda($tbanco)}}</th>
                  <th class="text-center bg-complete font-w800">{{moneda($tcard)}}</th>
                  <th class="text-center bg-complete font-w800">{{moneda($tcash)}}</th>
                </tr>
              </tfoot>
            </table>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  $('#date, #month, #day').change(function (event) {

    var year = $('#date').val();
    var month = $('#month').val();
    var day = $('#day').val();
    window.location = '/admin/informes/cuotas-mes/' + month;
  });

  $('#searchInform').keydown(function (evt) {
    setTimeout(function () {
      var search = $('#searchInform').val();
      var token = $('#_token').val();
      var month = $('#month').val();
      $.post('/admin/informes/search/' + month, {search: search, _token: token}).done(function
              (data) {
        $('#content-table-inform').empty().append(data);
      });
    }, 50);
  });

</script>
<style>
  .table-striped tr:hover td{
    background-color: #dedede;
  }
</style>
@endsection