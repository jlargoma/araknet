<div class="row">
  <div class="col-xs-12 push-20">
    <h2 class="text-center font-w300">Nuevo Usuario</h2>
  </div>
  <form class="form-horizontal" action="{{ url('/admin/usuario/create') }}"  id="form-new" method="post">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <div class="col-md-12 col-xs-12 push-20">

      <div class="col-md-6  push-20">
        <div class="form-material">
          <input class="form-control" type="text" id="name" name="name" required>
          <label for="name">Nombre</label>
        </div>
      </div>
      <div class="col-md-6  push-20">
        <div class="form-material">
          <input class="form-control" type="email" id="email" name="email" required>
          <label for="email">E-mail</label>
        </div>
      </div>

      <div class="clear"></div>

      <div class="col-md-6  push-20">
        <div class="form-material">
          <input class="form-control" type="number" id="telefono" name="telefono" maxlength="9" required>
          <label for="telefono">Teléfono</label>
        </div>
      </div>
      <div class="col-md-6  push-20">
        <div class="form-material">
          <input class="form-control" type="text" id="password" name="password" required>
          <label for="password">Contraseña</label>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xs-12 push-20">
      <div class="col-md-6  push-20">
        <div class="form-material">
          <label for="role">Role</label>
          <select class="form-control" id="role" name="role" data-placeholder="Seleccione una role" required>
            <option value=""></option>
            @foreach($roles as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xs-12 push-20">
      <div class="col-md-6  push-20">
        <div class="form-material">
          <input class="form-control" type="text" id="iban" name="iban" maxlength="20">
          <label for="iban">IBAN</label>
        </div>
      </div>
      <div class="col-md-6  push-20">
        <div class="form-material">
          <input class="form-control" type="text" id="ss" name="ss" maxlength="18">
          <label for="ss">Seg. soc</label>
        </div>
      </div>

    </div>
    <div class="col-md-12 col-xs-12 push-20">
      <div class="col-md-6  push-20">
        <div class="form-material">
          <input class="form-control" type="text" id="salario_base" name="salario_base" maxlength="20">
          <label for="salario_base">Salario Base</label>
        </div>
      </div>
      <div class="col-md-6  push-20">
        <div class="form-material">
          <input class="form-control" type="text" id="ppc" name="ppc" maxlength="18">
          <label for="ppc">P.P.C</label>
        </div>
      </div>

    </div>
    <div class="col-md-12 col-xs-12 push-20 text-center">
      <button class="btn btn-success" type="submit">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
      </button>
    </div>
  </form>
</div>

<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script>
jQuery(function () {
    App.initHelpers(['datepicker', 'select2']);
});


$(document).ready(function () {
    $('#email').keyup(function () {
        var value = $(this).val();
        $('#password').val(value);
    });




});
</script>