@extends('layouts.admin-master')

@section('title') Gestión asisitencia  Evolutio HTS @endsection
@section('content')
<style type="text/css">
	.table-header-bg.selected-table  > thead > tr > th, .table-header-bg.selected-table > thead > tr > td{
		color: #fff;
	    background-color: #3c763d!important;
	    border-bottom-color: #3c763d!important;
	}
</style>
<div class="content content-full bg-white">
	<div class="row push-30">
		<div class="col-xs-12 col-md-8">
			<h2 class="text-center font-w300">
				<?php setlocale(LC_TIME, "ES"); ?>
				<?php setlocale(LC_TIME, "es_ES"); ?>
				Control de <span class="font-w600"><?php echo ucfirst($date->copy()->formatLocalized('%B %Y'));  ?></span> para la<span class="font-w600"> asistencia</span> 
				<?php if ( $nameCoach != ''): ?>
					de <?php echo $nameCoach ?>
				<?php endif ?>
			</h2>
		</div>
		<div class="col-xs-12 col-md-2">
			<div class="col-xs-12 push-10">
				<select class="form-control" id="listCoach">
					<option>----</option>
					<?php foreach ($coachs as $key => $coach): ?>
						<?php if($coach->id == $idCoach){ $selected = "selected";}else{$selected = "";} ?>
						<option value="<?php echo $coach->id; ?>" <?php echo $selected ?>><?php echo $coach->name; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col-xs-12 col-md-2">
			<div class="col-xs-12 push-10">
				<select class="form-control" id="calendarTime">
					<?php $mes = $date->copy()->startOfYear();?>
					<?php for ($i=1; $i <= 12; $i++) : ?>
						<?php if( $date->format('n') == $i ){ $selected = "selected"; }else{ $selected = "";} ?>
						<option value="<?php echo $mes->format('Y-m') ?>" <?php echo $selected ?>>
							<?php echo ucfirst($mes->formatLocalized('%B')); ?>
						</option>
						<?php $mes->addMonth() ?>
					<?php endfor; ?>
				</select>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 push-30">
			
			<div id="calendar" class="col-xs-12 col-md-12">
			    <div class="panel panel-default">
			        <div class="calendar fc fc-ltr">
			            <table class="fc-header " style="width:100%; margin-bottom: 0">
			                <tbody>
			                    <tr class="">
			                        <td class="fc-header-center header-month"> 
			                        	<span class="fc-header-title">  
			                        		<h2><?php echo ucfirst( $date->copy()->formatLocalized('%B %Y')) ?></h2>          
			                        	</span>
			                        </td>
			                    </tr>
			                </tbody>
			            </table>
			            <div class="fc-content" style="position: relative; min-height: 1px;">
			                <div class="fc-view fc-view-month fc-grid" style="position: relative; min-height: 1px;" unselectable="on">
								<?php $startMonth = $date->copy()->startOfMonth()->startOfWeek(); ?>
			                	<?php $endMonth = $startMonth->copy()->endOfWeek(); ?>

			                    <?php $day = $startMonth->copy(); ?>
			                    <?php $dayMonth = $startMonth; ?>
			                    <table class="fc-border-separate" style="width:100%" cellspacing="0">
			                        <thead>
			                            <tr class="fc-last">
			                            	<?php for ( $i = 1; $i <= 7; $i++): ?>
			                            		<th class="fc-day-header fc-mon fc-widget-header header-week">
													<?php echo utf8_encode($dayMonth->formatLocalized('%a')); ?>
			                            		</th>
			                        			<?php $dayMonth->addDay() ?>
			                            	<?php endfor ?>
			                            </tr>
			                        </thead>
			                        <tbody>
			                            <?php for ( $i = 1; $i <= 5; $i++): ?>
			                                <tr>
			                                	<?php for ( $j = 1; $j <= 7; $j++): ?>
			                                        <td class="fc-day-header fc-mon fc-widget-header" style="height: 158px;">

			                                            <span class="pull-right font-s22 text-black">
			                                            	<?php echo $day->formatLocalized('%d') ?>	
			                                            </span>
			                                            <?php if ($j != 7): ?>
			                                            <div class="col-xs-12 not-padding">
			                                                <div class="col-xs-12">
			                                                   <?php 
																	$clientesApuntados = \App\Assistance::where('date_assistance', $day->copy()->format('Y-m-d 00:00:00'))
																		->get();
																		
																	$unAsignedClaseHoy = \App\GuestAssistance::where('date_assistance', $day->copy()->format('Y-m-d 00:00:00'))
																		->get();

			                                                        $total     = count($clientesApuntados) + count($unAsignedClaseHoy);
			                                                        $asistidos = 0;
			                                                        $ausentes  = 0;
			                                                        foreach ($clientesApuntados as $cliente):
			                                                            if ($cliente->assistance == 1) {
			                                                                $asistidos++;
			                                                            }else{
			                                                                $ausentes++;
			                                                            }
			                                                        endforeach;
			                                                        foreach ($unAsignedClaseHoy as $cliente):
			                                                            if ($cliente->assistance == 1) {
			                                                                $asistidos++;
			                                                            }else{
			                                                                $ausentes++;
			                                                            }
			                                                        endforeach;
			                                                    ?>
			                                                    <?php if ($asistidos >= 5): ?>
			                                                        <div class="text-white" style="padding: 10px; position: absolute; top: 0; left: 0; z-index: 20; background: red; border-radius: 20px;">
			                                                            <i class="fa fa-star"></i>
			                                                        </div>
			                                                    <?php endif ?>
			                                                    <div class="col-xs-12 font-w300 font-s18 text-center push-10" >
			                                                        <button class="btn btn-transparent btn-day-summary" data-toggle="modal" data-target="#modal-dia" type="button" data-date="<?php echo $day->format('Y-m-d') ?>" data-day="<?php echo $day->format('N') ?>" data-idCoach="<?php echo $idCoach ?>" style="height: auto; line-height: 1"> 
			                                                            <i class="fa fa-eye fa-2x"></i>
			                                                        </button>

			                                                    </div>
			                                                    <div class="col-xs-12 font-w300 font-s18 text-center" >
			                                                        <span class="">Asis.</span>/
			                                                        <span class="">Total</span>/
			                                                        <span class="">Ausen.</span>
			                                                    </div>
			                                                    <div class="col-xs-12 font-w300 font-s22 text-center" >
			                                                        <span class="text-success font-w600"><?php echo $asistidos ?></span>/
			                                                        <span class="text-primary font-w600"><?php echo $total ?></span>/
			                                                        <span class="text-danger font-w600"><?php echo $ausentes ?></span>
			                                                    </div>
			                                                </div>
			                                            </div>
			                                        	<?php else: ?>
		                                        			<div class="col-xs-12" style="width: 210px;"></div>
			                                            <?php endif ?>
			                                        </td>
			                                        <?php $day->addDay() ?>
			                                    <?php endfor ?>
			                                </tr>
			                            <?php endfor ?>
			                        </tbody>
			                    </table>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-dia" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-big" >
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <span class="text-white">Resumen del día</span>
                </div>
                <div class="row block-content" id="contentDia">
				
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$('#listCoach').change(function() {
		var date = $('#calendarTime').val();
		var coach = $(this).val();
		if (coach == '----') {
			window.location = '/admin/gestion-asistencia/';
		}else{
			window.location = '/admin/gestion-asistencia/'+coach+'/'+ date;
		}

		
	});
	$('#calendarTime').change(function() {
		var date = $(this).val();
		var coach = $('#listCoach').val();
		if (coach == '----') {
			window.location = '/admin/gestion-asistencia/0/'+ date;
		}else{
			window.location = '/admin/gestion-asistencia/'+coach+'/'+ date;
		}
		
	});

	$('.btn-day-summary').click(function(event) {
		var date = $(this).attr('data-date');
		var day = $(this).attr('data-day');
		var idCoach = $(this).attr('data-idCoach');
		$.get( "{{url('/admin/gestion-asistencia-dia/')}}/"+date+"/"+day, {idCoach: idCoach}).done(function( data ) {
		    // alert('llega');
		    $('#contentDia').empty().append(data);
		});
	});


	$('.selectHorarioMobileAsistencia').change(function(){
		var day = $(this).val();
		$('.boxDays').hide();
		$('#'+day).show();
	});
</script>
@endsection