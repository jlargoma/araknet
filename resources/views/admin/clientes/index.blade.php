@extends('layouts.admin-master')

@section('title') Clientes - Araknet HTS @endsection

@section('externalScripts')
<link rel="stylesheet" href="{{ assetV('admin-css/assets/css/customer.css') }}">-->

@endsection

@section('headerButtoms')
<li class="text-center">
  <button id="newUser" class="btn btn-success btn-home">
    <i class="fa fa-plus"></i> Cliente
  </button>
</li>
@endsection

<?php 
$b_aux = ['btn-primary','btn-primary','btn-primary','all'=>'btn-primary'];
if (isset($b_aux[$status])) $b_aux[$status] = 'btn-success';
?>
@section('content')
<div class="row">
  <div class="col-md-6 col-lg-4">
    <div class="box-db blue-light">
      {{$aHntsDash['today']}}<hnt>HNT</hnt>
      <p>Hoy</p>
    </div>
    <div class="box-db blue-light">
      {{$aHntsDash['yest']}}<hnt>HNT</hnt>
      <p>Ayer</p>
    </div>
    <div class="box-db blue">
      {{$aHntsDash['week']}}<hnt>HNT</hnt>
      <p>7 días</p>
    </div>
    <div class="box-db purple">
      {{$aHntsDash['month']}}<hnt>HNT</hnt>
      <p>Mes</p>
    </div>
    <div class="box-db red-light">
      {{$aHntsDash['avg']}}<hnt>HNT</hnt>
      <p>Promedio por Hotspots</p>
    </div>
    <div class="box-db green">
      {{$aHntsDash['balance']}}<hnt>HNT</hnt>
      <p>Balance</p>
    </div>
  </div>
  <div class="col-md-6  col-lg-8">
    <canvas id="hnt_month" style="width:100%; height: 300px;"></canvas>
  </div>
</div>


<div class="content content-full bg-gray-lighter">
  <div class="row ">
    <div class="col-md-9 col-xs-12 mb-1em">
      <a href="{{url('/admin/clientes/'.$month)}}?status=all" class="inline">
        <button class="btn btn-md {{$b_aux['all']}}">
          Todos
        </button>
      </a>
      <a href="{{url('/admin/clientes/'.$month)}}?status=1" class="inline">
        <button class="btn btn-md {{$b_aux[1]}}">
          Activos
        </button>
      </a>
      <a href="{{url('/admin/clientes/'.$month)}}?status=0" class="inline">
        <button class="btn btn-md {{$b_aux[0]}}">
          Inactivos
        </button>
      </a>
      <a href="{{url('/admin/clientes-export')}}" class="inline">
        <button class="btn btn-md">
          EXPORT EXCEL
        </button>
      </a>
    </div> 
    <div class="col-xs-8 col-md-2 pull-right">
      @if ($noPay > 0)
      <button id="cuotas-pendientes" class="btn btn-danger right">
        Cuotas Pendientes {{ $noPay }} €
      </button>
      @endif
    </div>
    <div class="col-xs-4 col-md-1 pull-right">
      <select id="date" class="form-control">
        <?php
        foreach ($months as $k => $v):
          $selected = ($k == $month) ? "selected" : "";
          ?>
          <option value="<?php echo $k; ?>" <?php echo $selected ?>>
            <?php echo $v . ' ' . $year; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="row mt-1">
    <div class="loading text-center" style="padding: 150px 0;">
      <i class="fa fa-5x fa-circle-o-notch fa-spin"></i><br><span class="font-s36">CARGANDO</span>
    </div>
    <div class="col-md-12" id="containerTableResult" style="display: none;">
      @include('/admin/clientes/table')
    </div>
  </div>
</div>
@include('/admin/clientes/modals')
@endsection


@section('scripts')
  <script type="text/javascript">
    var dataTableClient = 1
  </script>
   
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" src="/admin-css/assets/js/plugins/chartJs/Chart.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>
<script src="{{ assetV('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>

@include('/admin/clientes/scripts')
@endsection