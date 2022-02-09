
@extends('layouts.admin-master')

@section('title') LISTADO DE CITAS - Araknet @endsection

@section('externalScripts')
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
	<style type="text/css">
		#DataTables_Table_0_wrapper .row > .col-sm-6:first-child{
			display: none;
		}
		#DataTables_Table_0_wrapper .row > .col-sm-6 #DataTables_Table_0_filter{
			text-align: left!important;
		}
		input[type="search"], ::-webkit-input-placeholder, :-moz-placeholder, :-ms-input-placeholder{
			color: black;
		}
		.header-navbar-fixed #main-container{
			padding-top: 0; 
		}

		td {
			padding: 5px!important;
		}
		
		th.text-center{
			background-color: #f3b760!important;
			border-right: 1px solid black;
			border-left: 1px solid black;
			font-size: 13px !important;
		}
		*, *:before, *:after{ 
		    box-sizing: border-box; 
		} 
		.css-shapes-preview{ 
			float: right;
		    position: relative; 
		    height: 10px; 
		    width: 10px; 
		    background-color: #f3b760; 
		    border-radius: 25px; 
		}
	</style>
@endsection

@section('headerButtoms')


		<li class="text-center">
			<a href="{{url('admin/nutricion')}}" class="text-white btn btn-sm btn-success font-s16 font-w300" style="padding: 10px 15px;line-height: 15px;">
			    Listado NUTRICIÓN
		    </a>
		</li>
		<li class="text-center">
			<a href="{{url('admin/citas')}}" class="text-white btn btn-sm btn-primary font-s16 font-w300" style="padding: 10px 15px;line-height: 15px;">
				Listado de Citas
		    </a>
		</li>

@endsection


@section('content')



<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>

<div class="bg-white">
	
	<section class="content content-full">
		<div class="row">
			<div class="row push-20">
				<div class="col-xs-12 col-md-10">
					<h2 class="text-center font-w300">
						<b>Informe Plan en Forma</b></span>
					</h2>
				</div>		
			</div>
			<div class="row push-20">
				<div class="col-md-12">
					<table class="table table-striped js-dataTable-full table-header-bg">
						
						<thead>					
							<tr>
								<th class="text-center">Nombre</th>
								<th class="text-center">Primera Cita</th>
								<th class="text-center">Peso inicial</th>
								<th class="text-center">Objetivo</th>
								<th class="text-center">Peso perdido</th>
								<th class="text-center">Semana Actual</th>
								
								<?php for ($i=1; $i <= 10; $i++):?>
									<th class="text-center"> Sem <?php echo $i ?></th>
								<?php endfor; ?>
							</tr>	
						</thead>
						<tbody>

							<?php foreach ($usuarios as $usuario): ?>
								<tr>
									<td><?php echo $usuario->user->name ?></td>
									<td class="text-center">
										<?php if (isset($plan[$usuario->customer_id][0])): ?>
											<?php echo Carbon::createFromFormat('Y-m-d H:i:s',$plan[$usuario->customer_id][0]->date)->formatLocalized('%d %b') ?>								
										<?php endif ?>								
									</td>
									<td class="text-center">
										<?php if (isset($plan[$usuario->customer_id][0])): ?>
											<?php echo $plan[$usuario->customer_id][0]->weight ?>								
										<?php endif ?>	
									</td>
									<td class="text-center">
										<?php if (isset($plan[$usuario->customer_id][0])): ?>
											<?php echo $plan[$usuario->customer_id][0]->objetive ?>								
										<?php endif ?>	
									</td>
									<td class="text-center">
										<?php if (isset($plan[$usuario->customer_id][0])): ?>
											<?php echo $plan[$usuario->customer_id][0]->weight - $plan[$usuario->customer_id][max(array_keys($plan[$usuario->customer_id]))]->weight ?>								
										<?php endif ?>	
									</td>
									

										
											<?php if (isset($plan[$usuario->customer_id])): ?>
												<?php if ($actualWeek == Carbon::createFromFormat('Y-m-d H:i:s',$plan[$usuario->customer_id][max(array_keys($plan[$usuario->customer_id]))]->date)->format('W')): ?>
													<td>
														<input 	style="width: 100%"
															type="text" class="form-control text-center new"
															data-user="<?php echo $usuario->customer_id ?>"
															data-week="<?php echo max(array_keys($plan[$usuario->customer_id])) ?>"
															value="<?php echo $plan[$usuario->customer_id][max(array_keys($plan[$usuario->customer_id]))]->weight ?>">
													</td>
												<?php else: ?>
													<td class="text-center" style="background-color: rgba(243,183,96,0.2);" >
														<input 	style="width: 100%"
														type="text" class="form-control text-center new"
														data-user="<?php echo $usuario->customer_id ?>"
														data-week="<?php echo max(array_keys($plan[$usuario->customer_id]))+1 ?>">
												</td>
												<?php endif ?>
												
											<?php else: ?>
												<td class="text-center" style="background-color: rgba(243,183,96,0.2);" >
													<input 	style="width: 100%"
														type="text" class="form-control text-center new"
														data-user="<?php echo $usuario->customer_id ?>"
														data-week="0">
												</td>
											<?php endif ?>
											
										
									
									<?php for ($i=1; $i <= 10; $i++):?>
										<?php if (isset($plan[$usuario->customer_id][$i])): ?>
											<td class="tex-center">
												<p style="display: none"><?php echo $plan[$usuario->customer_id][$i]->weight ?></p>
												<input style="width: 100%"	
														type="text" class="form-control text-center editable" 
														value="<?php echo $plan[$usuario->customer_id][$i]->weight ?>"
														data-id="<?php echo $plan[$usuario->customer_id][$i]->id ?>"
														data-user="<?php echo $usuario->customer_id ?>"
														data-week="<?php echo $plan[$usuario->customer_id][$i]->week ?>">

											</td>
										<?php else: ?>
											<td class="tex-center">
												<input style="width: 100%"	
														type="text" class="form-control text-center new"
														data-user="<?php echo $usuario->customer_id ?>"
														data-week="<?php echo $i ?>">

											</td>
										<?php endif ?>
									<?php endfor; ?>
								</tr>
							<?php endforeach ?>
							
							

							

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection

@section('scripts') 

	<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="{{ asset('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>
	
	<script type="text/javascript">
		$('.editable').change(function() {
			var id = $(this).attr('data-id');
			var weight = $(this).val();
			var user = $(this).attr('data-user');
			var week = $(this).attr('data-week');
			$.get('/admin/informe-forma/saveForma', {id: id ,weight: weight, user: user ,week: week }).done(function( data ) {
				// alert('Añadido');
				location.reload();
			});
		});

		$('.new').change(function() {
			var weight = $(this).val();
			var user = $(this).attr('data-user');
			var week = $(this).attr('data-week');
			$.get('/admin/informe-forma/newForma', {weight: weight, user: user ,week: week }).done(function( data ) {
				// alert('Añadido');
				location.reload();
			});
		});
	</script>
@endsection