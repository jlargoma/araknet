@extends('layouts.popup')
@section('content')
@include('invoices.invoice-content')
<?php printInvoiceContent($oInvoice,$items); ?>
<div class="row col-xs-12  text-center">
<button class="btn btn-complete sendInvoiceEmail" type="button"  data-id="{{$oInvoice->id}}" data-email="{{$oInvoice->email}}">Enviar</button>
@if($oInvoice->charge_id>0)
<a class="btn btn-success" type="button" href="/admin/facturas/modal/editar/{{$oInvoice->charge_id}}" >Volver</a>
@endif
@if($oInvoice->user_id>0)
<a class="btn btn-success" type="button" href="/admin/usuarios/informe/{{$oInvoice->user_id}}/invoice" >Ficha Usuario</a>
@endif
</div>
@endsection
@section('scripts')
@include('invoices.invoice-style')
@include('invoices.script_mail')
@endsection