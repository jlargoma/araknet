<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.users-master')
@section('title') Mis pagos- Evolutio @endsection

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
	<div class="row push-20">
		<div class="col-md-3 ">
			<button class="text-white btn btn-sm btn-primary font-s16 font-w300" style="padding: 10px 15px;line-height: 15px;" data-toggle="modal" data-target=".bs-example-modal-lg">
			    Añadir tarjeta
		    </button>
		</div>
		<div class="col-md-3"></div>
		<div class="col-md-3"></div>
		<div class="col-md-3"></div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-12 push-00">
			<h2 style="letter-spacing: -2px;" class="font-w300 push-10">Mis tarjetas</h2>
		</div>
		<div class="col-xs-12 col-md-12 push-20">

			<?php if ( count($cards['data']) > 0): ?>
				
				<table class="table table-condensed table-striped table-bordered">
					<tr>
						<th class="bg-primary text-center">Tipo (Terminación) </th>
						<th class="bg-primary text-center">Fecha caducidad</th>
						<th class="bg-primary text-center">Modelo de tarjeta</th>
						<th class="bg-primary text-center">Acciones</th>
					</tr>
					<?php foreach ($cards['data'] as $key => $card): ?>
						<tr>
							<td class="text-center">
								<?php echo $card['brand'] ?> <?php echo $card['country'] ?> (**** <?php echo  $card['last4']?>)
							</td>
							<td class="text-center"><?php echo  $card['exp_month']?>/<?php echo  $card['exp_year']?></td>
							<td class="text-center"><?php echo  $card['funding']?></td>
							<td class="text-center">
								<a href="{{ url('/clientes/cards/delete') }}/ <?php echo base64_encode($card['id']) ?>" class="text-danger">
									<i class="fa fa-times"></i>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				</table>
			
			<?php else: ?>

				<h2 style="letter-spacing: -2px;" class="font-w300 push-10">No hay tarjetas disponibles</h2>
				
			<?php endif ?>
			
		</div>
	</div>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-body">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 class="modal-title" id="myModalLabel" style="letter-spacing: -2px;">Nueva tarjeta</h3>
				</div>
				<div class="modal-body">
					@include('users._partials.cards._formCards')
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


@section('scripts')

@endsection