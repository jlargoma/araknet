<script type="text/javascript">
$(document).ready(function () {
  $('#cuotas-pendientes').click(function () {
    $('#estado-payment').click();
  })

  $('#addIngreso').click(function () {
    $.get('/admin/nuevo/ingreso', function (data) {
      $('#contentListIngresos').empty().append(data);
    });
  });
$('.openEditCobro').on('click', function (e) {
            e.preventDefault();
            var cobro_id = $(this).data('cobro');
            $('#ifrCliente').attr('src','/admin/update/cobro/' + cobro_id);
            $('#modalCliente').modal('show');
        });
        $('.openCobro').on('click',function (e) {
            e.preventDefault();
            var rate = $(this).data('rate');
             var appointment = $(this).data('appointment');
                       
            if (appointment>0){
              $('#ifrCliente').attr('src','/admin/clientes/cobro-cita/' + appointment);
//              alert('Las citas se deben abonar en el calendario'); return;
            } else {
              $('#ifrCliente').attr('src','/admin/clientes/generar-cobro/' + rate);
            }
            $('#modalCliente').modal('show');
        });

   


  $('.btn-edit-cobro').click(function (e) {
    e.preventDefault();
    var charge_id = $(this).data('charge');
    var rate_id = $(this).data('rate');
    $('#ifrCliente').attr('src','/admin/update/cobro/' + charge_id);
  });

  $('#newUser').click(function (e) {
    e.preventDefault();
    $('#ifrCliente').attr('src','/admin/cliente/nuevo' );
    $('#modalCliente').modal('show');
  });

  $('#containerTableResult').on('click','.openUser',function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('#ifrCliente').attr('src','/admin/cliente/informe/' + id);
    $('#modalCliente').modal('show');
  });

  $('.add_rate').click(function (e) {
    e.preventDefault();
    var customer_id = $(this).attr('data-idUser');
    $('#ifrCliente').attr('src','/admin/cliente/cobrar/tarifa?customer_id=' + customer_id);
  });
  
  $('#date').change(function (event) {

    var month = $(this).val();
    window.location = '/admin/clientes/' + month;
  });

  $('#containerTableResult').on('change', '.switchStatus', function (event) {
    var id = $(this).attr('data-id');
    if ($(this).is(':checked')) {
      $.get('/admin/cliente/activate/' + id, function (data) {
      });
    } else {
      $.get('/admin/cliente/disable/' + id, function (data) {
      });
    }
  });

  $('#date-nutri, #date-fisio').click(function (event) {
    event.preventDefault();
    var customer_id = $(this).attr('data-idUser');
    var consulta = $(this).attr('data-title');
    // $.get(', {customer_id: customer_id}, function(data) {
    $('#content-date').empty().load('/admin/citas/form/inform/create/' + customer_id + '/' + consulta);
    // });
  });



});

  @if($detail)
    var details = {!!$detail!!};
  @endif
</script>
<script src="{{assetv('/admin-css/assets/js/toltip.js')}}"></script>