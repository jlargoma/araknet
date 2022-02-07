
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">

<div class="row">
    <div class="col-xs-12 push-20">
        <h2 class="text-center font-w300">Nuevo 
            <span class="font-w600">
                <?php if( $role == "user" ){ echo "Usuario/Cliente";}?>
                <?php if( $role == "admin" ){ echo "Administrador";}?>
                <?php if( $role == "teach" ){ echo "Entrenador";}?>
            </span>
        </h2>
    </div>
	<form class="form-horizontal" action="{{ url('/admin/usuarios/create') }}"  id="form-new" method="post">
	<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	<input type="hidden" name="role" value="<?php echo $role; ?>">
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
            <div class="col-md-6  push-20 hidden">
                <div class="form-material">
                    <input class="form-control" type="text" id="password" name="password" required>
                    <label for="password">Contraseña</label>
                </div>
            </div>
        </div>

        <?php if ($role == 'teach'): ?>
            <div class="col-md-12 col-xs-12 push-20">
                <div class="col-md-6  push-20">
                    <div class="form-material">
                        <label for="role">Role</label>
                        <select class="js-select2 form-control" id="role" name="role" style="width: 100%;" data-placeholder="Seleccione una role" required>
                            <option value="teach">Entrenador</option>
                            <option value="nutri">Nutricionista</option>
                            <option value="fisio">Fisioterapeuta</option>
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
        <?php endif ?>
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


    $(document).ready(function() {
    	$('#email').keyup(function() {
    		var value = $(this).val();
    		$('#password').val(value);
    	});

        // $('#form-new').submit(function(event) {

        //     event.preventDefault();

        //     var _token       = $('input[name="_token"]').val();
        //     var name         = $('input[name="name"]').val();
        //     var email        = $('input[name="email"]').val();
        //     var phone        = $('input[name="telefono"]').val();
        //     var password     = $('input[name="password"]').val();
        //     var role         = $('select[name="role"]').val();
        //     var iban         = $('input[name="iban"]').val();
        //     var ss           = $('input[name="ss"]').val();
        //     var salario_base = $('input[name="salario_base"]').val();
        //     var ppc          = $('input[name="ppc"]').val();

        //     var url = $(this).attr('action');

        //     $.post( url , {_token : _token,  name : name,    email : email,   phone : phone,   password : password,    role : role, iban : iban, ss : ss,  salario_base : salario_base, ppc : ppc}, function(data) {
        //         if (data == "email duplicado") {
        //             alert(data);
        //         }else{
        //             location.reload();
        //         }
        //     });

        // });


    });
</script>