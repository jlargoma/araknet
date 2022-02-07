<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



<script type="text/javascript">
$(document).ready(function () {
  jQuery('#tableInvoices').dataTable({
    initComplete: function () {
      $('div.loading').remove();
      $('#containerTableResult').show();
    },
    pageLength: 500,
    lengthMenu: [[500], [500]]
  });
  
  var dateRangeObj = {
    autoUpdateInput: true,
    locale: {
      firstDay: 1,
      format: 'DD/MM/YYYY',
      "applyLabel": "Aplicar",
      "cancelLabel": "Cancelar",
      "fromLabel": "From",
      "toLabel": "To",
      "customRangeLabel": "Custom",
      "daysOfWeek": [
        "Do",
        "Lu",
        "Mar",
        "Mi",
        "Ju",
        "Vi",
        "Sa"
      ],
      "monthNames": [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
      ],
    },

  };
  $('#fecha').daterangepicker(dateRangeObj);
  
  Date.prototype.yyyymmmdd = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();
    return [
      this.getFullYear(),
      (mm > 9 ? '' : '0') + mm,
      (dd > 9 ? '' : '0') + dd
    ].join('-');
  };
  
  
  $('#fecha').on('change', function (event) {
    var date = $(this).val();

    var arrayDates = date.split('-');
    var res1 = arrayDates[0];
    var res2 = arrayDates[1];
    location.href = '/admin/facturas?dates='+date;
//    console.log(res1,res2);
});

});
</script>
<style>
    .date-filter {
float: right;
    width: 310px;
}
#fecha {
    width: 205px;
        height: 25px;
    display: inline-block;
}
  </style>