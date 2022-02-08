<?php

use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') INFORME DE CUOTAS PAGADAS - Araknet HTS @endsection

@section('externalScripts')
<style>
  .bg-complete {
    color: #fff !important;
    background-color: #5c90d2 !important;
    border-bottom-color: #5c90d2 !important;
    font-weight: 800;
    vertical-align: middle !important;
  }
  option.b {
    font-weight: bold;
  }
</style>
@endsection
@section('content')
<div class="content content-boxed bg-gray-lighter">
  <h2 class="text-center">INFORME DE CUOTAS PAGADAS AL MES</h2>
  <div class="text-center">Listado de cobros realizados por fecha</div>
  <input type="text" id="searchInform" class="form-control" placeholder="Buscar"/>
  <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
  <div class="row mt-1">
      <div class="col-md-2 col-xs-3">
        <label>Mes</label>
        <select id="month" class="form-control">
          <?php
          foreach ($months as $k => $v):
            $s = ($k == $month) ? 'selected' : '';
            echo '<option value="' . $k . '" ' . $s . '>' . $v . '</option>';
          endforeach;
          ?>
        </select>
      </div>
      <div class="col-md-2 col-xs-3">
        <label>Dia</label>
        <select id="day" class="form-control">
          <option value="all">Todos</option>
          <?php
          for ($i = 1; $i <= $endDay; $i++):
            $s = ($i == $day) ? 'selected' : '';
            echo '<option value="' . $i . '" ' . $s . '>' . $i . '</option>';
          endfor;
          ?>
        </select>
      </div>
      <div class="col-md-3 col-xs-6">
        <label>Servicio</label>
        <select id="f_rate" class="form-control">
          <option value="all">Todos</option>
          <?php
          foreach ($rateFilter as $k => $v):
            $s = ($k == $filt_rate) ? 'selected' : '';
            echo '<option value="' . $k . '" ' . $s . ' class="b">' . $v['n'] . '</option>';
            foreach ($v['l'] as $k2 => $v2):
              $aux = "$k-$k2";
              $s = ($aux == $filt_rate) ? 'selected' : '';
              echo '<option value="' . $aux . '" ' . $s . '>&nbsp; - ' . $v2 . '</option>';
            endforeach;
          endforeach;
          ?>
        </select>
      </div>
      <div class="col-md-2 col-xs-6">
        <label>Tipo de Pago</label>
        <select id="f_method" class="form-control">
          <option value="all">Todos</option>
          <option value="banco" <?php if ($filt_method == 'banco') echo 'selected' ?>>BANCO</option>
          <option value="cash" <?php if ($filt_method == 'cash') echo 'selected' ?>>METALICO</option>
          <option value="card" <?php if ($filt_method == 'card') echo 'selected' ?>>TARJETA</option>
          <option value="bono" <?php if ($filt_method == 'bono') echo 'selected' ?>>BONO</option>
        </select>
      </div>
      <div class="col-md-3 col-xs-6">
        <label>ENTRENADOR/FISIO</label>
        <select id="f_coach" class="form-control">
          <option></option>
          <?php
          foreach ($aCoachs as $id => $name):
            $sel = ($f_coach == $id) ? 'selected' : '';
            ?>
            <option value="{{$id}}" <?php echo $sel; ?>>{{$name}}</option>
            <?php
          endforeach;
          ?>
        </select>
      </div>
  </div>
  <div class="row" id="content-table-inform">
    <div class="table-responsive">
    @include('admin.informes._table_sumary')
    </div>
    <div class="table-responsive">
    @include('admin.informes._table_informes')
    </div>
  </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
  $('#date, #month, #day,#f_rate,#f_method,#f_coach').change(function (event) {

    var year = $('#date').val();
    var month = $('#month').val();
    var day = $('#day').val();
    var f_rate = $('#f_rate').val();
    var f_method = $('#f_method').val();
    var f_coach = $('#f_coach').val();
    window.location = '/admin/informes/cliente-mes/' + month + '/' + day + '/' + f_rate + '/' + f_method + '/' + f_coach;
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
@endsection