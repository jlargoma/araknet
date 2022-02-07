<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<div class="row">
	<div class="col-xs-12">
		<h2 class="text-center font-w300 push-20">
			El cliente <span class="font-w600"><?php echo $date->user->name; ?></span>
		</h2>
		<?php if ($hasBond == 1): ?>
			<p class="text-justify">
				Tiene un Bono de <b><?php echo $date->service->name; ?></b> contratado el
				<b><?php echo ucfirst(Carbon::createFromFormat('Y-m-d H:i:s', $rateBondAssinned->created_at)->formatLocalized('%A %d de %B de %Y')); ?></b>
				del cual le quedan <b><?php echo $seasonsAvaliables; ?> <?php if ($seasonsAvaliables == 1) { echo "SESION"; }else{ echo "SESIONES";} ?></b>
			</p>
		<?php else: ?>
			<p class="text-justify">
				No tiene ningun <b>BONO</b> contratado. 
			</p>
		<?php endif ?>
	</div>
	<div class="col-xs-12 push-20" >
		
		<?php if ($hasBond == 0): ?>
			@include('admin/dates/_noAbonned')
		<?php else: ?>
			<h2 class="text-center push-20">
				Formas de pago
			</h2>
			@include('admin/dates/_abonned')
		<?php endif ?>
		
		@include('admin/dates/_invited')

	</div>
</div>