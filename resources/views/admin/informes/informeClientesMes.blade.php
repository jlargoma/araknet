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
      <label >Cliente</label>
      <input type="text" id="searchCustomer" class=" field" placeholder="Buscar"/>
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
  <div class="row" id="content-table-inform">

    @if($byCustomer)
    @foreach($byCustomer as $cID=>$data)

    <?php
    if (isset($aLstCust[$cID])) {
      $customer = $aLstCust[$cID];
      ?>
      <div class="col-lg-4 col-sm-6 col-xs-12 lstHNT" data-cname="{{$customer->name}}">
        <canvas id="hnt_c{{$cID}}" style="width: 100%; height: 250px;"></canvas>
        <div class="text-center">
          <div class="customerName">{{$customer->name}}</div>
          <span>Activado: {{dateMin($customer->hotspot_date)}}</span>
        </div>
      </div>
      <?php
    }
    ?>

    @endforeach
    @endif
  </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
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
  
  
   @if($byCustomer)
      @foreach($byCustomer as $cID=>$data)
    
    
       new Chart(document.getElementById("hnt_c{{$cID}}"), {
        type: 'line',
        data: {
            datasets: [{
                data: [<?php echo implode(',',$data);?>],
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
       
        @endforeach
    @endif
 
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  

</script>
@endsection