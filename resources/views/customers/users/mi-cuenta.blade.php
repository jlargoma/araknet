<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.users-master')
@section('title') Mi Datos- Evolutio @endsection

@section('externalScripts')

@endsection

@section('body')
	<div class="row" style="margin: 30px 0;">
		<div class="col-md-2 col-xs-6 text-left">
			<a href="{{ url('/clientes') }}">Atrás</a>
		</div>
	</div>
	<div class="heading-block center">
		<h2>Mi cuenta</h2>
		<span>Aquí puedes revisar tus datos.</span>
	</div>
	<div class="row">
		<div class="col-xs-12">
			@include('users._partials._formUpdateCient')
		</div>
	</div>
@endsection


@section('scripts')

@endsection