<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.users-master')
@section('title') Mi cuenta- Evolutio @endsection

@section('externalScripts')

@endsection

@section('body')
<div class="heading-block center" style="margin: 50px 0">
	<h2>Bienvenido, {{ $user->name }}</h2>
	<span>Selecciona una de nuestras opciones.</span>
</div>
<div class="row">
	<div class="col-md-12 col-xs-12">
		
		<div class="col-md-3 col-xs-12">
			<a href="{{ url('/clientes/mi-contrato')}} ">
				<div class="col-xs-12 text-center">
					<img src="{{asset('/users/file.png')}}" class="img-responsive">
					<h3 class="text-center font-w300" style="letter-spacing:-2px; line-height:1;">
						Contrato
					</h3>
				</div>
			</a>
		</div>
		<div class="col-md-3 col-xs-12">
			<a href="{{ url('/clientes/mis-pagos')}} ">
				<div class="col-xs-12 text-center">
					<img src="{{asset('/users/cash.png')}}" class="img-responsive">
					<h3 class="text-center font-w300" style="letter-spacing:-2px; line-height:1;">
						Metodos de pago
					</h3>
				</div>
			</a>
		</div>
		<div class="col-md-3 col-xs-12">
			@if( $user->contractAccepted == 1)<a href="{{ url('/clientes/mi-servicios')}} ">@endif
				<div class="col-xs-12 text-center">
					<img src="{{asset('/users/list.png')}}" class="img-responsive">
					<h3 class="text-center font-w300" style="letter-spacing:-2px; line-height:1;">
						Mis servicios contratados
					</h3>
				</div>
			@if( $user->contractAccepted == 1)</a>@endif
		</div>
		<div class="col-md-3 col-xs-12">
			@if( $user->contractAccepted == 1)<a href="{{ url('/clientes/mi-cuenta')}} ">@endif
				<div class="col-xs-12 text-center">
					<img src="{{asset('/users/users.png')}}" class="img-responsive">
					<h3 class="text-center font-w300" style="letter-spacing:-2px; line-height:1;">
						Mi cuenta
					</h3>
				</div>
			@if( $user->contractAccepted == 1)</a>@endif
		</div>
	</div>
</div>

@endsection


@section('scripts')

@endsection