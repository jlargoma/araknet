<link rel="stylesheet" href="{{ assetV('css/contabilidad.css') }}">
<script type="text/javascript" src="/admin-css/assets/js/plugins/chartJs/Chart.min.js"></script>
<script type="text/javascript" src="/admin-css/assets/js/charts.js"></script>
<?php
$tYearMonths = '';
$aux = '';
$ij = 0;
foreach ($currentY as $t => $monthData) {
  $aux .= '{
              data: [';
  foreach ($monthData as $k => $v) {
    if ($k > 0)
      $aux .= "'" . round($v) . "',";
  }

  $aux .= '],
              label: "'. ($t) . '",
              borderColor: "' . printColor($ij) . '",
              fill: false
            },';
  $ij++;
}
$tYearMonths = $aux;
/***************************************/
$labels_1 = [];
$values_1 = [];
$bColor_1 = [];
$ij = 0;
if ($crLst) {
    foreach ($crLst as $k => $v) {
      $valueAux = ($v[0]>0) ? round(($v[0]/$tIncomes)*100) : 0;
      $bColor_1[] = '"'.printColor($ij).'"';
      $labels_1[] = "'$oRateTypes[$k]'";
      $values_1[] = $valueAux;
      $ij++;
    }
  }
  
//$tIncomes
        
?>

<script type="text/javascript">
$(document).ready(function () {

  var chart_1 = {
        labels: [<?php echo implode(',',$labels_1)?>],
        datasets: [
          {
            data: [<?php echo implode(',',$values_1)?>],
            backgroundColor: [<?php echo implode(',',$bColor_1)?>],
          }
        ]
      }
    
  getPieChart('chart_1',chart_1);
    
  new Chart(document.getElementById("chartTotalByMonth"), {
    type: 'line',
    data: {
      labels: [
<?php foreach ($monts as $v)
  echo "'" . $v . "',"; ?>
      ],
      datasets: [<?php echo $tYearMonths; ?>]
    },
    options: {
      title: {
        display: true,
        text: 'Total x Año'
      }
    }
  });

  var myBarChart = new Chart('chart_incomesYear', {
    type: 'bar',
    data: {
      labels: [
<?php foreach ($incomesYear as $k => $v) {
  echo "'" . $k . "',";
} ?>
      ],
      datasets: [
        {
          label: "Ingresos por Temp",
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          data: [
<?php foreach ($incomesYear as $k => $v) {
  echo "'" . round($v) . "',";
} ?>
          ],
        }
      ]
    }
  });
  var myBarChart = new Chart('chart_expensesYear', {
    type: 'bar',
    data: {
      labels: [
<?php foreach ($expensesYear as $k => $v) {
  echo "'" . $k . "',";
} ?>
      ],
      datasets: [
        {
          label: "Gastos por Temp",
          backgroundColor: 'red',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          data: [
<?php foreach ($expensesYear as $k => $v) {
  echo "'" . round($v) . "',";
} ?>
          ],
        }
      ]
    }
  });

var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [<?php foreach ($monts as $v) echo "'" . $v . "',"; ?>],
    datasets: [{
        label: 'METALICO',
        data: [<?php for($i=1;$i<13;$i++) echo "'" . round($pay_method['c'][$i]) . "',"; ?>],
        backgroundColor: 'rgba(52, 162, 99, 0.2)',
        borderColor: 'rgba(52,162,99,1)',
        borderWidth: 2
      },
      {
        label: 'BANCO',
        data: [<?php for($i=1;$i<13;$i++) echo "'" . round($pay_method['b'][$i]) . "',"; ?>],
        backgroundColor: 'rgb(92,144,210,0.2)',
        borderColor: '#5c90d2',
        borderWidth: 2
      },
      {
        label: 'VISA',
        data: [<?php for($i=1;$i<13;$i++) echo "'" . round($pay_method['v'][$i]) . "',"; ?>],
        backgroundColor: 'rgb(210,92,147,0.2)',
        borderColor: '#d25c93',
        borderWidth: 2
      }
    ]
  },
  options: {
    scales: {
      yAxes: [{
        stacked: true,
        ticks: {
          beginAtZero: true
        }
      }],
      xAxes: [{
        stacked: true,
        ticks: {
          beginAtZero: true
        }
      }]

    },
    legend:{display:false}
  }
});


  $('.detail').on('click',function(){
    var k = $(this).data('id');
    if($(this).data('t') == 'i'){
      $.get('/admin/ingreso-by-rate/'+k, function (data) {
        $('#contentModalInfo').html(data);
        $('#modalInfo').modal('show');
      });
    }
    if($(this).data('t') == 'e'){
      $.get('/admin/gastos-by-byType/'+k, function (data) {
        $('#contentModalInfo').html(data);
        $('#modalInfo').modal('show');
      });
    }
  });


});
</script>