function getPieChart(idDom, pieData) {
  // Get context with jQuery - using jQuery's .get() method.
  var oChartCanvas = $('#' + idDom).get(0).getContext('2d')
  var oOptions = {
//    maintainAspectRatio: false,
//    responsive: true,
    legend: {
      labels: {
        fontSize: 11,
        boxWidth: 15,
      },
      position: 'right'
    },
    tooltips: {},
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  var pieChart = new Chart(oChartCanvas, {
    type: 'pie',
    data: pieData,
    options: oOptions
  })
}

function getLineChart(idDom, pieData) {
  // Get context with jQuery - using jQuery's .get() method.
  var oChartCanvas = $('#' + idDom).get(0).getContext('2d')
  var oOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      labels: {
        fontColor: '#000',
        fontSize: 16,
        boxWidth: 25
      }
    },
    tooltips: {},
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  var pieChart = new Chart(oChartCanvas, {
    type: 'line',
    data: pieData,
    options: oOptions
  })
}


