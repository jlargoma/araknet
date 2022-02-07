<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<?php use \Carbon\Carbon; ?>
@extends('layouts.admin-master')

@section('externalScripts')
	<script type="text/javascript" src="{{ asset('/admin-css/assets/js/chart.js')}}"></script>
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('title') 

@endsection

@section('content')
<style type="text/css">
	.header-navbar-fixed #main-container {
	    padding-top: 0px;
	}
</style>
<div class="bg-white">
 	<section class="content content-full">
 		<div class="row">

			@include('admin.contabilidad._button-contabiliad')

		</div> 
		<div class="row">
			<div class="col-xs-12 col-md-11">
				<h2 class="text-center font-w300">
					CUENTA CON SOCIOS Y ADMINISTRADORES
				</h2>
			</div>	
			<div class="col-xs-12 col-md-1">
				<select class="form-control" id="yearSelector">
					<?php $yearSelector = Carbon::now()->subYears(1); ?>
					<?php for ($i = 1;$i <= 5; $i++): ?>
						<?php if( $year == $yearSelector->copy()->formatlocalized('%Y') ){ $selected = "selected"; }else{ $selected = ""; } ?>
						<option value="<?php echo $yearSelector->copy()->formatlocalized('%Y') ?>" <?php echo $selected ?>>
							<?php echo $yearSelector->copy()->formatlocalized('%Y') ?>	
						</option>
					<?php $yearSelector->addYears(1); ?>
					<?php endfor ?>

				</select>
			</div>		
		</div>
		<br>
		<div class="row">
		<?php if (count($aportesBanco) > 0): ?>
			<table class="table table-bordered table-striped table-header-bg no-footer">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Concepto</th>
						<th class="text-center type" style="width: 250px;">Tipo</th>
						<th class="text-center">Debe</th>
						<th class="text-center">Haber</th>
						<th class="text-center">Comentario</th>
					</tr>
				</thead>	
				<tbody>
					<?php foreach ($aportesBanco as $banco): ?>
						<tr>
							<td><?php echo $banco->id ?></td>
							<td><?php echo Carbon::createFromFormat('Y-m-d',$banco->date)->format('d-m-Y') ?></td>
							<td><?php echo $banco->concept ?></td>
							<td><?php echo $banco->type ?></td>
							<?php if ($banco->typePayment == "APORTACION SOCIOS"): ?>
								<td><?php echo number_format($banco->import,2,',','.') ?> € </td>
								<td></td>
							<?php else: ?>
								<td></td>
								<td><?php echo number_format($banco->import,2,',','.') ?> € </td>
							<?php endif ?>
							<td><?php echo $banco->comment ?></td>
						</tr>
					<?php endforeach ?>
					<tr>
						<td colspan="4" class="text-right bg-primary text-white">Total</td>
						<td><?php echo number_format($totalAportacion,2,',','.') ?> €</td>
						<td><?php echo number_format($totalDividendo,2,',','.') ?> €</td>
					</tr>
				</tbody>			
			</table>
			<div class="col-md-3">
				<table class="table table-bordered table-striped table-header-bg no-footer">
					<thead>
						<th>Socio</th>
						<th>Importe</th>
						<th>Porcentaje</th>
					</thead>
					<tbody>
						<tr>
							<td>Jorge</td>
							<td><?php echo $totalJorge ?></td>
							<?php if ($totalAportacion > 0): ?>
								<td><?php echo number_format(100-(($totalAportacion - $totalJorge)/$totalAportacion)*100,2,',','.')?>% </td>
							<?php endif ?>
							
						</tr>
						<tr>
							<td>Victor</td>
							<td><?php echo $totalVictor ?></td>
							<td>
								<?php if ($totalAportacion > 0): ?>
									<?php echo number_format(100-(($totalAportacion - $totalVictor)/$totalAportacion)*100,2,',','.')?>% </td>
								<?php endif ?>
							
							
						</tr>
						<tr>
							<td class="text-right bg-primary text-white">Total</td>
							<td colspan="2"><?php echo $totalAportacion ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		<?php else: ?>
			No hay aportes en esta cuenta
		<?php endif ?>
			
		</div>
		
	</section>
</div>

<div class="modal in" id="modal-csv" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="block block-themed block-transparent remove-margin-b">
				<div class="block-header bg-primary-dark">
					<ul class="block-options">
						<li>
							<button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
						</li>
					</ul>
				</div>
				<div class="row block-content">
					<section class="content content-boxed">
						<form action="{{url('/admin/cashbox/import/csv/')}}" method="post" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
							<div class="col-xs-12 push-20">
								Selecciona fichero para subir:
							    <input type="file" name="csv" id="csv" class="form-control">
							</div>
							<div class="col-xs-12 push-20">
								<button class="btn btn-success" type="submit">
									Subir
								</button>
							</div>
						</form>
					</section>
				</div>
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

			$('#yearSelector').change(function() {
				var year = $(this).val();		

				window.location.replace("/admin/cashbox/"+year);
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {

			$('#addCashBox').click(function(){
				$.get('/admin/nuevo/addCashBox', function(data) {
					$('#contentListCashBox').empty().append(data);
				});
			});

			$('.selectAddGasto').change(function() {
				var id = $(this).attr('data-idCashBox');
				var concept = $('#concept-'+id).val();
				var date = $('#date-'+id).val();
				var comment = $('#comment-'+id).val();
				var typePayment = $('#typePayment-'+id).val();
				var type = $('#type-'+id).val();
				var importe = $('#import-'+id).val();
				$.get('/admin/cashbox/migrate/gasto', {id: id ,concept: concept, date: date ,comment: comment ,typePayment: typePayment , importe: importe, type: type}).done(function( data ) {


				});
			});

		});
	</script>
@endsection