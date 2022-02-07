<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<?php use \Carbon\Carbon; ?>
@extends('layouts.admin-master')

@section('title') LISTADO DE CITAS - EVOLUTIO @endsection


@section('content')

@section('headerButtoms')
<?php if (Auth::user()->role == 'nutri' || Auth::user()->role == 'admin'): ?>
	<li class="text-center">
		<a href="{{url('admin/nutricion')}}" class="text-white btn btn-sm btn-success font-s16 font-w300" style="padding: 10px 15px;line-height: 15px;">
		    Listado NUTRICIÓN
	    </a>
	</li>
<?php endif ?>
<?php if (Auth::user()->role == 'fisio' || Auth::user()->role == 'admin'): ?>
	<li class="text-center">
		<a href="{{url('admin/fisioterapia')}}" class="text-white btn btn-sm btn-primary font-s16 font-w300" style="padding: 10px 15px;line-height: 15px;">
		    Listado FISIOTERAPIA
	    </a>
	</li>
<?php endif ?>
<?php if (Auth::user()->role == 'admin' || Auth::user()->role == 'nutri'): ?>
	<li class="text-center">
		<a href="{{url('admin/informe-forma')}}" class="text-white btn btn-sm btn-warning font-s16 font-w300" style="padding: 10px 15px;line-height: 15px;">
		    Listado INFORMES
	    </a>
	</li>
<?php endif ?>

@endsection

<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
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
		cursor: pointer;
	}
	.fisioterapia{
	    background-color: #70b9eb;

	}
	.nutricion{
	    background-color: #46c37b;

	}
</style>

<div class="bg-white">
	<section class="content content-full">
		<div class="row">

			<div class="col-xs-12 col-md-8 push-30">
				<h2 class="text-center font-w300">
					Listado de Citas de <span class="font-w600"><?php echo $week->copy()->formatLocalized('%d de %B'); ?></span> al <span class="font-w600"><?php echo $week->copy()->endOfWeek()->formatLocalized('%d de %B'); ?></span>
				</h2>
			</div>
			<div class="col-md-4">
				<div class="col-xs-12 col-md-6 pull-right">
					<?php  
						$diff =  48;
						$monthly = $month->copy()->subMonth($month->copy()->format('n')-2)->startOfWeek();
					?>
					<select id="week" class="form-control" style="width: 100%;">
						<?php for ($i=1; $i <= $diff; $i++): ?>
							<?php 
								if ( $selectedWeek == $monthly->copy()->format("W")) {
									$selected = 'selected';
								}else{
									$selected = '';
								}
								
							?>

							<option value="<?php echo $monthly->copy()->format('Y-m-d'); ?>" <?php echo $selected ?>>
								Del <?php echo $monthly->copy()->formatLocalized('%d'); ?> al <?php echo $monthly->copy()->endOfWeek()->formatLocalized('%d'); ?> de <?php echo ucfirst($monthly->copy()->formatLocalized('%B')); ?>  
							</option>
							<?php $monthly->addWeek(); ?>
						<?php endfor; ?>
					</select>
				</div>
			</div>
			<div class="row push-30">
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
												
												<?php if ( $date->status == 1 && $date->charged == 1 ): ?>
													<div class="col-xs-12  items-push push-20 <?php echo strtolower($date->service->name) ?> <?php if($date->charged == 0){ echo "dateCharge"; } ?>" >
												<?php else: ?>
													<div class="col-xs-12  items-push push-20 <?php echo strtolower($date->service->name) ?> <?php if($date->charged == 0){ echo "dateCharge"; } ?>" data-toggle="modal" data-target="#modal-charge-date" type="button" data-idDate="<?php echo $date->id; ?>" data-id="<?php echo $date->id_user ?>">
												<?php endif ?>
							

												<div style="position: absolute;top: 0px; left: 5px;"">
													<?php if($date->charged == 0): ?>
														<i class="fa fa-circle text-danger" aria-hidden="true" style="border: 1px solid white;border-radius: 100%;"></i>
													<?php else: ?>
														<i class="fa fa-circle text-success" aria-hidden="true" style="border: 1px solid white;border-radius: 100%;"></i>
				           							<?php endif; ?>
												</div>
												<?php if ($date->charged == 0 && $date->status == 0): ?>
													<div style="position: absolute;top: -15px; right: -15px;  padding:1px 5px; z-index: 100">
														<a url="{{url('admin/citas/delete')}}/<?php echo $date->id; ?>" data-id="<?php echo $date->id_user ?>" onclick="return confirm('Estas seguro?');">
															<i class="fa fa-times fa-2x text-danger"></i>
														</a>
													</div>
												<?php endif ?>
												

												<div class="row push-0" style="margin-bottom: 0;">
													<div class="col-xs-4 h5 push-5 text-white">
														<?php echo date('H', strtotime($date->date)) ?>:00
													</div>
													<div class="col-xs-8 h5 font-w600 text-muted text-white">
														<?php echo $date->service->name; ?>
													</div>
													<div class="col-xs-12 push-5 text-center text-white">
														<b><?php echo ucfirst(strtolower(substr($date->user->name, 0,strpos($date->user->name," "))))."..."; ?></b> con (<b><?php echo ucfirst(strtolower(substr($date->coach->name, 0,strpos($date->coach->name," ")))); ?>)
													</div>
												</div>
											</div>
										<?php endforeach ?>
									<?php else: ?>
										<h3 class="h5 text-center font-w300 push-20">
											No hay Citas para Hoy
										</h3>
									<?php endif ?>
									<div class="row text-center">
										<div class="col-xs-12 text-muted addDate" style="cursor: pointer;" data-toggle="modal" data-target="#modal-add-date" type="button">
											<i class="fa fa-plus fa-3x"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php $weekDays->addDays(1); ?>
					<?php endfor; ?>
				</div>

				<?php $weekDays->addDays(1) ?>

				<div class="col-xs-12 col-md-8 push-30">
					<h2 class="text-center font-w300">
						Listado de Citas de <span class="font-w600"><?php echo $weekDays->copy()->formatLocalized('%d de %B'); ?></span> al <span class="font-w600"><?php echo $weekDays->copy()->endOfWeek()->formatLocalized('%d de %B'); ?></span>
					</h2>
				</div>

				<div class="col-md-12">
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
												
												<?php if ( $date->status == 1 && $date->charged == 1 ): ?>
													<div class="col-xs-12  items-push push-20 <?php echo strtolower($date->service->name) ?> <?php if($date->charged == 0){ echo "dateCharge"; } ?>" >
												<?php else: ?>
													<div class="col-xs-12  items-push push-20 <?php echo strtolower($date->service->name) ?> <?php if($date->charged == 0){ echo "dateCharge"; } ?>" data-toggle="modal" data-target="#modal-charge-date" type="button" data-idDate="<?php echo $date->id; ?>">
												<?php endif ?>
							

												<div style="position: absolute;top: 0px; left: 5px;"">
													<?php if($date->charged == 0): ?>
														<i class="fa fa-circle text-danger" aria-hidden="true" style="border: 1px solid white;border-radius: 100%;"></i>
													<?php else: ?>
				           								<i class="fa fa-circle text-success" aria-hidden="true" style="border: 1px solid white;border-radius: 100%;"></i>
				           							<?php endif; ?>
												</div>
												<?php if ($date->charged == 0 && $date->status == 0): ?>
													<div style="position: absolute;top: -15px; right: -15px;  padding:1px 5px; z-index: 100">
														<a url="{{url('admin/citas/delete')}}/<?php echo $date->id; ?>" onclick="return confirm('Estas seguro?');">
															<i class="fa fa-times fa-2x text-danger"></i>
														</a>
													</div>
												<?php endif ?>
												

												<div class="row push-0" style="margin-bottom: 0;">
													<div class="col-xs-4 h5 push-5 text-white">
														<?php echo date('H', strtotime($date->date)) ?>:00
													</div>
													<div class="col-xs-8 h5 font-w600 text-muted text-white">
														<?php echo $date->service->name; ?>
													</div>
													<div class="col-xs-12 push-5 text-center text-white">
														<b><?php echo ucfirst(strtolower(substr($date->user->name, 0,strpos($date->user->name," "))))."..."; ?></b> con (<b><?php echo ucfirst(strtolower(substr($date->coach->name, 0,strpos($date->coach->name," ")))); ?>)
													</div>
												</div>
											</div>
										<?php endforeach ?>
									<?php else: ?>
										<h3 class="h5 text-center font-w300 push-20">
											No hay Citas para Hoy
										</h3>
									<?php endif ?>
									<div class="row text-center">
										<div class="col-xs-12 text-muted addDate" style="cursor: pointer;" data-toggle="modal" data-target="#modal-add-date" type="button">
											<i class="fa fa-plus fa-3x"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php $weekDays->addDays(1); ?>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="modal-add-date" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-popin">
		<div class="modal-content">
			<div class="block block-themed block-transparent remove-margin-b">
				<div class="block-header bg-primary-dark">
					<ul class="block-options">
						<li>
							<button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
						</li>
					</ul>
				</div>
				<div class="row block-content" id="content-add-date">

				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-charge-date" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-popin">
		<div class="modal-content">
			<div class="block block-themed block-transparent remove-margin-b">
				<div class="block-header bg-primary-dark">
					<ul class="block-options">
						<li>
							<button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
						</li>
					</ul>
				</div>
				<div class="row block-content" id="content-charge-date">

				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('scripts') 
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
	jQuery(function () {
        App.initHelpers(['datepicker']);
    });
</script>
	<script type="text/javascript">
		$(document).ready(function() {

			$('.dateCharge').click(function(event) {
				event.preventDefault();
				var id = $(this).attr('data-idDate');

				$('#content-charge-date').empty().load('/admin/citas/getForm/cita/'+id);
			});

			$('.addDate').click(function(event){
				event.preventDefault();

				$('#content-add-date').load('/admin/citas/crear/nuevo');

			});

			$('#week').change(function(event) {
				var month = $(this).val();
				window.location = '/admin/citas/'+month;
			});

		});
	</script>
@endsection