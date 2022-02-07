@extends('layouts.admin-master')

@section('title') Nuevo horario - Evolutio HTS @endsection

@section('externalScripts')
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
@endsection

@section('content')
<div class="content bg-gray-lighter">
    <div class="col-xs-12">
        <div class="col-sm-5 text-left hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li><a class="link-effect" href="{{ url('/admin')}}">Admin</a></li>
                <li>Usuarios</li>
                <li>actualizar</li>
            </ol>
        </div>
    </div>
</div>
<div class="content content-full bg-gray-lighter">
	<div class="row">
	    <div class="col-md-12 push-30 push-t-30">
	        <div class="col-md-12">
			    <div class="row">
			        <div class="block col-md-6 col-md-offset-3 bg-white" style="padding: 20px;">
						@include('_form')
			        	
			        </div>
			    </div> 
            </div>
	    </div>
	</div>
</div>



@endsection


@section('scripts')
	<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
	<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
	<script>
	    jQuery(function () {
	        App.initHelpers(['datepicker', 'select2','summernote','ckeditor']);
	    });
	</script>
@endsection