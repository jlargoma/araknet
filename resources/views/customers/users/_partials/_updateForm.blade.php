<form class="form-horizontal" action="{{ url('/admin/usuarios/update') }}" method="post">
	<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	<input type="hidden" name="id" value="{{ $user->id }}">
    <div class="col-md-12 col-xs-12 push-20">
        
        <div class="col-md-6  push-20">
            <div class="form-material">
            	<label for="name">Nombre</label>
                <input class="form-control" type="text" id="name" name="name" required value="<?php echo $user->name ?>">
                
            </div>
        </div>
        <div class="col-md-6  push-20">
            <div class="form-material">
                <label for="email">E-mail</label>
                <p><?php echo $user->email ?></p>
                <input class="form-control" type="hidden" id="email" name="email" value="<?php echo $user->email ;?>">
                
            </div>
        </div>

        <div class="clear"></div>

        <div class="col-md-6  push-20">
            <div class="form-material">
                <label for="phone">Teléfono</label>
                <input class="form-control" type="number" id="phone" name="phone" required maxlength="9" value="<?php echo $user->phone ?>">
                
            </div>
        </div>
        <div class="col-md-6  push-20">
            <div class="form-material">
            	<label for="password">Contraseña</label>
            	<input type="hidden" name="id_tax" value="<?php echo $user->id_tax; ?>">
            	<input type="hidden" name="role" value="<?php echo $user->role; ?>">
                <input class="form-control" type="password" id="password" name="password" required value="<?php echo $user->password ?>">
                
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 push-20 text-center">
		<button class="btn btn-success" type="submit">
			<i class="fa fa-floppy-o" aria-hidden="true"></i> Actualizar
		</button>
	</div>
</form>