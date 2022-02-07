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
    $('#ifrCliente').attr('src','/admin/usuarios/nuevo' );
    $('#modalCliente').modal('show');
  });

  $('#containerTableResult').on('click','.openUser',function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('#ifrCliente').attr('src','/admin/usuarios/informe/' + id);
    $('#modalCliente').modal('show');
  });

  $('.add_rate').click(function (e) {
    e.preventDefault();
    var id_user = $(this).attr('data-idUser');
    $('#ifrCliente').attr('src','/admin/usuarios/cobrar/tarifa?id_user=' + id_user);
  });
  
  $('.add_bono').click(function (e) {
    e.preventDefault();
    var id_user = $(this).attr('data-idUser');
    var back = '/ficha/'+id_user
    $('#ifrCliente').attr('src','/admin/bonos/comprar/' + id_user + back );
    $('#modalCliente').modal('show');
  });
  
  var openAddSB = null;
  $('#containerTableResult').on('click','.openAdd',function (e) {
    e.preventDefault();
    $('.boxAddServBono').hide();
    console.log(openAddSB,$(this).data('iduser'));
    if (openAddSB != $(this).data('iduser')){
      openAddSB = $(this).data('iduser')
      $(this).closest('td').find('.boxAddServBono').show();
    } else {
      openAddSB = null;
    }
  });



  $('#date').change(function (event) {

    var month = $(this).val();
    window.location = '/admin/clientes/' + month;
  });

  $('#containerTableResult').on('change', '.switchStatus', function (event) {
    var id = $(this).attr('data-id');
    if ($(this).is(':checked')) {
      $.get('/admin/usuarios/activate/' + id, function (data) {
      });
    } else {
      $.get('/admin/usuarios/disable/' + id, function (data) {
      });
    }
  });

  $('#date-nutri, #date-fisio').click(function (event) {
    event.preventDefault();
    var id_user = $(this).attr('data-idUser');
    var consulta = $(this).attr('data-title');
    // $.get(', {id_user: id_user}, function(data) {
    $('#content-date').empty().load('/admin/citas/form/inform/create/' + id_user + '/' + consulta);
    // });
  });



});

  @if($detail)
    var details = {!!$detail!!};
  @endif
</script>
<script src="{{assetv('/admin-css/assets/js/toltip.js')}}"></script>