<style type="text/css">
	.white > *{
		color: white!important;
	}
</style>
<div class="row">
	<div class="col-xs-12 white push-20">
		<h3 class="text-center"><?php echo strtoupper($rate->name) ?>(<?php echo $rate->max_pax; ?>)</h3>
	</div>
	<div class="col-xs-12 white">
		<p>Comprado: <?php echo date('d \d\e M \d\e Y', strtotime($userRates[0]->created_at)) ?></p>
	</div>
	<div class="col-xs-12 white">
		<?php if ( count($classes) > 0): ?>
			<?php foreach ($classes as $key => $classe): ?>
				<p class="text-left white">
					<?php echo $key+1; ?>  <?php echo date('d \d\e M', strtotime($classe->date_assistance)); ?>
				</p>
			<?php endforeach ?>
		<?php else: ?>
			<h3 class="text-center">No hay clases aun para esta tarifa</h3>
		<?php endif ?>
		<p class="text-center white">
			Sesiones pendientes de uso: <?php echo $rate->max_pax - count($classes) ?>
		</p>
	</div>
</div>