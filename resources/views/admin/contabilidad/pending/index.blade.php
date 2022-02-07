<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<?php use \Carbon\Carbon; ?>
@extends('layouts.admin-master')

@section('externalScripts')
	<script type="text/javascript" src="{{ asset('/admin-css/assets/js/chart.js')}}"></script>
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('title') 
Movimentos de banco
@endsection

@section('content')
<style type="text/css">
	.header-navbar-fixed #main-container {
	    padding-top: 10px;
	}
	.accountSelector:hover{
		background-color: rgba(0,0,0,0.3)!important;
		color: white!important;
	}
	.guardado{
		background-color: green;
	}
</style>
<div class="bg-white">
 	<section class="content content-full">
 		
		@include('admin.contabilidad._button-contabiliad')

 		<div style="clear: both;"></div>

 		<div class="row">
			<div class="col-md-12 col-xs-12 push-20">
				<div class="col-md-3 col-xs-4 col-md-offset-4">
						<?php if ($WCCorriente > 0): ?>
							<i class="si si-ban " style="color:red"></i>
						<?php endif ?>
						<button class="accountSelector text-left" style="width: 90%;background-color: white;border:none;" value="CUENTA CORRIENTE">
							CUENTA CORRIENTE :   <b class="text-right"><?php echo number_format($cuentaCorriente['balance'],2,',','.') ?> €</b>
						</button>
						<br>
						<?php if ($WCPoliza > 0): ?>
							<i class="si si-ban " style="color:red"></i>
						<?php endif ?>		
						<button class="accountSelector text-left" style="width: 90%;background-color: white;border:none;" value="POLIZA DE CREDITO">
							POLIZA DE CREDITO :
							<b class="text-right">
							<?php if (isset($polizaCredito['balance'])): ?>
								<?php echo number_format($polizaCredito['balance'],2,',','.') ?> €
							<?php else: ?>
								0 €
							<?php endif ?>
							</b>
						</button>
						<br>
						<?php if ($WCTpv > 0): ?>
							<i class="si si-ban " style="color:red"></i>
						<?php endif ?>	
						<button class="accountSelector text-left" style="width: 90%;background-color: white;border:none;" value="COBROS TPV">
							COBROS TPV :
							<b class="text-right">
								<?php if (isset($tpv['balance'])): ?>
									<?php echo number_format($tpv['balance'],2,',','.') ?> €
								<?php else: ?>
									0 €
								<?php endif ?>
							</b>
						</button>
				</div>
				
			
				<div class="col-md-3 col-xs-12 push-10 pull-right">
					<!-- <button id="addPendiente" class="btn btn-success pull-left" data-toggle="modal" data-target="#modal-pendiente" style="border-radius: 100%;padding: 6px 10px 2px;"> 
						<i class="fa fa-plus fa-3x"></i>
					</button> -->
					<button id="addCvs" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-csv" > 
						<i class="fa fa-plus "></i> Importar CSV
					</button>
				</div>
			</div>

		</div> 
		<div class="row push-20">
			<div class="col-xs-12 col-md-10">
				<h2 class="text-center font-w300">
					Movimientos de la cuenta <span><b><?php echo $account ?></b></span>
				</h2>
			</div>	
			<div class="col-xs-12 col-md-1">
				<select class="form-control" id="monthSelector">
					<?php $monthSelector = Carbon::now()->startOfYear(); ?>
					<?php for ($i = 1;$i <= 12; $i++): ?>
						<?php if( $month == $monthSelector->copy()->format('m') ){ $selected = "selected"; }else{ $selected = ""; } ?>
						<option value="<?php echo $monthSelector->copy()->format('m') ?>" <?php echo $selected ?>>
							<?php echo ucfirst($monthSelector->copy()->formatlocalized('%B')) ?>	
						</option>
					<?php $monthSelector->addMonths(1); ?>
					<?php endfor ?>
				</select>
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
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<table class="table table-striped js-dataTable-full-bank table-header-bg">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center" style="width: 10%!important">Fecha</th>
							<th class="text-center">Concepto</th>
							<th class="text-center">Importe</th>
							<th class="text-center">Saldo</th>
							<!-- <th class="text-center">Tipo de pago</th> -->
							<th class="text-center">Tipo</th>
							<!-- <th class="text-center" style="width: 10%!important">Pagado por</th> -->
							<th class="text-center" style="width: 10%!important">Comentario</th>
							<th class="text-center">Accion</th>
						</tr>
					</thead>	
					<tbody>
						<?php foreach ($pendientes as $key => $pendiente): ?>
							<tr>
								<td class="text-center">
									<?php if($pendiente->type == ''){ echo "<i class='fa fa-exclamation fa-2x' aria-hidden='true' style='color:red;'></i>";} ?><?php echo $key+1 ?>
								</td>
								<td class="text-center">

									<?php $date = Carbon::createFromFormat('Y-m-d', $pendiente->date); ?>
									<p style="display: none"><?php echo strtotime($date->format('Y-m-d'))?></p>
									<b><?php echo strtoupper($date->format('d-m-Y')); ?></b>
									<input type="hidden" id="date-<?php echo $pendiente->id; ?>" value="<?php echo $pendiente->date; ?>">
								</td>
								<td class="text-center">
									<input type="text" class="form-control" id="concept-<?php echo $pendiente->id; ?>" value="<?php echo $pendiente->concept; ?>">
								</td>
								<td class="text-center">
									<?php if ($pendiente->import > 0): ?>
										<b class="text-success"><?php echo $pendiente->import; ?>€</b>
									<?php else: ?>
										<b class="text-danger"><?php echo $pendiente->import; ?>€</b>
									<?php endif ?>
									<input type="hidden" class="form-control" id="import-<?php echo $pendiente->id; ?>" value="<?php echo $pendiente->import; ?>">
								</td>
								<td class="text-center">
									<input type="hidden" class="form-control" id="balance-<?php echo $pendiente->id; ?>" value="<?php echo $pendiente->balance; ?>">
									<b><?php echo $pendiente->balance; ?>€</b>
								</td>
								
								<!-- <td class="text-center">
									<select class="form-control" id="typePayment-<?php echo $pendiente->id; ?>">
										<?php if ($pendiente->typePayment == "GASTO" || $pendiente->typePayment == ""): ?>
											<option value="GASTO" selected="">GASTO</option>
											<option value="INVERSION">INVERSION</option>
											<option value="TRASPASO">TRASPASO</option>

										<?php elseif($pendiente->typePayment == "INVERSION"): ?>
											<option value="GASTO">GASTO</option>
											<option value="INVERSION" selected="">INVERSION</option>	
											<option value="TRASPASO">TRASPASO</option>
										<?php else: ?>
											<option value="GASTO">GASTO</option>
											<option value="INVERSION" >INVERSION</option>	
											<option value="TRASPASO" selected="">TRASPASO</option>
										<?php endif ?>
									</select>
									 <?php echo $pendiente->typePayment; ?>
								</td> -->
									<td >
										<select class="js-select2 form-control selectAddGasto" id="type-<?php echo $pendiente->id; ?>" style="width: 100%;" data-placeholder="Seleccione un tipo" required data-idPending="<?php echo $pendiente->id; ?>">
							                <option></option>
							                <optgroup label="REPARTO DIVIDENDO">
							                 	<option value="DIVIDENDO JORGE" <?php if($pendiente->type == 'DIVIDENDO JORGE'){ echo "selected";} ?>>DIVIDENDO JORGE</option>
							                 	<option value="DIVIDENDO VICTOR" <?php if($pendiente->type == 'DIVIDENDO VICTOR'){ echo "selected";} ?>>DIVIDENDO VICTOR</option>
							                 	<option value="DIVIDENDO BELTRAN" <?php if($pendiente->type == 'DIVIDENDO BELTRAN'){ echo "selected";} ?>>DIVIDENDO BELTRAN</option>
							                </optgroup>
							                <optgroup label="APORTACION SOCIOS">
							                 	<option value="APORT. JORGE" <?php if($pendiente->type == 'APORT. JORGE'){ echo "selected";} ?>>APORT. JORGE</option>
							                 	<option value="APORT. VICTOR" <?php if($pendiente->type == 'APORT. VICTOR'){ echo "selected";} ?>>APORT. VICTOR</option>
							                 	<option value="APORT. BELTRAN" <?php if($pendiente->type == 'APORT. BELTRAN'){ echo "selected";} ?>>APORT. BELTRAN</option>
							                </optgroup>
							                <optgroup label="INGRESO">
							                 	<option value="INGRESO" <?php if($pendiente->type == 'INGRESO'){ echo "selected";} ?>>INGRESO CLIENTES</option>
							                </optgroup>
							                <optgroup label="INVERSION">
							                 	<option value="INVERSION" <?php if($pendiente->type == 'INVERSION'){ echo "selected";} ?>>INVERSION</option>
							                </optgroup>
							                <optgroup label="TRASPASO">
												<option value="TRASPASO" <?php if($pendiente->type == 'TRASPASO'){ echo "selected";} ?>>TRASPASO</option>
											</optgroup>
							                <optgroup label="GASTOS">
								                <option <?php if($pendiente->type == 'MOBILIARIO'){ echo "selected";} ?> value="MOBILIARIO">MOBILIARIO</option>
								                <option <?php if($pendiente->type == 'SERVICIOS PROFESIONALES INDEPENDIENTES'){ echo "selected";} ?> value="SERVICIOS PROFESIONALES INDEPENDIENTES">SERVICIOS PROFESIONALES INDEPENDIENTES</option>
								                <option <?php if($pendiente->type == 'VARIOS'){ echo "selected";} ?> value="VARIOS">VARIOS</option>
								                <option <?php if($pendiente->type == 'EQUIPAMIENTO DEPORTIVO'){ echo "selected";} ?> value="EQUIPAMIENTO DEPORTIVO">EQUIPAMIENTO DEPORTIVO</option>
								                <option <?php if($pendiente->type == 'IMPUESTOS'){ echo "selected";} ?> value="IMPUESTOS">IMPUESTOS</option>
								                <option <?php if($pendiente->type == 'SUMINISTROS'){ echo "selected";} ?> value="SUMINISTROS">SUMINISTROS</option>
								                <option <?php if($pendiente->type == 'GASTOS BANCARIOS'){ echo "selected";} ?> value="GASTOS BANCARIOS">GASTOS BANCARIOS</option>
								                <option <?php if($pendiente->type == 'PUBLICIDAD'){ echo "selected";} ?> value="PUBLICIDAD">PUBLICIDAD</option>
								                <option <?php if($pendiente->type == 'REPARACION Y CONSERVACION'){ echo "selected";} ?> value="REPARACION Y CONSERVACION">REPARACION Y CONSERVACION</option>
								                <option <?php if($pendiente->type == 'ALQUILER NAVE'){ echo "selected";} ?> value="ALQUILER NAVE">ALQUILER NAVE</option>
								                <option <?php if($pendiente->type == 'SEGUROS SOCIALES'){ echo "selected";} ?> value="SEGUROS SOCIALES">SEGUROS SOCIALES</option>
								                <option <?php if($pendiente->type == 'NOMINAS'){ echo "selected";} ?> value="NOMINAS">NOMINAS</option>
								                <option <?php if($pendiente->type == 'TARJETA VISA'){ echo "selected";} ?> value="TARJETA VISA">TARJETA VISA</option>
								                <option <?php if($pendiente->type == 'MATERIAL OFICINA'){ echo "selected";} ?> value="MATERIAL OFICINA">MATERIAL OFICINA</option>
								                <option <?php if($pendiente->type == 'MENSAJERIA'){ echo "selected";} ?> value="MENSAJERIA">MENSAJERIA</option>
								                <option <?php if($pendiente->type == 'PRODUCTOS VENDING'){ echo "selected";} ?> value="PRODUCTOS VENDING">PRODUCTOS VENDING</option>
								                <option <?php if($pendiente->type == 'LIMPIEZA'){ echo "selected";} ?> value="LIMPIEZA">LIMPIEZA</option>
								                <option <?php if($pendiente->type == 'INTERNET'){ echo "selected";} ?> value="INTERNET">INTERNET</option>
								                <option <?php if($pendiente->type == 'RENTING EQUIPAMIENTO DEPORTIVO'){ echo "selected";} ?> value="RENTING EQUIPAMIENTO DEPORTIVO">RENTING EQUIPAMIENTO DEPORTIVO</option>
								                <option <?php if($pendiente->type == 'COMISONES COMERCIALES'){ echo "selected";} ?> value="COMISONES COMERCIALES">COMISONES COMERCIALES</option>
								            </optgroup>
							            </select>
									</td>
								
								<!-- <td class="text-center">
									<select class="js-select2 form-control" id="PayFor-<?php echo $pendiente->id; ?>" style="width: 100%;" data-placeholder="Seleccione una" required>
						                <option></option>
						                <option value="BANCO" selected=""> BANCO </option>
						            </select>
								</td> -->
								<td class="text-center">
									<input type="text" class="form-control selectAddGasto " data-idPending="<?php echo $pendiente->id; ?>" id="comment-<?php echo $pendiente->id; ?>" value="<?php echo $pendiente->comment; ?>">
								</td>
								<td class="text-center">
									<div class="btn-group">
										<a href="{{ url('/admin/pending/delete') }}/<?php echo $pendiente->id; ?>" class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar" onclick="return confirm('Estas seguro?');"> <i class="fa fa-times"></i> </a> 
									</div>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>			
				</table>
			</div>
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
						<form action="{{url('/admin/pending/import/csv/')}}" method="post" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
							
							<div class="col-xs-12 push-20 text-center">
								<select name="account" id="account">
									<option>CUENTA CORRIENTE</option>
									<option>POLIZA DE CREDITO</option>
									<option>COBROS TPV</option>
								</select>
							</div>

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
		$('.selectAddGasto').change(function() {
			var selected = $(':selected', this);
			var id = $(this).attr('data-idPending');
			var concept = $('#concept-'+id).val();
			var date = $('#date-'+id).val();
			var type = $('#type-'+id).val();

			var comment = $('#comment-'+id).val();
			var typePayment = selected.closest('optgroup').attr('label');
			var PayFor = $('#PayFor-'+id).val();
			var importe = $('#import-'+id).val();
			var balance = $('#balance-'+id).val();
			console.log(comment);
			$.get('/admin/pending/migrate/gasto', {id: id ,concept: concept, date: date ,comment: comment ,typePayment: typePayment , importe: importe, type: type,balance: balance}).done(function( data ) {
				// alert('Añadido');
				// location.reload();
			});
		});

		$(document).ready(function() {

			$('#yearSelector').change(function() {
				var year = $(this).val();		
				var month = $('#monthSelector').val();
				var account = "<?php echo $account ?>";			

				window.location.replace("/admin/pending/"+account+"/"+year);
			}); 
			$('#monthSelector').change(function() {
				var month = $(this).val();		
				var year = $('#yearSelector').val();		
				var account = "<?php echo $account ?>";			

				window.location.replace("/admin/pending/"+account+"/"+year+"/"+month);
			});

			$('.accountSelector').click(function () {
				
				var account = $(this).val();

					window.location.replace("/admin/pending/"+account);
				
			});


			
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {

			$('#addPendiente').click(function(){
				$.get('/admin/nuevo/pending', function(data) {
					$('#contentListPendiente').empty().append(data);
				});
			});

		});
	</script>
@endsection