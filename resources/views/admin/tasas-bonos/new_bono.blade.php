@extends('layouts.admin-master')

@section('title') Nuevo horario - Araknet HTS @endsection

@section('externalScripts')

<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
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
	                <li><a class="link-effect" href="{{ url('/admin/tasas-bonos/bonos')}}">Bonos</a></li>
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
				        			Formulario para crear un Bono
				        		</h3>
				        	</div>
				        	<div class="clear"></div>
				        	<form class="form-horizontal" action="{{ url('/admin/tasas-bonos/create_bono') }}" method="post">
				        		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				                <div class="col-md-12 col-xs-12 push-20">
				                    <div class="col-md-6  push-20">
				                        <div class="form-material">
				                            <input class="form-control" type="text" id="nombre" name="nombre" required>
				                            <label for="nombre">Nombre de Bono</label>
				                        </div>
				                    </div>
				                    <div class="col-md-6  push-20">
				                        <div class="form-material">
				                            <select class="js-select2 form-control" id="tipo" name="tipo" style="width: 100%;" data-placeholder="tipo" required >
		                                    <option></option>
			                                <?php for ($i=0; $i < 2; $i++): ?>
			                                	<?php if ($i == 0): ?>
			                                		<option value="<?php echo $i; ?>">
				                                       Normal
				                                   </option>
			                                	<?php elseif ($i == 1): ?>
			                                		<option value="<?php echo $i; ?>">
				                                       Especial
				                                   </option>
			                                	<?php endif ?>
			                                <?php endfor; ?>
			                            </select>
				                            <label for="nombre">Tipo</label>
				                        </div>
				                    </div>
				                </div>
				                <div class="col-md-12 col-xs-12 push-20">
				                    <div class="col-md-6  push-20">
				                        <div class="form-material">
				                            <select class="js-select2 form-control" id="season" name="season" style="width: 100%;" data-placeholder="season" required >
		                                    <option></option>
		                                    <?php $sesiones = [ 0 => '1 Sesion', 1 => '5 Sesiones',2 => '10 Sesiones',3 => '20 Sesiones',]; ?>
			                                <?php for ($i=0; $i < count($sesiones); $i++): ?>
		                                		<option value="<?php echo $i; ?>">
			                                       	<?php echo $sesiones[$i];  ?>
			                                   	</option>
			                                <?php endfor; ?>
			                            </select>
				                            <label for="nombre">Sesiones</label>
				                        </div>
				                    </div>
				                    <div class="col-md-6  push-20">
				                        <div class="form-material">
				                            <input class="form-control" type="number" id="price" name="price" required>
				                            <label for="price">Precio</label>
				                        </div>
				                    </div>
				                </div>
				                <!-- <input type="text" name="resultadoBusqueda" id="resultadoBusqueda"> -->
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