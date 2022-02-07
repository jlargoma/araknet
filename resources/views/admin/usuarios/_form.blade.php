<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">


<div class="row">
    <div class="block col-md-12 bg-white">
    	<div class="col-xs-12 col-md-12 push-20">
    		<h3 class="text-center">
    			DATOS PERSONALES
    		</h3>
    	</div>
    	<div class="clear"></div>
        <form class="form-horizontal" action="{{ url('/admin/usuarios/update') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="id" value="{{ $user->id }}">
            <?php if (Auth::user()->role != "nutri"): ?>
                
                    <div class="col-md-12 col-xs-12 push-20">
                        
                        <div class="col-md-6  push-20">
                            <div class="form-material">
                                <input class="form-control" type="text" id="name" name="name" required value="<?php echo $user->name ?>">
                                <label for="name">Nombre</label>
                            </div>
                        </div>
                        <div class="col-md-6  push-20">
                            <div class="form-material">
                                <input type="text" id="email" class="form-control" name="email" required value="<?php echo $user->email ?>">
                                <label for="email">E-mail</label>
                            </div>
                        </div>

                        <div style="clear:both;"></div>

                        <div class="col-md-6  push-20">
                            <div class="form-material">
                                <input class="form-control" type="number" id="telefono" name="telefono" required maxlength="9" value="<?php echo $user->telefono ?>">
                                <label for="telefono">Teléfono</label>
                            </div>
                        </div>
                        <div class="col-md-6  push-20">
                            <div class="form-material">
                                <input class="form-control" type="password" id="password" name="password" value="">
                                <label for="password">Contraseña</label>
                            </div>
                        </div>
                        <div class="col-md-6  push-20">
                            <div class="form-material">
                                <select class="js-select2 form-control" id="role" name="role" style="width: 100%;" data-placeholder="Seleccione una role" required>
                                    <option value="user" <?php if( $user->role == "user" ){ echo "selected";}?>>Usuario/Cliente</option>
                                    <option value="admin"  <?php if( $user->role == "admin" ){ echo "selected";}?>>Administrador</option>
                                    <option value="teach" <?php if( $user->role == "teach" ){ echo "selected";}?>>Entrenador</option>
                                    <option value="administrativo" <?php if( $user->role == "administrativo" ){ echo "selected";}?>>Administrativo</option>
                                    
                                </select>
                                <label for="role">Role</label>
                            </div>
                        </div>
                    </div>
                    <?php if ($user->role == "user"): ?>
                        <div class="col-md-12 col-xs-12 push-20">
                            <div class="col-md-6  push-20">
                                <div class="form-material">
                                    <?php 
                                        $arrayIdRates = array();
                                        $userRates = \App\UserRates::where('id_user', $user->id)
                                                                    ->whereYear('created_at','=', date('Y'))
                                                                    ->whereMonth('created_at','=', date('m'))
                                                                    ->get();
                                        foreach ($userRates as $key => $rate) {
                                            $arrayIdRates[] = $rate->id_rate;
                                        }
                                    ?>
                                    <select class="form-control" id="id_rates" name="id_rates" style="width: 100%; cursor: pointer" data-placeholder="Seleccione tarifas.." >
                                        <option></option>
	                                    <?php foreach ($rates as $rate): ?>
                                            <?php if ($rate->status == 1): $class = "green" ; else: $class = "blue";endif ?>
                                            <option value="<?php echo $rate->id ?>" data-price="<?php echo $rate->price ?>" class="<?php echo $class; ?>" <?php if (in_array($rate->id,
                                            $arrayIdRates)): ?> selected <?php endif ?>>
                                                <?php echo $rate->name ?>
                                            </option>
	                                    <?php endforeach ?>
                                    </select>
                                    <label for="id_tax">Tarifa</label>
                                </div>
                            </div>
                            <div class="col-md-6  push-20">
                                <div class="form-material">
                                    <select class="js-select2 form-control" id="role" style="width: 100%;" data-placeholder="Seleccione una role" required disabled>

                                        <option value="admin"  <?php if ($user->role == 'admin'){ echo "selected"; }?>>
                                            Administrador
                                        </option>
                                        <option value="teach" <?php if ($user->role == 'teach'){ echo "selected"; }?>>
                                            Entrenador
                                        </option>
                                        <option value="user" <?php if ($user->role == 'user'){ echo "selected"; }?>>
                                            Usuario/Cliente
                                        </option>
                                    </select>
                                    <input type="hidden"  name="role" value="<?php echo $user->role ?>">
                                    <label for="role">Role</label>
                                </div>
                            </div>
                            
                        </div>
                    <?php endif ?>
                    <?php if ($user->role == 'teach'): ?>
                        <div class="col-md-12 col-xs-12 push-20">
                            <div class="col-md-6  push-20">
                                <div class="form-material">
                                    <input class="form-control" type="text" id="iban" name="iban" maxlength="20" value="<?php echo $user->iban ?>" >
                                    <label for="iban">IBAN</label>
                                </div>
                            </div>
                            <div class="col-md-6  push-20">
                                <div class="form-material">
                                    <input class="form-control" type="text" id="ss" name="ss" maxlength="18" value="<?php echo $user->ss ?>">
                                    <label for="ss">Seg. soc</label>
                                </div>
                            </div>
                            
                        </div>
                        
                        <?php $coachRates = \App\CoachRates::where('id_user', $user->id)->get(); ?>

                        <?php if (count($coachRates) > 0): ?>
                            <div class="col-md-12 col-xs-12 push-20">
                                <div class="col-md-6  push-20">
                                    <div class="form-material">
                                        <input class="form-control" type="text" id="salario_base" name="salario_base" maxlength="20" value="<?php echo $coachRates[0]->salary ?>" >
                                        <label for="salario_base">Salario Base</label>
                                    </div>
                                </div>
                                <div class="col-md-6  push-20">
                                    <div class="form-material">
                                        <input class="form-control" type="text" id="ppc" name="ppc" maxlength="18" value="<?php echo $coachRates[0]->ppc ?>">
                                        <label for="ppc">P.P.C</label>
                                    </div>
                                </div>
                                
                            </div>
                        <?php else: ?>
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
                    <?php endif ?>
            <?php else: ?>
                    <div class="col-md-12 col-xs-12 push-20">
                        <div class="col-md-6  push-20">
                            <div class="form-material">
                                <input class="form-control" type="text" id="name" name="name" required value="<?php echo $user->name ?>" disabled>
                                <label for="name">Nombre</label>
                            </div>
                        </div>
                        <div class="col-md-6  push-20">
                            <div class="form-material">
                                <input type="text" id="email" name="email" required value="<?php echo $user->email ?>">
                                <label for="email">E-mail</label>
                            </div>
                        </div>

                        <div style="clear:both;"></div>

                        <div class="col-md-6  push-20">
                            <div class="form-material">
                                <input class="form-control" type="number" id="telefono" name="telefono" required maxlength="9" value="<?php echo $user->telefono ?>" disabled>
                                <label for="telefono">Teléfono</label>
                            </div>
                        </div>
                    </div>
            <?php endif ?>
            <div class="col-md-12 col-xs-12 push-20 text-center">
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Actualizar
                </button>
            </div>
        </form>
    	
    </div>
</div>


<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script>
    jQuery(function () {
        App.initHelpers(['datepicker', 'select2','summernote','ckeditor']);
    });


</script>