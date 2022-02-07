<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<style type="text/css">
	#main-container{
		padding-top: 0!important
	}
	.table.table-bordered.table-striped.table-header-bg .first-line > th{
		padding: 5px 0 0 0 ;
	}

	.table.table-bordered.table-striped.table-header-bg .second-line > th{
		padding: 10px 0;
	}
	.table.table-bordered.table-striped.table-header-bg tbody tr td{
		padding: 8px 10px;
	}
	.fisioterapia, .nutricion{
		border-bottom: 1px solid #e8e8e8; 
		padding: 15px 5px;
	}
	.fisioterapia{
	    background-color: #70b9eb;

	}
	.nutricion{
	    background-color: #46c37b;

	}
</style>

<div class="row" id="table-dates">
	<div class="col-md-12">
		<?php $weekDays = $week->copy(); ?>
		<?php for ($i=1; $i <= 6; $i++) : ?>
			<div class="col-sm-12 col-lg-2 not-padding" style="min-height: 350px;">
				<div class="block block-themed">
					<div class="block-header bg-primary">
						<h3 class="text-center block-title"><?php echo ucfirst(utf8_encode($weekDays->copy()->formatLocalized('%A %d'))); ?></h3>
					</div>
					<div class="block-content block-content-full text-center bg-white"  style="padding: 20px 5px;">
						<?php if (Auth::user()->role == 'nutri'): ?>
							<?php $dates = \App\Dates::whereYear('date','=', $weekDays->format('Y'))
														->whereMonth('date','=', $weekDays->format('m'))
														->whereDay('date','=', $weekDays->format('d'))
														->where('id_type_rate',5)
														->orderBy('date','ASC')->get(); 
							?>
						<?php elseif (Auth::user()->role == 'fisio'): ?>
							<?php $dates = \App\Dates::whereYear('date','=', $weekDays->format('Y'))
													->whereMonth('date','=', $weekDays->format('m'))
													->whereDay('date','=', $weekDays->format('d'))
													->where('id_type_rate',6)
													->orderBy('date','ASC')->get(); 
							?>
						<?php else: ?>
							<?php $dates = \App\Dates::whereYear('date','=', $weekDays->format('Y'))
													->whereMonth('date','=', $weekDays->format('m'))
													->whereDay('date','=', $weekDays->format('d'))
													->orderBy('date','ASC')->get(); 
							?>
						<?php endif ?>
						
						<?php if ( count($dates) > 0): ?>
							<?php foreach ($dates as $key => $date): ?>
									<?php if (Auth::user()->role == 'nutri'): ?>
										<div class="col-xs-12  items-push push-20 <?php echo strtolower($date->service->name) ?>" data-idDate="<?php echo $date->id ?>">

											<div style="position: absolute;top: 0px; left: 5px;"">
												<?php if($date->charged == 0): ?>
													<i class="fa fa-circle text-danger" aria-hidden="true"></i>
												<?php else: ?>
			           								<i class="fa fa-circle text-success" aria-hidden="true"></i>
			           							<?php endif; ?>
											</div>
											<?php if($date->charged == 0 && $date->status == 0): ?>
												<div style="position: absolute;top: -15px; right: -15px;  padding:1px 5px; z-index: 100; cursor: pointer">
													<div class="deleteDate" data-idDate="<?php echo $date->id; ?>">
														<i class="fa fa-times fa-2x text-danger"></i>
													</div>
												</div>
											<?php endif; ?>

											<div class="row push-0" style="margin-bottom: 0;">
												<div class="col-xs-4 h5 push-5 text-white">
													<?php echo date('H', strtotime($date->date)) ?>:00
												</div>
												<div class="col-xs-8 h5 font-w600 text-muted text-white">
													<?php echo $date->service->name; ?>
												</div>
												<div class="col-xs-12 h5 push-5 text-center text-white">
													<b><?php echo $date->user->name; ?></b> con (<b><?php echo $date->coach->name; ?></b>)
												</div>
											</div>
										</div>
									<?php else: ?>
										<div class="col-xs-12  items-push push-20 <?php echo strtolower($date->service->name) ?> <?php if($date->charged == 0){ echo "dateCharge"; } ?>" data-idDate="<?php echo $date->id ?>">

											<div style="position: absolute;top: 0px; left: 5px;"">
												<?php if($date->charged == 0): ?>
													<i class="fa fa-circle text-danger" aria-hidden="true"></i>
												<?php else: ?>
			           								<i class="fa fa-circle text-success" aria-hidden="true"></i>
			           							<?php endif; ?>
											</div>
											<?php if($date->charged == 0 && $date->status == 0): ?>
												<div style="position: absolute;top: -15px; right: -15px;  padding:1px 5px; z-index: 100; cursor: pointer">
													<div class="deleteDate" data-idDate="<?php echo $date->id; ?>">
														<i class="fa fa-times fa-2x text-danger"></i>
													</div>
												</div>
											<?php endif; ?>

											<div class="row push-0" style="margin-bottom: 0;">
												<div class="col-xs-4 h5 push-5 text-white">
													<?php echo date('H', strtotime($date->date)) ?>:00
												</div>
												<div class="col-xs-8 h5 font-w600 text-muted text-white">
													<?php echo $date->service->name; ?>
												</div>
												<div class="col-xs-12 h5 push-5 text-center text-white">
													<b><?php echo $date->user->name; ?></b> con (<b><?php echo $date->coach->name; ?></b>)
												</div>
											</div>
										</div>
									<?php endif ?>
									
							<?php endforeach ?>
						<?php else: ?>
							<h3 class="h5 text-center font-w300 push-20">
								No hay Citas para Hoy
							</h3>
						<?php endif ?>
					</div>
				</div>
			</div>
			<?php $weekDays->addDays(1); ?>
		<?php endfor; ?>
	</div>
</div>
<script type="text/javascript">
	$('#week').change(function(event) {
		var date = $(this).val();

		$('#table-dates').empty().load('/admin/citas/_dates/'+date);
	});

	$('.deleteDate').click(function(event) {
		event.preventDefault();
		var date = $('#week').val();
		var id = $(this).attr('data-idDate');
		$.get('/admin/citas/delete/'+id,).done(function( data ) {
			$('#table-dates').empty().load('/admin/citas/_dates/'+date);
			});
			
	});

	$('.dateCharge').click(function(event) {
		event.preventDefault();
		var id = $(this).attr('data-idDate');

		$('#content-form-date').empty().load('/admin/citas/getForm/cita/'+id);
	});
</script>