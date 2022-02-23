<?php

use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') INFORME DE HNTs - Araknet HTS @endsection

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
  .customerName {
    font-weight: bold;
    cursor: pointer;
}
.btn-months{
  text-align: center;
}

</style>
<script type="text/javascript" src="/admin-css/assets/js/plugins/chartJs/Chart.min.js"></script>
@endsection
@section('content')
<div class="content content-boxed bg-gray-lighter">
  <h2 class="text-center">INFORME DE HNTs AL MES</h2>
  <div class="col-xs-12 btn-months mx-1em">
    @foreach($months as $k=>$v)
    <a href="/admin/informes/cliente-mes/{{$k}}/{{$f_user}}" class=" btn btn-success <?php echo ($month == $k) ? 'active' : '' ?>">
      {{$v}}
    </a>
    @endforeach
  </div>
  
  
  <div class="row my-2 formLine v2">
    <div class="col-md-6">
<!--      <label >Cliente</label>
      <input type="text" id="searchCustomer" class=" field" placeholder="Buscar"/>-->
    </div>
    <div class="col-md-6">
      <label >Usuario</label>
        <select id="f_user" class="field">
          <option value="">Todos</option>
          <?php
          foreach ($ausers as $id => $name):
            $sel = ($f_user == $id) ? 'selected' : '';
            ?>
            <option value="{{$id}}" <?php echo $sel; ?>>{{$name}}</option>
            <?php
          endforeach;
          ?>
        </select>
    </div>
  </div>
  <div class="">
    <table class="table  dataTable-i1">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Último</th>
          <th>Mes</th>
          <th>Gráfico</th>
        </tr>
      </thead>
      <tbody>
        @if($byCustomer)
    @foreach($byCustomer as $cID=>$data)
      
    <?php
    if (isset($aLstCust[$cID])) {
      $customer = $aLstCust[$cID];
      $last = isset($data[$lastDay]) ? $data[$lastDay] : 0;
      ?>
    <tr>
      <td>{{$customer->name}}</td>
      <td>{{$last}}</td>
      <td>{{array_sum($data)}}</td>
      <td><button class="btn btn-empty-green showGraf" data-id="{{$cID}}" data-name="{{$customer->name}}"><i class="fa fa-bar-chart <?= ($last == 0) ? 'text-danger' : '' ?>"></i></button></td>
    </tr>
      <?php
    }
    ?>

    @endforeach
    @endif
      </tbody>
    </table>
    
  </div>
</div>

<div class="modal fade in" id="modalChart" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button" ><i class="si si-close"></i> Cerrar</button>
            </li>
          </ul>
        </div>
        <div class="modal-content-box">
          <h2><span id="cNamechart"></span> ({{$cMonth}})</h2>
          <div class="box">
          <canvas id="hnt_customer" style="width:320px; height: 100px;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




@endsection
@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ assetV('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>
<script type="text/javascript">
  var dataTableInformMes = true;
  $('#f_user').change(function (event) {
    var f_user = $('#f_user').val();
    window.location = '/admin/informes/cliente-mes/{{$month}}/' + f_user;
  });

  $('#searchCustomer').keyup(function (evt) {
    var name = $(this).val().toUpperCase();
    console.log(name);
    $('.lstHNT').each(function(){
      var  str = $(this).data('cname').toUpperCase();
      if ( str.includes(name)){
        $(this).show();
      } else 
        $(this).hide();
    });
  });
  
  var daysText = [<?php echo $daysText;?>];
  
  var byCustomer = [];
  
    @if($byCustomer)
      @foreach($byCustomer as $cID=>$data)
    byCustomer[{{$cID}}] = [<?php echo implode(',',$data);?>];
    @endforeach
    @endif
      console.log(byCustomer);
  
  $('.showGraf').on('click', function(){
    var cID = $(this).data('id');
    $('#cNamechart').text($(this).data('name'));
    new Chart(document.getElementById("hnt_customer"), {
        type: 'line',
        data: {
            datasets: [{
                data: byCustomer[cID],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }],
            labels: daysText
        },
        options: {
          title: {
            display: false,
          },
          legend:{
          display: false,
          }
        }
      });
      $('#modalChart').modal();
  });
  

</script>
@endsection