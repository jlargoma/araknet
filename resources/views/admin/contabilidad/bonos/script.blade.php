<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />



<script type="text/javascript">
$(document).ready(function () {
  jQuery('#tableBonos').dataTable({
    initComplete: function () {
      $('div.loading').remove();
      $('#containerTableResult').show();
    },
    pageLength: 500,
    lengthMenu: [[500], [500]]
  });
  
   $('.history').on('click',function(){
//        $('.lstBono').removeClass('selected');
//        $(this).addClass('selected');
        $('#cnameLog').text($(this).data('c'));
        $('#cbonoLog').text($(this).data('b'));
        $('#contentBonoLog').load('/admin/bonologs/'+$(this).data('id'));
        $('#modalInfo').modal('show');
      })
      
  $('#bonoRate').on('change', function (event) {
    location.href = '/admin/bonos-clientes?f='+$(this).val();
});

});
</script>
<style>
    .date-filter {
float: right;
    width: 310px;
}
select#bonoRate {
    height: 28px;
    padding: 0 8px;
}
div#tableBonos_length {
    display: none;
}
#tableBonos thead th{
    color: #FFF;
    text-align: center;
    background-color: #48b0f7;
}
#tableBonos td{
    text-align: center;
}
#tableBonos td.tleft{
  text-align: left;
}
  </style>