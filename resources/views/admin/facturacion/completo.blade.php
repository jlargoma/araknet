@extends('layouts.admin-master')

@section('title') Nuevo horario - Araknet HTS @endsection

@section('externalScripts')

@endsection

@section('content')
<div class="content bg-gray-lighter">
    <div class="col-xs-12">
        <div class="col-sm-5 text-left hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li><a class="link-effect" href="{{ url('/admin')}}">Admin</a></li>
                <li><a class="link-effect" href="{{ url('/admin/facturacion/entrenadores')}}">Facturacion</a></li>
            </ol>
        </div>
    </div>
</div>
<div>
	<p style="margin-top: 25%">
		<h1 class="text-center">Estan todos los profesores asignados</h1>
	</p>
</div>
@endsection


@section('scripts')

@endsection