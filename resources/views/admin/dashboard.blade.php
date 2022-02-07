@extends('layouts.admin-master')

@section('title') Dashboard Contabilidad Evolutio @endsection

@section('content')
<div class="bg-white">
	<section class="content content-full">
		<div class="row">
			<div class="col-md-4 col-xs-12 text-center" >
				<a class="block block-link-hover2" href="{{ url('admin/ingresos')}}" style="padding: 30px 20px;">
					<i class="fa fa-download fa-3x" aria-hidden="true"></i>
					<div class="block-content">
						<div class="h3 push-5">Ingresos</div>
					</div>
				</a>
			</div>
			<div class="col-md-4 col-xs-12 text-center">
				<a class="block block-link-hover2" href="{{ url('admin/gastos')}}" style="padding: 30px 20px;">
					<i class="fa fa-upload fa-3x" aria-hidden="true"></i>
					<div class="block-content">
						<div class="h3 push-5">Gastos</div>
					</div>
				</a>
			</div>
			<div class="col-md-4 col-xs-12 text-center">
				<a class="block block-link-hover2" href="{{ url('admin/perdidas-ganacias')}}" style="padding: 30px 20px;">
					<i class="fa fa-line-chart fa-3x" aria-hidden="true"></i>
					<div class="block-content">
						<div class="h3 push-5">Cuenta P y G</div>
					</div>
				</a>
			</div>
		</div>
	</section>
</div>
@endsection