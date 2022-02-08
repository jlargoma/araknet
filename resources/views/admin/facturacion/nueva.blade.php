@extends('layouts.admin-master')

@section('title') Nuevo horario - Araknet HTS @endsection

@section('externalScripts')
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
@endsection

@section('content')
<div class="content bg-gray-lighter">
    <div class="col-xs-12">
        <div class="col-sm-5 text-left hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li><a class="link-effect" href="{{ url('/admin')}}">Admin</a></li>
                <li><a class="link-effect" href="{{ url('/admin/facturacion/entrenadores')}}">Facturacion</a></li>
                <li>Nuevo</li>
            </ol>
        </div>
    </div>
</div>
<div class="content content-full bg-gray-lighter">
	<div class="row">
	    <div class="col-md-12 push-30 push-t-30">
	        <div class="col-md-12">
			    <div class="row">
			        <div class="block col-md-6 col-md-offset-3 bg-white" style="padding: 20px;">
			        	<div class="col-xs-12 col-md-12 push-20">
			        		<h3 class="text-center">
			        			Formulario para añadir una nueva Tasa
			        		</h3>
			        	</div>
			        	<div class="clear"></div>
			        	<form class="form-horizontal" action="{{ url('/admin/facturacion/create') }}" method="post">
			        		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			                <div class="col-md-12 col-xs-12 push-20">
			                    
			                    <div class="col-md-6  push-20">
			                        <div class="form-material">
			                            <select class="js-select2 form-control" id="id_user" name="id_user" style="width: 100%;" data-placeholder="Entrenador..." required >
		                                    <option></option>
			                                <?php foreach ($users as $user): ?>
			                                	<option value="<?php echo $user->id ?>">
			                                		<?php echo $user->name ?>
			                                	</option>
			                                <?php endforeach ?>
			                            </select>
			                            <label for="name">Nombre</label>
			                        </div>
			                    </div>
			                    <div class="col-md-6  push-20">
			                        <div class="form-material">
			                            <input class="form-control" type="number" id="salario_base" name="salario_base" required>
			                            <label for="salario_base">Salario Base</label>
			                        </div>
			                    </div>

			                    <div class="clear"></div>

			                    <div class="col-md-6  push-20">
			                        <div class="form-material">
			                            <input class="form-control" type="number" id="ppc" name="ppc" required maxlength="9">
			                            <label for="ppc">Precio por clase</label>
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
			    </div> 
            </div>
	    </div>
	</div>
</div>



@endsection


@section('scripts')
	<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
	<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
	<script>
	    jQuery(function () {
	        App.initHelpers(['datepicker', 'select2','summernote','ckeditor']);
	    });
	</script>
@endsection