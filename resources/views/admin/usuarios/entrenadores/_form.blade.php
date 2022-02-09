@extends('layouts.popup')
@section('content')
<div class="row block bg-white">
    <div class="col-xs-12 my-2">
        <div class="block-1"><img src="/admin-css/assets/img/profile.png" class="img-responsive" style="max-width: 100%;"></div>
        <div class="block-2">
            <h2>{{$user->name}}</h2>
            <h4>{{$user->email}}</h4>
        </div>
       
    </div>
    <div class="col-xs-12">
        <form class="form-horizontal" action="{{ url('/admin/usuarios/update') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="id" value="{{ $user->id }}">
            <div class="row">

                <div class="col-lg-4 col-sm-4">
                    <div class="form-material">
                        <input class="form-control" type="text" id="name" name="name" required value="<?php echo $user->name ?>">
                        <label for="name">Nombre</label>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4  push-20">
                    <div class="form-material">
                        <input type="text" id="email" class="form-control" name="email" required value="<?php echo $user->email ?>">
                        <label for="email">E-mail</label>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4  push-20">
                    <div class="form-material">
                        <input class="form-control" type="number" id="phone" name="phone" required maxlength="9" value="<?php echo $user->phone ?>">
                        <label for="phone">Teléfono</label>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4  push-20">
                    <div class="form-material">
                        <input class="form-control" type="text" id="iban" name="iban" maxlength="20" value="<?php echo $user->iban ?>" >
                        <label for="iban">IBAN</label>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 push-20">
                    <div class="form-material">
                        <input class="form-control" type="password" id="password" name="password" value="">
                        <label for="password">Contraseña</label>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 push-20">
                    <div class="form-material">
                        <select class="form-control" id="role" name="role" style="width: 100%;" data-placeholder="Seleccione una role" required>
                          @foreach($user->roles as $k=>$v)
                          <option value="{{$k}}" <?php if ($user->role == $k) echo "selected";?>>{{$v}}</option>
                          @endforeach
                        </select>
                        <label for="role">Role</label>
                    </div>
                </div>
              <div class="col-lg-4 col-sm-4 push-20" style="height: 4em;">
                  <div class="form-material"><label>&nbsp;</label></div>
                </div>
                <div class="col-lg-2 col-sm-4 push-20">
                    <div class="form-material">
                        <input class="form-control" type="text" id="salario_base" name="salario_base" maxlength="20" value="{{$salario_base}}" >
                        <label for="salario_base">Salario Base</label>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 push-20">
                    <div class="form-material">
                        <input class="form-control" type="text" id="ss" name="ss" maxlength="18" value="<?php echo $user->ss ?>">
                        <label for="ss">Seg. soc</label>
                    </div>
                </div>
                    
                <div class="col-lg-2 col-sm-4 push-20">
                    <div class="form-material">
                        <input class="form-control" type="text" id="ppc" name="ppc" maxlength="18" value="{{$ppc}}">
                        <label for="ppc">P.P.C</label>
                        <small>Precio por Cita</small>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 push-20">
                    <div class="form-material">
                        <input class="form-control" type="text" id="pppt" name="pppt" maxlength="18" value="{{$pppt}}">
                        <label for="ppc">P.P.PT</label>
                        <small>Precio por Cita PT</small>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 push-20">
                    <div class="form-material">
                        <input class="form-control" type="text" id="ppcg" name="ppcg" maxlength="18" value="{{$ppcg}}">
                        <label for="ppc">P.P.Grup</label>
                        <small>Precio por Citas Grupales</small>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 push-20">
                    <div class="form-material">
                        <input class="form-control" type="text" id="comm" name="comm" maxlength="18" value="{{$comm}}">
                        <label for="ppc">% Comisión</label>
                    </div>
                </div>
<!--                <div class="col-lg-2 col-sm-4 push-20">
                  <button class="btn btn-horarios" type="button" data-id="{{ $user->id }}">Horarios</button>
                </div>-->
            </div>

            <div class=" text-center my-2">
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Actualizar
                </button>
            </div>
        </form>

    </div>
</div>
<div class="row block">
  <div class="col-xs-12">
    <h3 class="mt-1">Pagos Realizados</h3>
<div id="blockPayments"></div>
</div>
</div>
<div class="row">
  <div class="col-md-3"><h3>Liquidación Mensual</h3></div>
  
    <div class="col-xs-9" id="selectMonth">
      @foreach($aMonths as $k=>$v)
      <button type="button" data-v="{{$k}}" class="btn <?php echo ($month == $k) ? 'active' : '' ?>">{{$v}}</button>
      @endforeach
    </div>
    <div class="col-xs-3">
      <button class="btn btn-success" id="sendLiquid">
              <i class="fa fa-envelope"></i> enviar
          </button>
    </div>
    <div class="block col-md-12 bg-white">
        <div id="blockLiquid"></div>
    </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function () {
    $('#blockPayments').load('/admin/paymentsEntrenador/{{$user->id}}');
    $('#blockLiquid').load('/admin/liquidacion-Entrenador/{{$user->id}}');
    
    var currentMonth = "{{$month}}";
    $('#selectMonth').on('click','button',function (event) {
        var val = $(this).data('v');
        $('#blockLiquid').load('/admin/liquidacion-Entrenador/{{$user->id}}/'+val);
        $('#sendLiquid').attr("disabled", false);
        $('#selectMonth').find('button').removeClass('active');
        $(this).addClass('active');
        currentMonth = val;
    });
    
    var saveLiq = function(obj,type){
      var data = {
        id_coach: {{$user->id}},
        importe: obj.val(),
        date: obj.data('k'),
        type: type,
        _token: '{{csrf_token()}}',
      };
      $.post('/admin/payment-Entrenador', data).done(function (resp) {
         $('#blockPayments').load('/admin/paymentsEntrenador/{{$user->id}}');
      });
    }
    
    $('#blockPayments').on('change','.liquidation',function (event) {
        saveLiq($(this),'liq');
    });
    $('#blockPayments').on('change','.commision',function (event) {
        saveLiq($(this),'comm');
    });
    $('#sendLiquid').on('click',function (event) {
        var id_coach = {{$user->id}};
        var date = $('#selectMonth .active').data('v');
        var dateText = $('#selectMonth  .active').text();
        var that = $(this);
        $.get('/admin/enviar-liquidacion-Entrenador/'+id_coach+'/'+date).done(function (resp) {
          if (resp == 'OK'){
              alert('Liquidación '+dateText+' enviada');
              that.attr("disabled", true);
          } else alert(resp);
        });
      });  
      
});
    
</script>
<style type="text/css">
   .block-1 {
    float: left;
    width: 90px;
}
  .block-2 {
    float: left;
    width: calc(98% - 450px);
}
.block-2 h2,
.block-2 h4{
        padding-left: 12px;
}
  .block-3 {
    float: left;
width: 180px;
    text-align: center;
}
button.btn.active {
    background-color: #4ec37a;
}
</style>
@endsection