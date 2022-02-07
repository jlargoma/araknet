@extends('layouts.admin-master')

@section('title') Actualizar bono asignados a $user->name - Evolutio HTS @endsection

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
			    			<form class="form-horizontal" action="{{ url('/admin/usuarios/updateAssignedBono')}}" method="post">
			    				<div class="form-group">
				    				<div class="col-xs-12 col-md-6">
				    					<div class="form-material">
				    						<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				    						<input type="hidden" name="id_user" value="<?php echo $user->id ?>">
				                            <h3><?php echo $user->name ?></h3>
				                        </div>
				    				</div>
				    			</div>
				    			<div class="form-group">
				    				<div class="col-xs-12">
				    					<label>Bonos disponibles</label>
				    				</div>
				    				<div class="col-xs-12">
				    					<?php foreach ($allBonus as $key => $bono): ?>
											<?php 
												$selected = false;
												$firstDayMonth = $date->copy()->startOfMonth()->format('Y-m-d');
												$lastDayMonth  = $date->copy()->endOfMonth()->format('Y-m-d');	

												$issetBono = \App\BonosForUsers::where('id_bono', $bono->id)
																				->where('id_user', $user->id)
																				->where('created_at' ,'>', $firstDayMonth)
																				->where('created_at' ,'<', $lastDayMonth)
																				->get();
												if ( count($issetBono) ) {
													$selected = true;
												}
											?>

				    						<div class="col-xs-12 col-md-4">
					    						<label class="css-input css-checkbox css-checkbox-primary">
					    							<input name="bonus[<?php echo $key ?>]" type="checkbox" value="<?php echo $bono->id ?>" <?php if($selected){ echo "checked";} ?>><span></span> <?php echo $bono->name; ?>
					    						</label>
				    						</div>
				    					<?php endforeach ?>
				    				</div>
				    			</div>
				    			<div class="form-group">
				    				<div class="col-xs-12">
				    					<button class="btn btn-success" type="submit">
				    						Actualizar
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