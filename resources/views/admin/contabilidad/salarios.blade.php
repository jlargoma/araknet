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
        <div class="table-responsive  box-shadow">
          <table class="table table-fixed">
            <thead >
              <tr class="fixed-head">
                <th class="static"></th>
                <th class="first-col">Sesiones</th>
                <th class="text-center">Salario</th>
                @foreach($lstMonths as $month)
                <th colspan="2" class="text-center">{{getMonthSpanish($month['m'])}} {{$month['y']}}</th>
                @endforeach
              </tr>
              <tr class="fixed-sub-head">
                <th class="static"></th>
                <th class="first-col"></th>
                <th></th>
                @foreach($lstMonths as $month)
                <th>Sesiones</th>
                <th class="line-td">Salario</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach($byUsers as $u)
              <tr class="salary_first">
                  <td class="static">{{$u['name']}}</td>
                  <td class="first-col">{{$u['total_session']}}</td>
                  <td class="line-td nowrap"><?php echo number_format($u['total_sal'],1,'.',','); ?> €</td>
                  @foreach($lstMonths as $k=>$month)
                    @if(isset($u[$k]))
                      <td >{{$u[$k]['ses']}}</td>
                      <td class="line-td nowrap"><?php echo number_format($u[$k]['sal'],1,'.',','); ?> €</td>
                    @else
                    <td >--</td>
                    <td class="line-td" >0 €</td>
                    @endif
                  @endforeach
                </tr>
                @for($i=1;$i<4;$i++)
                  @if(['tr_'.$i] && isset($rateTipes[$i]))
                  <tr>
                    <td class="static">{{$rateTipes[$i]}}</td>
                    <td class="first-col">{{$u['total_session_'.$i]}}</td>
                    <td class="line-td nowrap"><?php echo number_format($u['total_sal_'.$i],1,'.',','); ?> €</td>
                    @foreach($lstMonths as $k=>$month)
                      @if(isset($u[$k][$i]))
                        <td >{{$u[$k][$i]['ses']}}</td>
                        <td class="line-td nowrap"><?php echo number_format($u[$k][$i]['sal'],1,'.',','); ?> €</td>
                      @else
                      <td >&nbsp;</td>
                      <td class="line-td" >&nbsp;</td>
                      @endif
                    @endforeach
                  </tr>
                  @endif
                @endfor
              @endforeach
            </tbody>
          </table>
          
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
        <div class="col-md-12 col-xs-12 mx-1em">
          <div class="table-responsive  box-shadow">
          <table class="table table-fixed tf-2">
            <thead >
              <tr class="fixed-head">
                <th class="static"></th>
                <th class="first-col">Total</th>
                @foreach($lstMonths as $month)
                <th class="text-center">{{getMonthSpanish($month['m'])}} {{$month['y']}}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @for($i=1;$i<4;$i++)
              @if(isset($rateTipes[$i]))
                  <tr>
                    <td class="static">{{$rateTipes[$i]}}</td>
                    <td class="first-col nowrap"><?php echo number_format($resume['t_'.$i],1,'.',','); ?> €</td>
                    @foreach($lstMonths as $k=>$month)
                      @if(isset($resume[$k]['t_'.$i]))
                        <td class="nowrap text-right"><?php echo number_format($resume[$k]['t_'.$i],1,'.',','); ?> €</td>
                      @else
                      <td class="text-center" >--</td>
                      @endif
                    @endforeach
                  </tr>
              @endif
              @endfor
            </tbody>
          </table>
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
foreach ($lstMonths as $m) {
  echo "'" . getMonthSpanish($m['m']) . "',";
}
?>
  ],
  datasets: [
    {
      label: "Salarios en el Año",
      backgroundColor: [
<?php
foreach ($lstMonths as $m) {
  echo "'rgba(54, 162, 235, 0.5)',";
}
?>
      ],
      borderColor: [
<?php
foreach ($lstMonths as $m) {
  echo "'rgba(54, 162, 235, 0.2)',";
}
?>
      ],
      borderWidth: 1,
      data: [
<?php
foreach ($lstMonths as $k => $v):
  if (isset($resume[$k]))
    echo "'" . round($resume[$k]['total']) . "',";
  else
    echo "'0',";
endforeach
?>
      ],
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
        foreach ($lstMonths as $m) {
          echo "'" . getMonthSpanish($m['m']) . "',";
        }
        ?>
      ],
      datasets: [{
        data: [
          <?php
          foreach ($lstMonths as $k => $v):
            if (isset($resume[$k]))
              echo "'" . round($resume[$k]['total']) . "',";
            else
              echo "'0',";
          endforeach
          ?>
        ],
        label: '<?php echo $year ?>-<?php echo $year - 1 ?>',
        borderColor: "rgba(54, 162, 235, 1)",
        fill: false
      },
        {
          data: [{!! $resume_2 !!}],
          label: '<?php echo $year - 1 ?>-<?php echo $year - 2 ?>',
          borderColor: "rgba(104, 255, 0, 1)",
          fill: false
        },
        {
          data: [{!! $resume_3 !!}],
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