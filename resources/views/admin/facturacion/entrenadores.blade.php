@extends('layouts.admin-master')

@section('title') Horarios - Araknet HTS @endsection

@section('externalScripts')
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('content')
<div class="content bg-gray-lighter">
    <div class="col-xs-12">
        <div class="col-sm-5 text-left hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li><a class="link-effect" href="{{ url('/admin')}}">Admin</a></li>
                <li>Facturación entrenadores</li>
            </ol>
        </div>
    </div>
</div>
<div class="content content-full bg-gray-lighter">
	<div class="row">
	    <div class="col-md-12 push-30 push-t-30">
	    	<div class="col-xs-12">
	    		<div class="col-sm-6 col-md-3">
                    <a class="block block-link-hover3 text-center" href="{{url('/admin/facturacion/entrenadores/new')}}" style="border :1px solid #d4d4d4">
                        <div class="block-content block-content-full">
                            <div class="h1 font-w700 text-success"><i class="fa fa-plus"></i></div>
                        </div>
                        <div class="block-content block-content-full block-content-mini bg-gray-lighter text-success font-w600">NUEVA TASA</div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="block block-link-hover3 text-center" style="border :1px solid #d4d4d4">
                        <div class="block-content block-content-full">
                            <div class="h1 font-w700 text-success"><i class="fa fa-users"></i> <?php echo count($customers) ?></div>
                        </div>
                        <div class="block-content block-content-full block-content-mini bg-gray-lighter text-success font-w600">Entrenadores</div>
                    </div>
                </div>
	    	</div>
	        <div class="col-xs-12">
			    <div class="row">
		            <?php if ( count($taxes) > 0): ?>
		            	<table class="table table-bordered table-striped js-dataTable-full table-header-bg">
		                    <thead>
		                        <tr>
		                            <th class="text-center hidden-xs hidden-sm">id</th>
		                            <th class="text-center">Nom<span class="hidden-xs hidden-sm">bre</span></th>
		                            <th class="text-center hidden-xs hidden-sm">Email</th>
		                            <th class="text-center hidden-xs hidden-sm">Tarifa</th>
		                            <th class="text-center">Tel<span class="hidden-xs hidden-sm">éfono</span></th>
		                            <th class="text-center">Salario <span class="hidden-xs hidden-sm">Base</span></th>
		                            <th class="text-center">PPC</th>
		                            <th class="text-center" style="width: 10%;">Acciones</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    <?php foreach ($customers as $key => $customer): ?>
		                       <tr>
		                       		<td class="text-center hidden-xs hidden-sm"> 
		                       			<?php echo $customer->id; ?>
		                       		</td>
		                       		<td class="text-center"> 
		                       			<b><?php echo $customer->name; ?></b>
		                       		</td>
		                       		<td class="text-center hidden-xs hidden-sm"> 
		                       			<b><?php echo $customer->email; ?></b>
		                       		</td>
		                       		<td class="text-center hidden-xs hidden-sm"> 
	                       				<b><?php echo $customer->tarifa->nombre; ?></b>
		                       		</td>
		                       		<td class="text-center"> 
		                       			<a href="tel:<?php echo $customer->phone ?>">
		                       				<?php echo substr($customer->phone, 0, 4); ?>...
		                       			</a>
		                       		</td>
		                       		<td class="text-center"> 
		                       			<?php 
		                       				$taxesCoach = \App\Taxes_coach::where('customer_id',$customer->id)->get();

		                       				if ( count($taxesCoach) > 0) {
		                       					
		                       					if ($taxesCoach[0]->salario_base != 0) {
		                       						echo $taxesCoach[0]->salario_base." €";
		                       					}else{
		                       						echo "Sin asignar tarifa";
		                       					}
		                       				}else{
		                       					echo "Sin asignar tarifa";
		                       				}
		                       			 ?>
		                       		</td>
		                       		<td class="text-center">
	                       				<?php 
	                       					if ( count($taxesCoach) > 0) {
		                       					echo $taxesCoach[0]->ppc." €";
		                       				}else{
		                       					echo "Sin asignar ppc";
		                       				}
	                       				?>
		                       		</td>
		                       		<td class="text-center">
		                       			<div class="btn-group">
											<a href="{{ url('/admin/facturacion/generar-liquidacion/')}}/<?php echo $customer->id ?>" class="btn btn-xs btn-warning" type="button" data-toggle="tooltip" title="" data-original-title="Generar liquidación "><i class="fa fa-file-text"></i></a>

		                       				<a href="{{ url('/admin/facturacion/entrenador/actualizar/')}}/<?php echo $taxesCoach[0]->id ?>" class="btn btn-xs btn-primary" type="button" data-toggle="tooltip" title="" data-original-title="Editar Entrenador"><i class="fa fa-pencil"></i></a>
		                       				
                                            <a href="{{ url('/admin/facturacion/entrenador/delete')}}/<?php echo $taxesCoach[0]->id ?>" class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar tarifa de entrenador"><i class="fa fa-times"></i></a>
                                        </div>
		                       		</td>
		                       </tr>
		                    <?php endforeach ?>
		                    </tbody>
		                </table>
		            <?php else: ?>
	            		<div class="col-xs-12">
	            			<h2 class="text-muted font-w200">
		            			No hay <span class="font-w600">tarifas</span> para ningun <span class="font-w600">entrenador</span>, por favor cree una nueva 
		            			<a href="{{url('/admin/facturacion/entrenadores/new')}}">aquí</a>
		            		</h2>
	            		</div>
		            <?php endif ?>
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