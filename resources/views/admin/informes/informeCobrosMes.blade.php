<?php

use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') INFORME DE COBROS - Evolutio HTS @endsection

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
          <h2 class="text-center">INFORME DE COBROS AL MES</h2>
        </div>
        <div class="col-xs-12 btn-months mx-1em">
          @foreach($months as $k=>$v)
          <a href="/admin/informes/cobros-mes/{{$k}}" class=" btn btn-success <?php echo ($month == $k) ? 'active' : '' ?>">
            {{$v.' '.$year}}
          </a>
          @endforeach
        </div>

        
        <div class="col-md-8 col-xs-12 push-20">
          <table class="table table-striped table-header-bg">
            <thead>
              <tr>
                <th class="text-left bg-complete font-w800">Cliente</th>
                <th class="text-center bg-complete font-w800">Coach</th>
                <th class="text-center bg-complete font-w800">Precio</th>
                <th class="text-center bg-complete font-w800">Tarifa</th>
              </tr>
            </thead>
            <tbody>
              <?php $total = 0; ?>
              @foreach($uResult as $cID=>$lst)
              <?php 
                $cname = isset($aCust[$cID]) ? $aCust[$cID] : '-';
              ?>
              @foreach($lst as $item)
              <?php 
                $coach = $item[1];
                $import = $item[3];
                $rType = $item[5];
              ?>
              <tr>
                <td class="text-left">{{$cname}}</td>
                <td class="text-center">
                   <?php echo isset($aCoachs[$coach]) ? $aCoachs[$coach] : '-'; ?>
                </td>
                <td class="text-center">
                   <?php echo isset($aRType[$rType]) ? $aRType[$rType] : '-'; ?>
                </td>
                <td class="text-center">
                  <?php 
                  if ($item[2] == 'bono'){
                    echo 'BONO';
                  } else {
                    $total += $import;
                    echo moneda($import); 
                  }
                  ?>
                </td>
              </tr>
              @endforeach
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th class="text-left bg-complete font-w800">Total</th>
                <th class="text-left bg-complete" colspan="2"></th>
                <th class="text-center bg-complete font-w800">{{moneda($total)}}</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="col-md-4 col-xs-12 push-20">
          <div class="table-responsive">
            <table class="table table-striped table-header-bg">
              <thead>
                <tr>
                  <th class="text-left bg-complete font-w800 static">Coach</th>
                  <th class="text-center bg-complete font-w800">Total</th>
                </tr>
              </thead>
              <tbody>
              <?php $total = 0; ?>
              @foreach($tCoachs as $cID=>$import)
              <?php 
                $cname = isset($aCoachs[$cID]) ? $aCoachs[$cID] : '-';
              ?>
              <tr>
                <td class="text-left">{{$cname}}</td>
                <td class="text-center">
                  <?php 
                    $total += $import;
                    echo moneda($import); 
                  ?>
                </td>
              </tr>
              @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th class="text-left bg-complete font-w800 static">Total</th>
                  <th class="text-center bg-complete font-w800">{{moneda($total)}}</th>
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