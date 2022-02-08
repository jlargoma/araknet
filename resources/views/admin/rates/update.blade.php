@extends('layouts.admin-master')

@section('title') Horario - Araknet HTS @endsection

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
                <li>Tarifas y Bonos</li>
                <li><a class="link-effect" href="{{ url('/admin/tasas-bonos/tarifas')}}">Tarifas</a></li>
                <li>Editar</li>                
            </ol>
        </div>
    </div>
</div>
<div class="content content-full bg-gray-lighter">
	<div class="row">
	    <div class="col-md-12 push-30 push-t-30">
	        <div class="col-md-12">
			    <div class="row">
			        <div class="block col-md-6 col-md-offset-3">
			        	<div class="col-xs-12 col-md-12 push-20">
			        		<h3 class="text-center">
			        			Formulario para editar un regalo
			        		</h3>
			        	</div>
			        	<div class="clear"></div>
			        	<form class="form-horizontal" action="{{ url('/admin/tarifas/update') }}" method="post">
			        		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			        		<input type="hidden" name="id" value="{{ $rate->id }}">
			                <div class="col-md-12 col-xs-12 push-20">
			                    <div class="col-md-6  push-20">
			                        <div class="form-material">
			                            <input class="form-control" type="text" id="name" name="name"  value="<?php echo $rate->name; ?>" required>
			                            <label for="name">Nombre de Tarifa</label>
			                        </div>
			                    </div>
			                     <div class="col-md-6  push-20">
			                        <div class="form-material">
			                            <select class="js-select2 form-control" id="type" name="type" style="width: 100%;" data-placeholder="Tipo de tarifa..." required>
		                                    <option></option>
			                                <?php foreach ($typesRate as $typeRate): ?>
			                                	<option value="<?php echo $typeRate->id ?>" <?php if($typeRate->id == $rate->type){echo "selected";} ?>>
			                                		<?php echo $typeRate->name ?>
			                                	</option>
			                                <?php endforeach ?>
		                            	</select>
		                            	<label for="name">Bloque Asignado</label>
			                        </div>
			                    </div>
			                </div>
			                <div class="col-md-12 col-xs-12 push-20">
			                    <div class="col-md-6  push-20">
			                        <div class="form-material">
			                            <input class="form-control" type="number" id="max_pax" name="max_pax" value="<?php echo $rate->max_pax; ?>" required>
			                            <label for="nombre">Numero maximo de clases</label>
			                        </div>
			                    </div>
			                    <div class="col-md-6  push-20">
			                        <div class="form-material">
			                            <input class="form-control" type="number" id="price" name="price" value="<?php echo $rate->price; ?>" required>
			                            <label for="nombre">Precio del Bono</label>
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