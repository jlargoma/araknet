@extends('layouts.admin-master')

@section('title') Liquidacion {{ $user->name}} - Evolutio HTS @endsection

@section('externalScripts')
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('content')
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<div class="content content-boxed bg-white">
	<div class="row">
	   	<div class="col-xs-12">
			<div class="col-md-3 col-xs-12 text-center pull-right push-20">
				<div class="col-xs-12 not-padding push-10">
					<h4 class="push-10 text-justify">Generar liquidación:</h4>
					<select id="date" class="form-control">
						<?php $fecha = $date->copy()->startOfYear(); ?>
						<?php for ($i=1; $i <= 12; $i++): ?>
							<?php if( $date->format('n') == $i ){ $selected = "selected"; }else{$selected = "";} ?>
							<option value="<?php echo $fecha->startOfMonth()->format('Y-m-d'); ?>" <?php echo $selected ?>>
								<?php echo $fecha->formatLocalized('%B %Y'); ?> 
							</option>
							<?php $fecha->addMonth(); ?>
						<?php endfor; ?>
					</select>
				</div>
				<div class="col-xs-6 col-xs-offset-3 not-padding">
					<a class="btn btn-primary" href="{{ url('/admin/facturacion/enviar-liquidacion') }}/<?php echo $user->id ?>/<?php echo $date->copy()->format('Y-m') ?>">
						<i class="fa fa-envelope" aria-hidden="true"></i> Enviar
					</a>
				</div>
			</div>

			<div style="clear: both;"></div>


	   		<div class="col-xs-12 push-20">
	   			<h2 class="text-center font-w300">
	   				Liquidación de <span class="text-green font-w600"><?php echo strtoupper($date->copy()->formatLocalized('%B')) ?></span>
	   			</h2>
	   		</div>
	   		<div class="col-xs-12">
	   			<div class="col-xs-12 col-md-2">
	   				<div class="col-xs-12">
	   					<img src="{{ asset('admin-css/assets/img/profile.png') }}" class="img-responsive" style="max-width: 105px;" />
	   				</div>
	   			</div>
	   			<div class="col-xs-12 col-md-10">
	   				<div class="col-xs-12">
	   					<h2 class="font-w300 text-left">
	   						<span class="font-w600">{{ $user->name }}</span>
	   					</h2>
	   					<p class="font-s18 text-left">
	   						{{ $user->email }}
	   					</p>
	   				</div>
	   			</div>
	   		</div>
	   		<div class="col-xs-12">
	   			<table class="table table-borderless table-striped table-vcenter">
	   				<thead>
	   					<tr>
	   						<th class="text-center">#</th>
	   						<th class="text-left">Concepto</th>
	   						<th class="text-right">Total</th>
	   					</tr>
	   				</thead>
	   				<tbody>
	   					<?php 
	   						$i = 1; 
	   						$total = 0;
	   					?>
	   					<tr>
	   						<td class="text-center"><strong><?php echo $i ?></strong></td>
	   						<td class="text-left font-s20" style="text-transform: uppercase;">Salario Base</td>
	   						<td class="text-right font-s20"><strong><?php echo $taxCoach[0]->salary; ?>€</strong></td>
	   					</tr>
						<?php foreach ($pagosClase as $key => $pago): ?>
	   						<?php $i++ ?>
	   						<tr>
	   							<td class="text-center"><strong><?php echo $i ?></strong></td>
	   							<td class="text-left font-s18">
	   								<a class="desgloce-clase" style="cursor: pointer;" data-toggle="modal" data-target="#modal-desgloce-clase" data-idClase="<?php echo $pago[0]->id_class; ?>" data-idCoach="<?php echo $user->id ?>">
	   									<?php echo strtoupper(str_replace('-', ' ', $key)) ?>
	   								</a>
	   							</td>
	   							<td class="text-right font-s20"><strong><?php echo $totalClase[$key] ?>€</strong></td>
	   						</tr>
	   						<?php $total += $totalClase[$key]; ?>
	   					<?php endforeach ?>
	   						<?php $total += $taxCoach[0]->salary; ?>
	   						
	   					
	   					<tr class="success">
	   						<td colspan="2" class="text-right text-uppercase"><strong>Total:</strong></td>
	   						<td class="text-right font-s24"><strong><?php echo $total ?>€</strong></td>
	   					</tr>
	   				</tbody>
	   			</table>
	   		</div>

	   		<div class="col-xs-12">
	   			<?php $year = $date->copy()->startOfYear(); ?>
	   			<?php $year2 = $date->copy()->startOfYear(); ?>
	   			<table class="table table-bordered table-striped table-header-bg">
		    		<thead>
		    			<tr>
		    				<th class="text-center"> </th>
			    			<?php for ($i = 1; $i <= 12; $i++): ?>
			    				<th class="text-center"><?php echo $year2->formatLocalized('%B'); ?></th>
								<?php $year2->addMonth(); ?>
			    			<?php endfor; ?>
		    				<th class="text-center">Total ANUAL</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			<!-- LIQUIDACIONES -->
		    			<tr>
		    				<?php $totalAnualUser = 0; ?>
	    					<td class="text-center">
	    						<b>PAGOS MENS.</b>
	    					</td>
			    			<?php for ($i = 1; $i <= 12; $i++): ?>
								<td class="text-center">
									<strong><?php echo $user->getTotalLiquidation($user->id, $year);?> €</strong>
								</td>
								<?php  $totalAnualUser += $user->getTotalLiquidation($user->id, $year); ?>
								<?php $year->addMonth(); ?>
		    				<?php endfor; ?>
		    				<td class="text-center">
		    					<b><?php echo $totalAnualUser ?>€</b>
		    				</td>
	    				</tr>

	    				<tr>
	    					<td colspan="13" class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2;">
	    						<h2 class="text-center">TOTAL DE GASTO ANUAL </h2>
	    					</td>
	    					<td class="text-center">
	    						<h2 class="text-center">
	    							<?php echo $totalAnualUser ;//+ $totAnualBonoUser + $totAnualBonoEspUser?>€
	    						</h2>
	    					</td>
	    				</tr>
		    		</tbody>
		    	</table>
	   		</div>
	   	</div>
	</div>
</div>

<div class="modal fade in" id="modal-desgloce-clase" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-popin modal-lg" style="width: 70%;">
		<div class="modal-content">
			<div class="block block-themed block-transparent remove-margin-b" id="content-desgloce">
				
			</div>
			<div class="modal-footer">
				<button class="btn btn-sm btn-danger" type="button" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
	<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="{{ asset('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>


	<script type="text/javascript">
		$(document).ready(function() {
			$('.desgloce-clase').click(function(event) {
				var id_clase = $(this).attr('data-idClase');
				var id_user = $(this).attr('data-idCoach');
				$.get('/admin/facturacion/getDesgloceClase', {id_clase: id_clase, id_user: id_user}, function(data) {
					$('#content-desgloce').empty().html(data);
				});
			});

			$('#date').change(function(event) {
				var date = $(this).val();
				var id_user = '<?php echo $user->id ?>';
				
				window.location = '/admin/facturacion/generar-liquidacion/'+id_user+'/'+ date;

			});


		});
	</script>


@endsection