<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">


<div class="row">
    <div class="block col-md-12 bg-white">
    	<div class="col-xs-12 col-md-12 push-20">
    		<h3 class="text-center">
    			DATOS PERSONALES
    		</h3>
    	</div>
        <div class="col-md-12 col-xs-12 push-20">
            
            <div class="col-md-5  push-20">
                <div class="form-material">
                    <input class="form-control" type="text" id="name" name="name" required value="<?php echo $date->user->name ?>">
                    <label for="name">Nombre</label>
                </div>
            </div>
            <div class="col-md-5  push-20">
                <div class="form-material">
                    <input type="hidden" id="email" name="email" required value="<?php echo $date->user->email ?>">
                    <label for="email">E-mail</label>
                    <p class="push-0"><?php echo $date->user->email ?></p>
                </div>
            </div>

            <div class="col-md-2  push-20">
                <div class="form-material">
                    <input class="form-control" type="number" id="telefono" name="telefono" required maxlength="9" value="<?php echo $date->user->telefono ?>">
                    <label for="telefono">Teléfono</label>
                </div>
            </div>
        </div>
    	<div class="clear"></div>
        <div class="col-xs-12 col-md-12 push-20">
            <h3 class="text-center">
                DATOS MEDICOS
            </h3>
        </div>
        <div class="col-md-12 col-xs-12 push-20">
            <form class="form-horizontal" action="{{ url('/admin/usuarios/update') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="id" value="{{ $date->user->id }}">

                <div class="col-md-2 col-md-offset-1 push-20">
                    <div class="form-material">
                        <input class="form-control" type="number" id="age" name="age" required value="">
                        <label for="age">Edad</label>
                    </div>
                </div>

                <div class="col-md-2  push-20">
                    <div class="form-material">
                        <input class="form-control" type="number" id="weight" name="weight" required value="">
                        <label for="weight">Peso Actual</label>
                    </div>
                </div>

                <div class="col-md-2  push-20">
                    <div class="form-material">
                        <input class="form-control" type="number" id="height" name="height" required maxlength="9" value="">
                        <label for="height">Altura</label>
                    </div>
                </div>

                <div class="col-md-2  push-20">
                    <div class="form-material">
                        <input class="form-control" type="number" id="objective" name="objective" required maxlength="9" value="">
                        <label for="objective">Objetivo</label>
                    </div>
                </div>

                <div class="col-md-2  push-20">
                    <div class="form-material">
                        <input class="form-control" type="number" id="lastDate" name="lastDate" required maxlength="9" value="">
                        <label for="lastDate">Ultima cita</label>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="col-md-3 col-md-offset-2  push-20">
                    <div class="form-material">
                        <input class="form-control" type="number" id="rate" name="rate" required maxlength="9" value="">
                        <label for="rate">Cuota asociada</label>
                    </div>
                </div>

                <div class="col-md-3  push-20">
                    <div class="form-material">
                        <input class="form-control" type="number" id="totalDates" name="totalDates" required maxlength="9" value="<?php echo count(\App\Dates::where('id_user', $date->id_user)->get()) ?>">
                        <label for="totalDates">Consultas realilzadas</label>
                    </div>
                </div>

                <div class="col-md-3  push-20">
                    <div class="form-material">
                        <input class="form-control" type="number" id="basal" name="basal" required maxlength="9" value="">
                        <label for="basal">Metabolismo Basal</label>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="col-md-12  push-20">
                   <div class="form-material">
                       <textarea class="form-control" type="text" id="comment" name="comment" required maxlength="500" value="<?php echo $date->check->comment ?>" rows="9"></textarea>
                       <label for="comment">Comentarios</label>
                   </div>
               </div>
            </form>
        </div>
    </div>
</div>


<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script>
    jQuery(function () {
        App.initHelpers(['datepicker', 'select2','summernote','ckeditor']);
    });
</script>