<div class="row">
	<div class="col-xs-12">
	<?php if (count($horarios) > 0): ?>
		<?php foreach ($horarios as $key => $horario): ?>
			<div class="col-md-3 col-xs-12">
				<div class="col-xs-12 clase-bordered <?php echo str_replace(' ', '-', strtolower($horario->classes->name)) ?>" style="margin-bottom:10px;" >
					<span class="hora pull-left">
						<?php if ( $horario->classes->hour > 10 ): ?>
							0<?php echo $horario->hour ?>
						<?php else: ?>
							<?php echo $horario->hour ?>
						<?php endif ?>
						: 00
					</span>
					<?php 
						$apuntadosClaseHoy = \App\Assistance::where('date_assistance', $date->format('Y-m-d 00:00:00'))
							->where('id_schedule', $horario->id)
							->get();
							
						$unAsignedClaseHoy = \App\GuestAssistance::where('date_assistance', $date->format('Y-m-d 00:00:00'))
							->where('id_schedule', $horario->id)
							->get();
					?>
					<div class="col-xs-12 push-0">
						<h3 class="text-center push-0" title="<?php echo $horario->classes->name ?>">
							<?php echo $horario->classes->name ?>
						</h3>
					</div>
					<?php 
						$total = count($apuntadosClaseHoy) + count($unAsignedClaseHoy) ;
						$asistidos = 0;
						$ausentes = 0;

						foreach ($apuntadosClaseHoy as $apuntado) {
							if ($apuntado->assistance == 1) {
								$asistidos++;
							}else{
								$ausentes++;
							}
						}
						foreach ($unAsignedClaseHoy as $apuntado) {
							if ($apuntado->assistance == 1) {
								$asistidos++;
							}else{
								$ausentes++;
							}
						}
					?>
					<div class="col-xs-12 text-center push-20">
						<span class="text-primary font-s18 font-w600">
							<?php echo $total; ?>
						</span>  Clien.
						<span class="text-success font-s18 font-w600">
							<?php echo $asistidos ?>
						</span>  Clien.
						<span class="text-danger font-s18 font-w600">
							<?php echo $ausentes ?>
						</span>  Clien.
					</div>
				</div>
			</div>
		<?php endforeach ?>
	<?php else: ?>
		<div class="col-xs-12" style="padding: 30px;">
			<h2 class="text-muted text-center font-w300">
				No hay clases para este dia en este Coach
			</h2>
		</div>
	<?php endif ?>
		
	</div>
</div>