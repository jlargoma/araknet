@extends('layouts.popup')
@section('content')
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ assetV('css/custom.css') }}">

<a class="back" href="<?php echo $urlBack; ?>">X</a>
<?php 

$date_type_u = "Entrenador";
$date_type = 'pt'
?>
@if($blocked)<!-- es un bloqueo -->
  @if($isGroup)
    @include('calendars.editGroup')
  @else
    @include('calendars.editBlock')
  @endif
@else
  @include('citasPT.editDate')
@endif

@if($id<1) 
  @include('calendars.blockDate')
@endif
@endsection
@section('scripts')
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/moment.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
jQuery(function () {
    App.initHelpers(['datepicker', 'select2']);
    

        $("#id_user").change(function() {
            var id = $(this).val();
            $.get('/admin/get-mail/' + id, function(data) {
                $('#NC_email').val(data[0]);
                $('#NC_phone').val(data[1]);
            });
            $( "#id_rate").val(-1)
            $.get('/admin/get-rates/' + id, function(data) {
              
              $( "#id_rate option" ).each(function( index ) {
                var dis = true;
                var opt = parseInt($( this ).val());
                for (const i in data) {
                  if (data[i] == opt) dis = false;
                }
                $( this ).attr('disabled',dis);
              })
            });
        });
        
        $('.sendForm').on('click', function(){
          var oForm = $('#'+$(this).data('id'));
          var data = {
            id: {{$id}},
            date: oForm.find('#date').val(),
            time: oForm.find('#hour').val(),
            uID: oForm.find('#id_user').val(),
            cID: oForm.find('#id_coach').val(),
            type: 'pt',
            _token: '{{csrf_token()}}',
          };
          $.post('/admin/citas/checkDisp',data, function(resp) {
                        
            if (resp == 'bloqueo'){
              window.show_notif('error', 'Horario bloqueado para el Entrenador');
              return;
            }
            
            if (resp>1){
              window.show_notif('error', 'Horario no disponible');
            } else {
              if (resp == 1){
                if (confirm('Ya hay una cita para ese momento, continuar de todas maneras?')){
                  oForm.submit();
                }
              } else {
                oForm.submit();
              }
            }
          });
        });
        
        $(".btnDeleteCita").click(function () {
            if (confirm('Eliminar la Cita?'))
                location.assign("/admin/citas/delete/{{$id}}");
        });

        $('.btn-user').click(function (e) {
           e.preventDefault();
           var id = $(this).attr('data-idUser');
           location.href = '/admin/usuarios/informe/' + id;

        });
                    
        $('#is_group').click(function (e) {
          if($(this).is(':checked')){
            $('#id_user').val('0').attr('disabled',true);
            $('#NC_email').val('').attr('disabled',true);
            $('#NC_phone').val('').attr('disabled',true);
          } else {
            $('#id_user').val('0').attr('disabled',false);
            $('#NC_email').val('').attr('disabled',false);
            $('#NC_phone').val('').attr('disabled',false);
          }
        });
               
        $("#hour").change(function () {
          var val = $(this).find(':selected').val();
          $('#customTime').val(val+':00');
        });
    });

</script>
<style>
  input#customTime {padding: 0px 4px;}
  #tit_user{
      width: 100% !important;
    }

  #tit_user span{
      float: right;
      padding-right: 11px;
      margin-top: -3px;
    }
  #tit_user span input{
      margin-right: 6px;
      height: 13px;
    }
      a.back{
      display: none;
    }
    @media (max-width: 780px) {  
        a.back {
        display: block;
        font-weight: bold;
        float: right;
        width: 31px;
        background-color: #6e6e6e;
        color: #FFF;
        text-align: center;
        font-size: 22px;
        border-radius: 6px;
      }
    }
</style>
@endsection