<?php

use \Carbon\Carbon;

setlocale(LC_TIME, "ES");
setlocale(LC_TIME, "es_ES");
?>
@extends('layouts.admin-master')

@section('title') Administrador de reservas MiramarSKI @endsection

@section('externalScripts')
@include('invoices.forms._form-script')

<script>
$(function () {
  $('#sendInvoiceEmail').on('click',function (e){
    e.preventDefault();
    e.stopPropagation();
    if(confirm('Enviar factura a '+ $('#email').val() +'?')){
      $('#loadigPage').show('slow');
       $.ajax({
        url: '/admin/facturas/enviar',
          type: 'POST',
          data: {
            id: $(this).data('id'),
            _token: "{{csrf_token()}}"
          }
        })
        .done(function () {
          window.show_notif('Ok', 'success', 'Factura enviada');
        })
        .fail(function () {
          window.show_notif('Ok', 'danger', 'Factura no enviada');
        })
        .always(function () {
          $('#loadigPage').hide('slow');
        });
      }
    });
  });
</script>
@endsection

@section('content')

<div class="container-fluid padding-25 sm-padding-10 bg-white">
  <div class="container clearfix">
    <div class="col-xs-12 text-left push-30">
      <h2 class="font-w300">DATOS PARA LA  <b>FACTURA
        @if($oInvoice->id>0)
        {{$oInvoice->num}}
        @endif
        </b></h2>
        @if($oInvoice->id>0)
      <h4>Fecha de Emisión: {{convertDateToShow_text($oInvoice->date)}}</h4>
      @endif
    </div>
    <form action="{{ route('invoice.save') }}" method="post">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="id" value="{{$oInvoice->id ?? null}}">
    
      
      <h3 class="row col-xs-12 invoice">Emisor:</h3>
      <div class=" row col-xs-6 bg-white mb-1em">
        <label>Data Fiscal</label>
        <select name="emisor" class="form-control">
          <option value="">--</option>
          @if($emisores)
          @foreach($emisores as $k=>$item)
          <option value="{{$k}}" <?php echo ($emisor == $k) ? 'selected':''; ?>>{{$item['name']}}</option>
          @endforeach
          @endif
        </select>
      </div>
      <div class=" row col-xs-6 bg-white mb-1em">
        <label>Alojamiento</label>
        <select name="roomID" class="form-control">
          <option value="">--</option>
          @if($aRoomsLst)
          @foreach($aRoomsLst as $k=>$name)
          <option value="{{$k}}" <?php echo ( $roomID == $k) ? 'selected':''; ?>>{{$name}}</option>
          @endforeach
          @endif
        </select>
      </div>
      <h3 class="row col-xs-12 invoice">Cliente:</h3>
      <div class="row col-xs-12 bg-white">
          <div class="col-md-4 col-xs-12 push-20">
            <label for="">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{$oInvoice->name ?? ''}}">
          </div>
          <div class="col-md-4 col-xs-12 push-20">
            <label for="">CIF/NIF/DNI/NIE</label>
            <input type="text" name="nif" class="form-control" value="{{$oInvoice->nif ?? ''}}">
          </div>
          <div class="col-md-4 col-xs-12 push-20">
            <label for="">Email</label>
            <input type="text" name="email" id="email" class="form-control" value="{{$oInvoice->email ?? ''}}">
          </div>
          <div class="col-md-4 col-xs-12 push-20">
            <label for="">Dirección</label>
            <input type="text" name="address" class="form-control" value="{{$oInvoice->address ?? ''}}">
          </div>
          <div class="col-md-4 col-xs-12 push-20">
            <label for="">Telefono</label>
            <input type="number" name="phone" class="form-control" value="{{$oInvoice->phone ?? ''}}">
          </div>
        </div>
        <h3 class="row col-xs-12 invoice">Items: <button class="btn pull-right" type="button" id="addItem" >+Item</button></h3>
        
        <div class="row col-xs-12">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th width="50%">Item</th>
                  <th class="text-center">% IVA</th>
                  <th class="text-center">Total</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="itemInvoices">
                <?php 
                  if($items):
                    foreach($items as $item):
                    ?>
                  <tr>
                    <td><textarea name="item[]" class="form-control itemname">{{$item['detail']}}</textarea></td>
                    <td><input type="number" step="0.01" name="iva[]" class="form-control iva" value="{{$item['iva']}}"></td>
                    <td><input type="number" step="0.01" name="price[]" class="form-control prices" value="{{$item['price']}}"></td>
                    <td><button type="button" class="rmItem">x</button></td>
                  </tr>                    
                    <?php
                    endforeach;
                  else:
                  ?>
                  <tr>
                    <td><textarea type="text" name="item[]" class="form-control itemname"></textarea></td>
                    <td><input type="number" step="0.01" name="iva[]" class="form-control iva" value="10"></td>
                    <td><input type="number" step="0.01" name="price[]" class="form-control prices"></td>
                    <td><button type="button" class="rmItem">x</button></td>
                  </tr>
                  <?php
                  endif;
                  ?>
              </tbody>
              <tfoot id="summary">
                
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <div class="row col-xs-12  text-center">
        
        <button class="btn btn-complete" type="submit" >Guardar</button>
        @if($oInvoice->id>0)
        <button class="btn btn-danger" type="button" id="delete" data-id="{{$oInvoice->id}}" >Eliminar</button>
        <a href="{{ route('invoice.downl',$oInvoice->id) }}" class="btn btn-success"><i class="fa fa-download"></i></a>
        <button class="btn btn-complete" type="button" id="sendInvoiceEmail" data-id="{{$oInvoice->id}}">Enviar</button>
        @endif
        <a class="btn btn-default" href="/admin/facturas" >Volver</a>
      </div>
    </form>
  </div>

@endsection