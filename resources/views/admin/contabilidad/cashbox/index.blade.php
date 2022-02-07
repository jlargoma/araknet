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


			<div class="col-xs-12 push-10">
				<button id="addCashBox" class="btn btn-success pull-left" data-toggle="modal" data-target="#modal-cashbox" style="border-radius: 100%;padding: 6px 10px 2px;"> 
					<i class="fa fa-plus fa-3x"></i>
				</button>
				<button id="addCvs" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-csv" > 
					<i class="fa fa-plus "></i> Importar CSV
				</button>
			</div>
		</div> 
		<div class="row">
			<div class="col-xs-12 col-md-11">
				<h2 class="text-center font-w300">
					Movimientos de Caja <b>(<span id="total-caja">{{ number_format($total_balance,2,',','.') }} €</span>)</b>
				</h2>
				@if ($last_arqueo)
					<?php $date_arqueo = Carbon::createFromFormat('Y-m-d', $last_arqueo->date); ?>
					<h4>
						Último arqueo el día {{ $date_arqueo->format('d-m-Y') }} con un importe total de arqueo {{ number_format($last_arqueo->import,2,',','.') }} €
					</h4>
				@endif
			</div>		
		</div>
		<br>
		<div class="row">
			<?php $total_import = $previous_import; ?>
			<table class="table table-bordered table-header-bg no-footer">
				<thead>
					<tr>
						<th class="text-center"><a href="{{url('/admin/cashbox/')}}">Fecha</a></th>
						<th class="text-center">Concepto</th>
						<th class="text-center type" style="width: 250px;">Tipo de movimiento</th>
						<th class="text-center type" style="width: 250px;">Tipo</th>
						<th class="text-center">Importe</th>
						<th class="text-center">Saldo</th>
						<th class="text-center">Comentario</th>
					</tr>
				</thead>	
				<tbody>
					<?php foreach ($cashBox as $date => $gastos): ?>
						<?php foreach ($gastos as $cash): ?>
							<tr class="
								@if($cash['type_payment'] == 'ARQUEO')
									bg-warning
								@elseif ($cash['type'] != 'GASTO') 
									bg-success 
								@else 
									bg-danger 
								@endif" style="color: black">
								<td class="text-center">
									{{ $cash['date'] }}
								</td>
								<td class="text-center">
									{{ $cash['concept'] }}
								</td>
								<td class="text-center">
									{{ $cash['type'] }}
								</td>
								<td class="text-center">
									{{ $cash['type_payment'] }}
								</td>
								<td class="text-center">
									<b>
										{{ number_format($cash['import'],2,',','.') }} €
									</b>
								</td>
								<td class="text-center">
									<b>
										{{ number_format($cash['balance'],2,',','.') }} €
									</b>
								</td>
								<td class="text-center">
									<!-- <pre><?php print_r($cash)?></pre> -->
									{{ $cash['user'] }}
								</td>
							</tr>
						<?php endforeach ?>
					<?php endforeach ?>
				</tbody>			
			</table>
		</div>
	</section>
</div>

<div class="modal fade" id="modal-cashbox" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-popout">
		<div class="modal-content">
			<div class="block block-themed block-transparent remove-margin-b">
				<div class="block-header bg-primary-dark">
					<ul class="block-options">
						<li>
							<button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
						</li>
					</ul>
				</div>
				<div class="row block-content" id="contentListCashBox">

				</div>
			</div>
		</div>
	</div>
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
			total_import = "{{ $total_balance }}";

			if (total_import > 0){
				$("#total-caja").addClass("text-success");
			} else {
				$("#total-caja").addClass("text-danger");
			}



			$('.selectAddGasto').change(function() {
				var selected = $(':selected', this);
				var id = $(this).attr('data-idCashBox');
				var concept = $('#concept-'+id).val();
				var date = $('#date-'+id).val();
				var comment = $('#comment-'+id).val();
				var typePayment = $('#typePayment-'+id).val();
				var type = selected.closest('optgroup').attr('label');
				
				var importe = $('#import-'+id).val();
				$.get('/admin/cashbox/migrate/gasto', {id: id ,concept: concept, date: date ,comment: comment ,typePayment: typePayment , importe: importe, type: type}).done(function( data ) {


				});
			});

		});
	</script>
@endsection