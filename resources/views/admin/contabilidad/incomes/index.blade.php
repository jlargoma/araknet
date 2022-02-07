@extends('layouts.admin-master')
@section('title') RESUMEN  INGRESOS POR  LINEA DE  NEGOCIO - EVOLUTIO  @endsection

<?php 
function sumMonthValue($m){
  $t=0;
  foreach ($m as $k=>$v){
    if (is_numeric($k)){
      $t += $v;
    }
  }
  return moneda($t);
}?>
@section('content')
@include('admin.contabilidad._button-contabiliad')

<div class="content">
<div class="row">
  
  <div class="col-sm-6">
    <canvas id="chartTotalByYear" style="width: 100%; height: 250px;"></canvas>
  </div>
  <div class="col-sm-6">
      <canvas id="chartTotalByMonth" style="width: 100%; height: 250px;"></canvas>
  </div>
  <div class="col-xs-12">
  @include('admin.contabilidad.incomes.table')
  </div>
</div>
  </div>
@endsection

@section('scripts')
<link rel="stylesheet" href="{{ assetV('css/contabilidad.css') }}">
<script type="text/javascript" src="/admin-css/assets/js/plugins/chartJs/Chart.min.js"></script>
<script type="text/javascript" src="/admin-css/assets/js/charts.js"></script>
<?php
  $tYearMonths = '';
    $aux = '';
    $ij = 0;
     foreach ($byYears as $Y=>$monthData){
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
?>
        
<script type="text/javascript">
    $(document).ready(function () {
      $('.d1').on('click',function(){
        var k = $(this).data('k');
        
        $('.d1_'+k).each(function(){
          if ($(this).css('display') != 'none'){
            var k = $(this).data('k');
            
            $('.d2_'+k).each(function(){
              if ($(this).css('display') != 'none'){
                var k = $(this).data('k');
                $('.d3_'+k).each(function(){
                  if ($(this).css('display') != 'none'){
                    var k = $(this).data('k');
                    $('.d4_'+k).hide();
                  }
                });
                $('.d3_'+k).hide();
              }
            });
            $('.d2_'+k).hide();
          }
        });
        
        $('.d1_'+k).toggle();
      });
      $('.d2').on('click',function(){
        var k = $(this).data('k');
        
        $('.d2_'+k).each(function(){
          if ($(this).css('display') != 'none'){
            var k = $(this).data('k');
            $('.d3_'+k).each(function(){
              if ($(this).css('display') != 'none'){
                var k = $(this).data('k');
                $('.d4_'+k).hide();
              }
            });
            $('.d3_'+k).hide();
          }
        });
        
        $('.d2_'+k).toggle();
      });
      $('.d3').on('click',function(){
        var k = $(this).data('k');
        $('.d3_'+k).toggle();
      });
      
      
      
      
       new Chart(document.getElementById("chartTotalByMonth"), {
        type: 'line',
        data: {
          labels: [
            <?php foreach ($monts as $v) echo "'" . $v . "',";?>
          ],
          datasets: [ <?php echo $tYearMonths;?>]
        },
        options: {
          title: {
            display: true,
            text: 'Total x Año'
          }
        }
      });
      
      var myBarChart = new Chart('chartTotalByYear', {
        type: 'bar',
        data: {
          labels: [
              <?php foreach ($tByYears as $k=>$v){ echo "'" . $k. "'," ;} ?>
          ],
          datasets: [
            {
              label: "Ingresos por Temp",
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1,
              data: [
                  <?php foreach ($tByYears as $k=>$v){ echo "'" . round($v). "'," ;} ?>
              ],
            }
          ]
          }
      });
      
      
      
      
      
      
      
      
    });
</script>
@endsection
