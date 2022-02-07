<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<?php

use \Carbon\Carbon; ?>

@extends('layouts.admin-master')

@section('title') Cuenta de perdidas y ganancias @endsection

@section('content')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>

<div class="bg-white push-10">
  <section class="content content-full">
    <div class="row">
      <div class="col-md-12 col-xs-12 push-20">
        @include('admin.contabilidad._button-contabiliad')
      </div>
      <div class="col-xs-12 col-md-8">
        <div class="text-rigth">
          <h3>Total <?php echo number_format($salesTotal, 1, '.', ','); ?> €</h3>
        </div>
        <div class="mx-1em box-shadow">
          @include('admin.contabilidad.ventas.tipos')
        </div>
        <div class="mx-1em box-shadow">
          @include('admin.contabilidad.ventas.By_tGroup')
        </div>
        <div class="mx-1em box-shadow">
          @include('admin.contabilidad.ventas.By_TypePay')
        </div>
        <div class="mx-1em box-shadow">
          @include('admin.contabilidad.ventas.By_FISIO')
        </div>
      </div>
      <div class="col-xs-12 col-md-4">
        <div class="col-md-12 col-xs-12">
          <div>
              <canvas id="barChartMonth" style="width: 100%; height: 250px;"></canvas>
          </div>
        </div>
        <div class="col-md-12 col-xs-12 mx-1em">
          <div>
              <canvas id="barChartYears" style="width: 100%; height: 250px;"></canvas>
          </div>
        </div>
    </div>
  </section>
</div>
@endsection
@section('scripts')
<style type="text/css">
  #main-container{
    padding-top: 10px!important
  }
  .tf-2 .static{
    width: 7em !important;
  }
  .tf-2 .first-col{
    padding-left: 9em !important;
  }
</style>

<script type="text/javascript">
        $(document).ready(function() {

function formatNumber(num) {
return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

var myBarChart = new Chart('barChartMonth', {
type: 'bar',
data: {
  labels: [
<?php
foreach ($lstMonthsYear as $m) {
  echo "'" . getMonthSpanish($m['m']) . "',";
}
?>
  ],
  datasets: [
    {
      label: "Salarios en el Año",
      borderColor: "rgba(54, 162, 235, 0.2)",
      backgroundColor: "rgba(54, 162, 235, 0.5)",
      borderWidth: 1,
      data: [{!! $resume[0] !!}],
    }
  ]
},
options: {
tooltips: {
    callbacks: {
    //https://www.chartjs.org/docs/latest/configuration/tooltip.html
        label: function(tooltipItem, data) {
            label = ' '+formatNumber(tooltipItem.yLabel)+' €';
            return label;
        }
    }
}
}
});
/********************************************************/
  new Chart(document.getElementById("barChartYears"), {
    type: 'line',
    data: {
      labels: [
        <?php
        foreach ($lstMonthsYear as $m) {
          echo "'" . getMonthSpanish($m['m']) . "',";
        }
        ?>
      ],
      datasets: [{
        data: [{!! $resume[0] !!}],
        label: '<?php echo $year ?>-<?php echo $year - 1 ?>',
        borderColor: "rgba(54, 162, 235, 1)",
        fill: false
      },
        {
          data: [{!! $resume[1] !!}],
          label: '<?php echo $year - 1 ?>-<?php echo $year - 2 ?>',
          borderColor: "rgba(104, 255, 0, 1)",
          fill: false
        },
        {
          data: [{!! $resume[2] !!}],
          label: '<?php echo $year - 2 ?>-<?php echo $year - 3 ?>',
          borderColor: "rgba(232, 142, 132, 1)",
          fill: false
        }
      ]
    },
    options: {
      title: {
        display: false,
        text: ''
      }
    }
  });
      
      
});
</script>
@endsection