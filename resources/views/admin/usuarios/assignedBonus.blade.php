@extends('layouts.admin-master')

@section('title') Horarios - Evolutio HTS @endsection

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
                <li>Asignar</li>
                <li>Bonos a usuarios</li>
            </ol>
        </div>
    </div>
</div>
<div class="content content-full bg-gray-lighter">
	<div class="row">
	    <div class="col-md-12 push-30 push-t-30">
	        <div class="col-md-12">
			    <div class="row">
			    	<div class="col-md-6 col-md-offset-3  bg-white" style="padding: 20px;">
			    		<div class="col-ms-12 not-padding-mobile">
			    			<form class="form-horizontal" action="{{ url('/admin/usuarios/asignarBono')}}" method="post">
			    				<div class="form-group">
				    				<div class="col-xs-12 col-md-6">
				    					<div class="form-material">
				    						<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				                            <select class="js-select2 form-control" id="id_user" name="id_user" style="width: 100%;" data-placeholder="Seleccione un usuario" required >
			                                    <option></option>
				                                <?php foreach ($users as $user): ?>
				                                	<option value="<?php echo $user->id ?>">
				                                		<?php echo $user->name ?>
				                                	</option>
				                                <?php endforeach ?>
				                            </select>
				                            <label for="id_user">Usuario</label>
				                        </div>
				    				</div>
				    			</div>
				    			<div class="form-group">
				    				<div class="col-xs-12">
				    					<label>Bonos disponibles</label>
				    				</div>
				    				<div class="col-xs-12">
				    					<?php foreach ($bonus as $key => $bono): ?>
				    						<div class="col-xs-12 col-md-4">
					    						<label class="css-input css-checkbox css-checkbox-primary">
					    							<input name="bonus[<?php echo $key ?>]" type="checkbox" value="<?php echo $bono->id ?>"><span></span> <?php echo $bono->name; ?>
					    						</label>
				    						</div>
				    					<?php endforeach ?>
				    				</div>
				    			</div>
				    			<div class="form-group">
				    				<div class="col-xs-12">
				    					<button class="btn btn-success" type="submit">
				    						Guardar
				    					</button>
				    				</div>
				    			</div>
			    			</form>
			    		</div>
			    	</div>
			    </div> 
            </div>
	    </div>
	</div>
</div>



@endsection


@section('scripts')
	<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
	<script>
	    jQuery(function () {
	        App.initHelpers(['select2']);
	    });
	</script>
@endsection