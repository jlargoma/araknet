@extends('layouts.admin-master')

@section('title') Facturas @endsection
@section('headerTitle') Facturas @endsection
@section('headerButtoms')
   <a href="{{ route('invoice.edit',-1) }}" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
@endsection
@section('content')

<div class="content content-full bg-white">
      <h2 class="font-w300">DATOS PARA LA  <b>FACTURA
        @if($oInvoice->id>0)
        {{$oInvoice->num}}
        @endif
        </b></h2>
        @if($oInvoice->id>0)
      <h4>Fecha de Emisión: {{convertDateToShow_text($oInvoice->date)}}</h4>
      @endif
     <form action="{{ route('invoice.save') }}" method="post">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="id" value="{{$oInvoice->id ?? null}}">
    
      
      <h3 class=" invoice">Emisor:</h3>
      <div class="row bg-white mb-1em">
        <div class="col-md-8 col-xs-12 push-20">
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
        <div class="col-md-4 col-xs-12 push-20">
          <label for="">Fecha</label>
          <input name="date" class="js-datepicker form-control" type="text" id="date" name="fecha"
                           data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd"
                           value="{{$oInvoice->date ?? ''}}"
                           style="cursor: pointer;"
                           required="">
        </div>
      </div>
      <h3 class="invoice">Cliente:</h3>
      <div class="row bg-white">
          <div class="col-md-4 col-xs-12 push-20">
            <label for="">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{$oInvoice->name ?? ''}}" required="">
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
        <h3 class=" invoice">Items: <button class="btn pull-right btn-success" type="button" id="addItem" >+Item</button></h3>
        
        <div class="">
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
         <button class="btn btn-complete" type="submit" >Guardar</button>
        @if($oInvoice->id>0)
        <button class="btn btn-danger" type="button" id="delete" data-id="{{$oInvoice->id}}" >Eliminar</button>
        <a href="{{ route('invoice.downl',$oInvoice->id) }}" class="btn btn-success" target="_blank"><i class="fa fa-download"></i></a>
        <button class="btn btn-complete sendInvoiceEmail" type="button"  data-id="{{$oInvoice->id}}">Enviar</button>
        @endif
        <a class="btn btn-default" href="/admin/facturas" >Volver</a>
        
     </form>
</div>
@endsection

@section('scripts')
<style type="text/css">
  td{
    padding: 8px!important;
  }
  #tableInvoices .fa.order{
    font-size: 11px;
        color: #FFF;
  }
  #tableInvoices thead th{
        color: #FFF;
    text-align: center;
    background-color: #48b0f7;
  }
  #tableInvoices thead th::after{
    content: none !important;
    display: none !important;
  }
</style>
@include('invoices.forms._form-script')
@include('invoices.script_mail')
@endsection