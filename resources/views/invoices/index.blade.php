@extends('layouts.admin-master')

@section('title') Facturas @endsection
@section('headerTitle') Facturas @endsection
@section('headerButtoms')
   <a href="{{ route('invoice.edit',-1) }}" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
@endsection
@section('content')

<div class="content content-full bg-white">
	<div class="row">
     <div class="col-md-6 col-xs-12 text-left">
      <h2 class="font-w300" style="margin: 0">LISTADO DE <b>FACTURAs ({{moneda($totalValue)}}) </b></h2>
    </div>
    <div class="col-md-3 pull-right col-xs-12 text-left">
      <a href="{{ url('admin/facturas/descargar-todas') }}" class="text-white btn btn-md btn-primary" target="_black">
        Descargar Todas
      </a>
    </div>
    </div>
    <div class="row">
        <div class="loading text-center" style="padding: 150px 0;">
            <i class="fa fa-5x fa-circle-o-notch fa-spin"></i><br><span class="font-s36">CARGANDO</span>
        </div>
        <div class="col-md-12" id="containerTableResult" style="display: none;">
           @include('invoices._table')
        </div>
    </div> 
</div>
@endsection

@section('scripts')
@include('invoices.script')
@include('invoices.script_mail')
@endsection