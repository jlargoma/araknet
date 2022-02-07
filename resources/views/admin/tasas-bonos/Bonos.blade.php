@extends('layouts.admin-master')

@section('title') Bonos - Evolutio HTS @endsection

@section('externalScripts')
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('content')
	<div class="content bg-gray-lighter">
	    <div class="col-xs-12">
	        <div class="col-sm-5 text-left hidden-xs">
	            <ol class="breadcrumb push-10-t">
	                <li><a class="link-effect" href="{{ url('/admin')}}">Admin</a></li>
	                <li>Tarifas y Bonos </li>
	                <li>Bonos </li>
	            </ol>
	        </div>
	    </div>
	</div>
	<div class="content content-full bg-gray-lighter">
		<div class="row">
		    <div class="col-md-12 push-30 push-t-30">
		    	<div class="col-xs-12">
		    		<div class="col-sm-6 col-md-3">
	                    <a class="block block-link-hover3 text-center" href="{{url('/admin/tasas-bonos/new_bono')}}" style="border :1px solid #d4d4d4">
	                        <div class="block-content block-content-full">
	                            <div class="h1 font-w700 text-success"><i class="fa fa-plus"></i></div>
	                        </div>
	                        <div class="block-content block-content-full block-content-mini bg-gray-lighter text-success font-w600">NUEVO BONO</div>
	                    </a>
	                </div>
	                <div class="col-sm-6 col-md-3">
	                    <div class="block block-link-hover3 text-center" style="border :1px solid #d4d4d4">
	                        <div class="block-content block-content-full">
	                            <div class="h1 font-w700 text-success"><i class="fa fa-users"></i> <?php echo count($bonos) ?></div>
	                        </div>
	                        <div class="block-content block-content-full block-content-mini bg-gray-lighter text-success font-w600">BONO</div>
	                    </div>
	                </div>
		    	</div>
    	        <div class="col-xs-12 col-md-7">
    			    <div class="row">
    			        <div class="col-md-12">
    			            <?php if ( count($bonos) > 0): ?>
    			            	<table class="table table-bordered table-striped js-dataTable-full table-header-bg">
    			                    <thead>
    			                        <tr>
                                            <th class ="text-center hidden-xs hidden-sm" style="width: 25%">id</th>
                                            <th class ="text-center" style="width: 25%">Nombre</th>
                                            <th class ="text-center"> Tipo</th>
                                            <th class ="text-center hidden-xs hidden-sm"> Seasons</th>
                                            <th class ="text-center"> Precio</th>
                                            <th class ="text-center" style="width: 10%;">Acciones</th>
    			                        </tr>
    			                    </thead>
    			                    <tbody>
                                    <?php $sesiones = [ 0 => '1 Sesion', 1 => '5 Sesiones',2 => '10 Sesiones',3 => '20 Sesiones',]; ?>
    			                    <?php foreach ($bonos as $bono): ?>
    			                       <tr>
    			                       		<td class="text-center hidden-xs hidden-sm"> 
    			                       			<?php echo $bono->id; ?>
    			                       		</td>
    			                       		<td class="text-center"> 
    			                       			<b><?php echo $bono->name; ?></b>
    			                       		</td>
                                            <td class="text-center">
                                                <?php if ($bono->type == 0): ?>
                                                    <span class="label label-primary">Normal</span>
                                                <?php else: ?>
                                                    <span class="label label-success">Especial</span>
                                                    
                                                <?php endif ?>
                                            </td>
                                            <td class="text-center hidden-xs hidden-sm">
                                                <b><?php echo $sesiones[$bono->seasons]; ?></b>
                                            </td>
                                            <td class="text-center">
                                                <b><?php echo $bono->price; ?>€</b>
                                            </td>
    			                       		<td class="text-center">
    			                       			<div class="btn-group">
    			                       				<a href="{{ url('/admin/tasas-bonos/actualizar-bono')}}/<?php echo $bono->id ?>" class="btn btn-md btn-primary" type="button" data-toggle="tooltip" title="" data-original-title="Editar Bono"><i class="fa fa-pencil"></i></a>
    			                       				
    	                                            <a href="{{ url('/admin/tasas-bonos/delete-bono')}}/<?php echo $bono->id ?>" class="btn btn-md btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar Bono"><i class="fa fa-times"></i></a>
    	                                        </div>
    			                       		</td>
    			                       </tr>
    			                    <?php endforeach ?>
    			                    </tbody>
    			                </table>
    			            <?php else: ?>
        	            		<div class="col-xs-12">
        	            			<h2 class="text-muted font-w200">
        		            			No hay <span class="font-w600">Bonos</span> , por favor cree una nueva 
        		            			<a href="{{url('/admin/tasas-bonos/new_bono')}}">aquí</a>
        		            		</h2>
        	            		</div>
    			            <?php endif ?>
    			        </div>
    			    </div> 
                </div>
		    </div>
		</div>
	</div>

@endsection


@section('scripts')
	<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="{{ asset('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>
@endsection