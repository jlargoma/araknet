<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.users-master')
@section('title') Mi clases- Araknet @endsection

@section('externalScripts')

@endsection

@section('body')
<div class="row" style="margin: 30px 0;">
	<div class="col-md-2 col-xs-6 text-left">
		<a href="{{ url('/clientes') }}">Atrás</a>
	</div>
</div>
<div class="heading-block center">
	<h2>Mis metodos de pago</h2>
	<span>Aquí puedes ver los metodos de pago, donde te cobraremos periodicamente tu tarifa.</span>
</div>
<div class="col-xs-12 col-md-12 push-00">
	<h2 style="letter-spacing: -2px;" class="font-w300 push-10">Mis suscripciones</h2>
</div>
<div class="col-xs-12 col-md-12 push-20">
	<?php if ( count($userSuscription['data']) > 0): ?>
		<?php foreach ($userSuscription['data'] as $key => $suscription): ?>
			<div class="col-xs-12 col-md-12 div-suscriptions">
				<?php if ($suscription['status'] == 'active'): ?>
					<div class="suscription-status">
						<i class="fa fa-circle fa-2x text-success" aria-hidden="true"></i>
					</div>
				<?php else: ?>
					<div class="suscription-status">
						<i class="fa fa-circle fa-2x text-danger" aria-hidden="true"></i>
					</div>
				<?php endif ?>
				
				<div class="col-md-8 col-xs-8">
					<h3 class="text-left push-0" style="letter-spacing: -2px;">
						<?php echo $suscription['plan']['name'] ?> 
					</h3>
					Suscripto desde: <?php echo ucfirst(Carbon::createFromTimestamp($suscription['start'])->formatLocalized('%d %B %Y')); ?>
				</div>
				<div class="col-md-4 col-xs-4 text-center">
					<h3 class="text-center push-0" style="letter-spacing: -2px;">
						<?php echo ($suscription['plan']['amount']/100) ?>€ / 
						<?php if ($suscription['plan']['interval_count'] > 1): ?>
							<?php echo $suscription['plan']['interval_count'] ?> Meses
						<?php else: ?>
							Mes
						<?php endif ?>
					</h3>
					<a href="{{ url('/clientes/services/cancelSuscription') }}/<?php echo base64_encode($suscription['id']) ?>" class="button button-3d button-mini button-rounded button-red">
						Cancelar suscripcion
					</a>
				</div>
			</div>
		<?php endforeach ?>
	<?php else: ?>

		<h2 style="letter-spacing: -2px;" class="font-w300 push-10">No hay suscripciones disponibles</h2>
		
	<?php endif ?>
	
</div>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-body">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">Nueva tarjeta</h4>
				</div>
				<div class="modal-body">
					<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
					<p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
					<p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
					<p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
					<p class="nobottommargin">Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


@section('scripts')

@endsection