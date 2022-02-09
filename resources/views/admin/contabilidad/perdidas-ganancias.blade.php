<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<?php use \Carbon\Carbon; ?>

@extends('layouts.admin-master')

@section('title') Cuenta de perdidas y ganancias @endsection

@section('content')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<style type="text/css">
		#main-container{
			padding-top: 10px!important
		}
</style>
<div class="bg-white push-10">
	<section class="content content-full">
		<div class="row">
		 		<div class="col-md-12 col-xs-12 push-20">
		 			
		 			@include('admin.contabilidad._button-contabiliad')
		 			
		 		</div>

			<div class="col-xs-12 col-md-6">
				<div class="col-xs-12 col-md-6">
					<canvas id="barChart" style="width: 100%; height: 250px;"></canvas>
				</div>
				<div class="col-xs-12 col-md-6">
					<canvas id="barChartClient" style="width: 100%; height: 250px;"></canvas>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="col-xs-12 col-md-4 not-padding">
					<div class="col-xs-12 not-padding">
						<div class="block block-link-hover3 text-center push-0">
							<?php $total = 0 ?>
							<?php for ($i = 1; $i < 12; $i++) { 
								$total += 0; 
								}
							?>
							<div class="block-content block-content-full bg-success">
								<div class="h1 font-w700 text-white"> <?php echo number_format(abs($total),0,',','.') ; ?><span class="h2 text-white-op">€</span></div>
								<div class="h5 text-white-op text-uppercase push-5-t">Ingresos anuales</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-4 not-padding">
					<div class="col-xs-12 not-padding">
						<div class="block block-link-hover3 text-center push-0">
							<?php $totalExpenses = 0; ?>
		            		<?php for($i = 1 ; $i <= 12; $i++): ?>
		            			<?php 
		            				$totalExpenses += 0;
		            			?>
		            		<?php endfor; ?>
							<div class="block-content block-content-full bg-danger">
								<div class="h1 font-w700 text-white"> <?php echo number_format(abs($totalExpenses),0,',','.') ; ?><span class="h2 text-white-op">€</span></div>
								<div class="h5 text-white-op text-uppercase push-5-t">Gastos anuales</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-4 not-padding">
					<div class="col-xs-12 not-padding">
						<div class="block block-link-hover3 text-center push-0">
							<div class="block-content block-content-full bg-primary">
								<div class="h1 font-w700 text-white"> 
									<?php echo number_format($total - abs($totalExpenses),0,',','.') ; ?><span class="h2 text-white-op">€</span> 
									<?php if (($total - abs($totalExpenses)) < 0): ?>
										<i class="fa fa-arrow-down text-danger"></i>
									<?php else: ?>
										<i class="fa fa-arrow-up text-success"></i>
									<?php endif ?>  
								</div>
								<div class="h5 text-white-op text-uppercase push-5-t">Resultado</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<?php $actualDate = Carbon::now(); ?>
				<?php $auxExpenses = 0; ?>
				<?php $auxIncomes = 0; ?>
				<?php $aux = $actualDate->copy()->subMonths(2) ?>
				<?php for ($i=1; $i <= 3 ; $i++): ?>
					<?php
                        $dataExpenses = DB::table("expenses")
							    	->select(DB::raw("SUM(import) as count"))
							    	->whereMonth('date','=' , $aux->copy()->format('m'))
                                	->whereYear('date','=' , $aux->copy()->format('Y'))
									->get();
						$expenses = $dataExpenses[0]->count;

                       	
						$incomes =   0;

				
						if(  abs($expenses) > $auxExpenses){
							$auxExpenses = abs($expenses);
						} 
						if(  abs($incomes) > $auxIncomes){
							$auxIncomes = abs($incomes);
						} 
						if ($i > 1) {

							if (abs($expenses) > $auxExpenses) {
								$statusExpenses = 1;
								
							}else{
								$statusExpenses = 0;
							}

							if (abs($incomes) > $auxIncomes) {
								$statusIncomes = 1;
								
							}else{
								$statusIncomes = 0;
							}
						}
						
						
					?>
					<div class="col-xs-12 col-md-4 push-0 not-padding">
						<div class="block block-link-hover3 text-center">
							<div class="col-xs-12 bg-gray-light" style="padding:20px 15px;">
								<div class="h5 text-black-op text-uppercase push-5-t">
									<span class="font-w700"><?php echo ucfirst($aux->copy()->formatlocalized('%B')) ?></span>
								</div>
								<div class="h3 font-w700 text-black text-center"> 
									<span class="h5">INGRESOS</span> 
									<?php echo number_format(abs($incomes),2,',','.') ; ?><span class="h3 text-black">€</span>
									<?php if ( isset($statusIncomes) && $statusIncomes == 1): ?>
										<i class="fa fa-arrow-down text-danger"></i>
									<?php elseif( isset($statusIncomes) && $statusIncomes == 0 ): ?>
										<i class="fa fa-arrow-up text-success"></i>
									<?php endif ?>  
									<br>
									
									<span class="h5">GASTOS</span> <?php echo number_format(abs($expenses),2,',','.') ; ?>
									<span class="h3 text-black">€</span>
									<?php if ( isset($statusExpenses) && $statusExpenses == 1): ?>
										<i class="fa fa-arrow-up text-danger"></i>
									<?php elseif( isset($statusExpenses) && $statusExpenses == 0 ): ?>
										<i class="fa fa-arrow-down text-success"></i>
									<?php endif ?>  
								</div>
								
								
							</div>
						</div>
					</div>

					<?php $aux->addMonths(1) ?>
				<?php endfor; ?>
				<?php $aux = $actualDate->copy()->subMonths(2) ?>
				<?php 
					$auxTotalClientes = 0;
					$auxTotalCuotaClienteMes = 0;
				?>
				<?php for ($i=1; $i <= 3 ; $i++): ?>
					<div class="col-xs-12 col-md-4 not-padding push-0">
						<div class="block block-link-hover3 text-centerpush-0">
						
							<div class="row block-content  bg-gray-light" style="padding: 10px 5px;">
								<?php 
									$clientsCoutas =DB::table('charges')
					                                ->distinct()
					                                ->select('customer_id')
					                                ->whereIn('type_rate', [1,2,3,4])
													->whereYear('date_payment', '=' ,$aux->copy()->format('Y'))
													->whereMonth('date_payment', '=' ,$aux->copy()->format('m'))
													->get();

									$clientsNotCoutas =DB::table('charges')
					                                ->distinct()
					                                ->select('customer_id')
					                                ->whereNotIn('type_rate', [1,2,3,4])
													->whereYear('date_payment', '=' ,$aux->copy()->format('Y'))
													->whereMonth('date_payment', '=' ,$aux->copy()->format('m'))
													->get();
								?>
								
								<?php $totalClientes = count($clientsCoutas) + count($clientsNotCoutas) ?>
								<?php 
									if ($totalClientes > $auxTotalClientes) {
										$auxTotalClientes = $totalClientes;
									}else{
										$auxTotalClientes = 0;
									}

									if( $i > 1){
										if ($totalClientes > $auxTotalClientes) {
											$statusTotalClientes = 1;
										}elseif( $auxTotalClientes > $totalClientes ){
											$statusTotalClientes = 0;
										}else{
											$statusTotalClientes = 0;

										}
									}
								?>
									<div class="col-md-6 not-padding">
										<div class="col-md-12">
											Clientes <br>
											<span class="font-w700 font-s18"><?php echo count($clientsCoutas) ?></span>/<span class="font-w700 font-s18"><?php echo count($clientsNotCoutas) ?></span>
											<?php 
												$totalCoutaCliente = \App\Charges::whereYear('date_payment', '=' ,$aux->copy()->format('Y'))
																			->whereMonth('date_payment', '=' ,$aux->copy()->format('m'))
																			->sum('import');
												if ($totalCoutaCliente > $auxTotalCuotaClienteMes) {
													$auxTotalCuotaClienteMes = $totalCoutaCliente;
												}
												if( $i > 1){
													if ($totalCoutaCliente > $auxTotalCuotaClienteMes) {
														$statusTotalCoutaCliente = 1;
													}else{
														$statusTotalCoutaCliente = 0;
													}
												}
											?>
										</div>
									</div>
									<div class="col-md-6 not-padding">
										<div class="col-md-12 text-center  h1 text-black-op text-uppercase">
											<span class="font-w700"><?php echo $totalClientes; ?></span>
											<?php if ( isset($statusTotalClientes) && $statusTotalClientes == 1): ?>
												<i class="fa fa-arrow-down text-danger"></i>
											<?php elseif( isset($statusTotalClientes) && $statusTotalClientes == 0 ): ?>
												
												<i class="fa fa-arrow-up text-success"></i>
											<?php endif ?> 
										</div>
									</div>
									<div class="col-md-12 not-padding">
										<div class="col-md-5">Cuota/mes</div> 
										<div class="col-md-7 text-center">
											<span class="font-w700 font-s18 ">
												<?php 
                                                                                                $auxCuotas = count($clientsCoutas) + count($clientsNotCoutas);
                                                                                                if ($auxCuotas<=0) $auxCuotas = 1;
                                                                                                $avg =  $totalCoutaCliente /  $auxCuotas;
                                                                                                        
                                                                                                ?>
												<?php echo number_format(abs($avg),2,',','.') ; ?>€
												<?php if ( isset($statusTotalCoutaCliente) && $statusTotalCoutaCliente == 1): ?>
													<i class="fa fa-arrow-down text-danger"></i>
												<?php elseif( isset($statusTotalCoutaCliente) && $statusTotalCoutaCliente == 0 ): ?>
													<i class="fa fa-arrow-up text-success"></i>
												<?php endif ?> 
											</span>
										</div>
									</div>

							</div>
						</div>
					</div>
					<?php $aux->addMonths(1) ?>
				<?php endfor; ?>
			</div>
		</div>
	</section>
</div>
<div class="bg-white">
	<section class="content content-full">
		<div class="row">
			<div class="col-xs-9 push-20">
				<h2 class="font-w300 text-center"><b>CUENTA DE PERDIDAS Y GANACIAS DE <?php echo $year; ?></b></h2>
			</div>
			<div class="col-xs-3 col-md-3 push-30">
				<div class="col-md-4 pull-right">
					<select class="form-control" id="yearSelector">
						<?php $yearSelector = $date->copy()->subYears(1); ?>
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
			<div class="col-xs-12">
				<!-- col-md-6  -->
				
				<table class="js-table-sections table table-hover table-bordered table-striped table-header-bg">
					<thead>
						<tr>
							<th class="text-center" style="width: 30px;"></th>
							<th class="text-center"></th>
							<?php $x = $date->copy()->startOfYear(); ?>
							<?php for($i = 1 ; $i <= 12; $i++): ?>
								<th class="text-center"><?php echo $x->formatlocalized('%B'); ?></th>
								<?php $x->addMonths(1); ?>

							<?php endfor; ?>
							<th class="text-center">Total</th>
						</tr>
					</thead >
					<tbody class="js-table-sections-header">
						<tr>
						 	<td class="text-center" style="color: #fff; background-color: #46c37b;">
                                <i class="fa fa-angle-right"></i>
                            </td>
							<td class="text-center" style="color: #fff; background-color:#46c37b; border-bottom-color:#46c37b;">
								<b>INGRESOS</b>
							</td>
							<?php $totalYear = 0; ?>
							<?php for($i = 1 ; $i <= 12; $i++): ?>
								<td class="text-center" style="color: #fff; background-color:#46c37b; border-bottom-color:#46c37b;">
									<?php $totalMonth = 0; 
									?>
									<b>
										<?php echo number_format($totalMonth, 0,',' , '.'); ?> €
									</b>
									<?php $totalYear += $totalMonth; ?>
								</td>
							<?php endfor; ?>
							<td class="text-center" style="color: #fff; background-color: #46c37b; border-bottom-color:#46c37b;">
								<b>
									<?php echo number_format($totalYear,0,',','.' ); ?> €
								</b>
							</td>
						</tr>	
					</tbody>
					<tbody>		
						<?php foreach ($rates[$year] as $key => $rate): ?>
							<?php $totalRates = 0; ?>
							<tr>
								<td>&nbsp;</td>
								<td class="text-left font-w600">
									<?php echo strtoupper($key) ?>
								</td>
								<?php for ($i=1; $i <= 12; $i++) : ?>
									<td class="text-center">
										<b><?php echo number_format($rate[$i],2,',','.') ; ?> €</b>
									</td>
									<?php $totalRates += $rate[$i]; ?>
								<?php endfor; ?>
								<td class="text-center">
									<b><?php echo number_format($totalRates,2,',','.') ; ?> €</b>
								</td>
							</tr>
						<?php endforeach; ?>	
						
						<?php foreach ($ratesExtras[$year] as $key => $rateExtra): ?>
							<?php $totalRatesExtras = 0; ?>
							<tr>
								<td>&nbsp;</td>

								<td class="text-left font-w600">
									<?php echo strtoupper($key) ?>
								</td>
								<?php for ($i=1; $i <= 12; $i++) : ?>
									<td class="text-center"><b><?php echo $rateExtra[$i] ?> €</b></td>
									<?php $totalRatesExtras += $rateExtra[$i]; ?>
								<?php endfor; ?>
								<td class="text-center"><b><?php echo  number_format($totalRatesExtras,2,',','.') ?> €</b></td>
							</tr>						
						<?php endforeach ?>		
					</tbody>		
						<!-- FIN INGRESOS -->
						<!-- GASTOS -->
					<tbody class="js-table-sections-header">
						<tr>
							<td class="text-center" style="color: #fff; background-color: #a94442;">
							    <i class="fa fa-angle-right"></i>
							</td>
							<td class="text-center" style="color: #fff; background-color: #a94442; border-bottom-color: #a94442;">
								<b>GASTOS</b>
							</td>
							<?php $totalYear = 0; ?>
							<?php for($i = 1 ; $i <= 12; $i++): ?>
								<td class="text-center" style="color: #fff; background-color: #a94442; border-bottom-color: #a94442;">
									<?php $totalMonth = 0;
									?>
									<b>
										<?php echo number_format($totalMonth,2,',','.') ; ?> €
									</b>
									<?php $totalYear += $totalMonth; ?>
								</td>
							<?php endfor; ?>
							<td class="text-center" style="color: #fff; background-color: #a94442; border-bottom-color: #a94442;">
								<b>
									<?php echo number_format($totalYear,2,',','.') ; ?> €
								</b>
							</td>
						</tr>
					</tbody>
					<tbody>
						<?php foreach ($gastos as $key => $gasto): ?>
							<tr>
								<td>&nbsp;</td>

								<td>
									<b><?php echo strtoupper($key); ?></b>
								</td>
								<?php $totalYear = 0; ?>
								<?php for ($i=1; $i <= 12; $i++): ?>
									<td class="text-center">
										<b><?php echo number_format($gasto[$i],2,',','.'); ?> €<b>
									</td>
									<?php $totalYear +=  $gasto[$i]; ?>
								<?php endfor; ?>
								<td class="text-center">
									<b><?php echo number_format($totalYear,2,',','.'); ?>€</b>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
					<tbody class="js-table-sections-header">
						<!-- FIN GASTOS -->
						<tr >
							<td class="text-center"  style="color: #fff; background-color: #5c90d2;">
							    <i class="fa fa-angle-right"></i>
							</td>

							<td class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 18px;">
								<b>BENEFICIO CONTABLE</b>
							</td>
							<?php $totalYearBeneficios = 0; ?>
							<?php for($i = 1 ; $i <= 12; $i++): ?>
								<td class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 18px;">
									<?php 
										$totalMonthGastos = 1;

										$totalMonthIngresos =  1; 

										$totalMonthBeneficios = $totalMonthIngresos - abs($totalMonthGastos);
									?>
									<b>
										<?php echo number_format($totalMonthBeneficios,2,',','.'); ?>€
									</b>
									<?php $totalYearBeneficios += $totalMonthBeneficios; ?>
								</td>
							<?php endfor; ?>
							<td class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 18px;">
								<b><?php echo number_format($totalYearBeneficios,2,',','.'); ?> €</b>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>
@endsection
@section('scripts') 
	<script>
        jQuery(function () {
            // Init page helpers (Table Tools helper)
            App.initHelpers('table-tools');
        });
    </script>
	<script type="text/javascript">
		$(document).ready(function() {

			$('#yearSelector').change(function() {
				var year = $(this).val();			

				window.location.replace("/admin/perdidas-ganancias/"+year);
			});


			/* GRAFICA INGRESOS/GASTOS */
				var data = {
				    labels: [
				    			<?php $init = $date->copy()->startOfYear(); ?>
								<?php for($i = 1 ; $i <= 12; $i++): ?>
									<?php if ($i == 12): ?>
										"<?php echo substr(ucfirst($init->formatlocalized('%B')), 0, 3); ?>"
									<?php else: ?>
										"<?php echo substr(ucfirst($init->formatlocalized('%B')), 0, 3); ?>",
									<?php endif; ?>
									<?php $init->addMonths(1); ?>
								<?php endfor; ?>
				    			],
				    datasets: [
				        {
				            label: "Ingresos",
				            backgroundColor: [
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				                'rgba(67, 160, 71, 0.3)',
				            ],
				            borderColor: [
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				                'rgba(67, 160, 71, 1)',
				            ],
				            borderWidth: 1,
				            data: [
				            		<?php for($i = 1 ; $i <= 12; $i++): ?>
				            			<?php 
				            				$totalMonth = 	1;
				            			?>
			            				<?php if ($i == 12): ?>
			            					<?php echo $totalMonth; ?>
			            				<?php else: ?>
			            					<?php echo $totalMonth; ?>,
			            				<?php endif ?>
				            		<?php endfor; ?>
				            	],
				        },
	        	        {
	        	            label: "Gastos",
	        	            backgroundColor: [
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	                'rgba(229, 57, 53, 0.3)',
	        	            ],
	        	            borderColor: [
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	                'rgba(229, 57, 53, 1)',
	        	            ],
	        	            borderWidth: 1,
	        	            data: [
        	            		<?php for($i = 1 ; $i <= 12; $i++): ?>
        	            			<?php 
        	            				$totalMonth = 
        	            								$gastos['MOBILIARIO'][$i]+
        												$gastos['SERVICIOS PROFESIONALES INDEPENDIENTES'][$i]+
        												$gastos['VARIOS'][$i]+
        												$gastos['EQUIPAMIENTO DEPORTIVO'][$i]+
        												$gastos['IMPUESTOS'][$i]+
        												$gastos['SUMINISTROS'][$i]+
        												$gastos['GASTOS BANCARIOS'][$i]+
        												$gastos['PUBLICIDAD'][$i]+
        												$gastos['REPARACION Y CONSERVACION'][$i]+
        												$gastos['ALQUILER NAVE'][$i]+
        												$gastos['SEGUROS SOCIALES'][$i]+
        												$gastos['NOMINAS'][$i]+
        												$gastos['TARJETA VISA'][$i]+
        												$gastos['MATERIAL OFICINA'][$i]+
        												$gastos['MENSAJERIA'][$i]+
        												$gastos['PRODUCTOS VENDING'][$i]+
        												$gastos['LIMPIEZA'][$i]+
        												$gastos['INTERNET'][$i]+
        												$gastos['RENTING EQUIPAMIENTO DEPORTIVO'][$i]+
        												$gastos['COMISONES COMERCIALES'][$i];
        	            			?>
                    				<?php if ($i == 12): ?>
                    					<?php echo abs($totalMonth); ?>
                    				<?php else: ?>
                    					<?php echo abs($totalMonth); ?>,
                    				<?php endif ?>
        	            		<?php endfor; ?>
        	            		],
	        	        }

				    ]
				};

				var myBarChart = new Chart('barChart', {
				    type: 'line',
				    data: data,
				});



			/* GRAFICA CLIENTES */

				/* La configuracion de posicion funciona con la siguiente que se escriba*/
					Chart.defaults.global.legend.position = 'top';
					Chart.defaults.global.legend.labels.usePointStyle = true;
				/*Fin de configuracion de posicion*/
				var dataClient = {
				    labels: [
				    			<?php $year = $date->copy()->startOfYear(); ?>
								<?php for($i = 1 ; $i <= 12; $i++): ?>
									<?php if ($i == 12): ?>
										"<?php echo substr(ucfirst($year->formatlocalized('%B')), 0, 3); ?>"
									<?php else: ?>
										"<?php echo substr(ucfirst($year->formatlocalized('%B')), 0, 3); ?>",
									<?php endif; ?>
									<?php $year->addMonths(1); ?>
								<?php endfor; ?>
				    			],
				    datasets: [
				        {
				            label: "Clientes por mes",
				            backgroundColor: [
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				                'rgba(54, 162, 235, 0.2)',
				            ],
				            borderColor: [
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(54, 162, 235, 1)',
				            ],
				            borderWidth: 1,
				            data: [
				            		<?php for($i = 1 ; $i <= 12; $i++): ?>
			            				<?php if ($i == 12): ?>
			            					<?php echo $clientes[$i]; ?>
			            				<?php else: ?>
			            					<?php echo $clientes[$i]; ?>,
			            				<?php endif ?>
				            		<?php endfor; ?>
				            		],
				        }
				    ]
				};

				var myBarChartClient = new Chart('barChartClient', {
				    type: 'bar',
				    data: dataClient,
				});
		});
	</script>

@endsection