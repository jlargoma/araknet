@extends('layouts.popup')
@section('content')
@if($oInvoice->id>0)
  <div class="row mb-1em">
    <div class="col-xs-6 text-left">Núm de Factura: <b>{{$oInvoice->num}}</b></div>
    <div class="col-xs-6 text-right">Fecha de Emisión: <b>{{convertDateToShow_text($oInvoice->date)}}</b></div>
  </div>
    @endif
    
    <form action="{{ route('invoice.save') }}" method="post" id="sendInvoiceBook">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="id" value="{{$oInvoice->id ?? null}}">
      <input type="hidden" name="charge_id" value="{{$charge_id}}">
      <input type="hidden" name="user_id" value="{{$oInvoice->user_id ?? null}}">
      <input type="hidden" name="confirm" value="1">
    
      
      <h3 class=" invoice">Emisor:</h3>
      <div class="row  bg-white mb-1em">
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
                           value="{{$oInvoice->date ?? date('Y-m-d')}}"
                           style="cursor: pointer;"
                           required="">
        </div>
      </div>
      
      <h3 class=" invoice">Cliente:</h3>
      <div class="row bg-white">
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
            <input type="text" name="email" class="form-control" value="{{$oInvoice->email ?? ''}}">
          </div>
          <div class="col-md-4 col-xs-12 push-20">
            <label for="">Dirección</label>
            <input type="text" name="address" class="form-control" value="{{$oInvoice->address ?? ''}}">
          </div>
          <div class="col-md-4 col-xs-12 push-20">
            <label for="">Telefono</label>
            <input type="text" name="phone" class="form-control" value="{{$oInvoice->phone ?? ''}}">
          </div>
        </div>
        <h3 class=" invoice">Items: <button class="btn pull-right" type="button" id="addItem" >+Item</button></h3>
        
        <div class="">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th width="65%">Item</th>
                  <th class="text-center" width="5%">% IVA</th>
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
                    <td><input type="number" step="0.01" name="price[]" class="form-control prices"  value="{{$item['price']}}"></td>
                    <td><button type="button" class="rmItem">X</button></td>
                  </tr>                    
                    <?php
                    endforeach;
                  else:
                  ?>
                  <tr>
                    <td><textarea type="text" name="item[]" class="form-control itemname"></textarea></td>
                    <td><input type="number" step="0.01" name="iva[]" class="form-control iva"></td>
                    <td><input type="number" step="0.01" name="price[]" class="form-control prices"></td>
                    <td><button type="button" class="rmItem">X</button></td>
                  </tr>
                  <?php
                  endif;
                  ?>
              </tbody>
               <tfoot id="summary"></tfoot>
            </table>
          </div>
        </div>
      </div>
      <div class="  text-center">
        
        <button class="btn btn-complete" type="submit" >Guardar</button>
        @if($oInvoice->id>0)
        <button class="btn btn-danger" type="button" id="delete" data-id="{{$oInvoice->id}}" >Eliminar</button>
        <a href="{{ route('invoice.downl',$oInvoice->id) }}" class="btn btn-success"><i class="fa fa-download"></i></a>
        @endif
        @if($oInvoice->user_id>0)
        <a href="/admin/usuarios/informe/{{$oInvoice->user_id}}/invoice" class="btn btn-success">Volver</a>
        @endif
      </div>
    </form>

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
  button#addItem {
    color: black;
    font-size: 15px;
}
.prices{
  text-align: right;
}
</style>
@include('invoices.forms._form-script')
@include('invoices.script_mail')
@endsection
  