<?php

use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') INFORME DE HNTs - Araknet HTS @endsection

@section('externalScripts')
<style>
  .bg-complete {
    color: #fff !important;
    background-color: #5c90d2 !important;
    border-bottom-color: #5c90d2 !important;
    font-weight: 800;
    vertical-align: middle !important;
  }

  .openUser {
    font-weight: bold;
    cursor: pointer;
  }
 
  span.status {
    width: 10px;
    height: 10px;
    background-color: red;
    display: inline-block;
    border-radius: 50%;
    margin-left: 8px;
}
  span.status.online {
    background-color: green;
}

  .status {
    color: red;
  }
  .status.online {
  color: green;
  }
  .loadingJS{
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: #9191918c;
    color: #fff;
    display: none;
  }

</style>
<script type="text/javascript" src="/admin-css/assets/js/plugins/chartJs/Chart.min.js"></script>
@endsection
@section('content')
<div class="content content-boxed bg-gray-lighter">
  <h2 class="text-center">INFORME DE CONEXIONES</h2>
  <div id="containerTableResult">
    <table class="table  dataTable-i1">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Hotspot</th>
          <th>imac</th>
          <th>Dirección</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>PINGs</th>
        </tr>
      </thead>


      <tbody>
        @if($data)
        @foreach($data as $d)

        <?php
        $name = '';
        $address = $d->street;
        if (isset($aLstCust[$d->hotspot_imac])) {
          $customer = $aLstCust[$d->hotspot_imac];
          $name = '<a class="openUser" data-id="'.$customer->id.'" data-type="user">'. $customer->name.'<a>';
          if (trim($customer->address) != '')
            $address = $customer->address;
        }
        ?>
        <tr>
          <td><?php echo $name; ?><span class="status {{$d->status}}"></span></td>
          <td>{{$d->name}}</td>
          <td>{{$d->hotspot_imac}}</td>
          <td>{{$address}}</td>
          <td class="status {{$d->status}}">{{$d->status}}</td>
          <td>{{datetimeMin($d->updated_at)}}</td>
          <td><button type="button" class="btn btn-info sendPing" data-id="{{$d->id}}">PING</button></td>
        </tr>
        <?php
        ?>

        @endforeach
        @endif
      </tbody>
    </table>

  </div>
</div>

<div class="modal fade in" id="modalCliente" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog lg modal-md">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button" class="reload"><i class="si si-close"></i> Cerrar y refrescar</button>
            </li>
          </ul>
        </div>
        <div><iframe id="ifrCliente"></iframe></div>
      </div>
    </div>
  </div>
</div>
<div class="loadingJS text-center" style="padding: 150px 0;">
      <i class="fa fa-5x fa-circle-o-notch fa-spin"></i><br><span class="font-s36">CARGANDO</span>
    </div>

@endsection
@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ assetV('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>
<script type="text/javascript">
var dataTableInformMes = true;

$('#searchCustomer').keyup(function (evt) {
    var name = $(this).val().toUpperCase();
    console.log(name);
    $('.lstHNT').each(function () {
        var str = $(this).data('cname').toUpperCase();
        if (str.includes(name)) {
            $(this).show();
        } else
            $(this).hide();
    });
});

  $('#containerTableResult').on('click','.openUser',function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('#ifrCliente').attr('src','/admin/cliente/informe/' + id);
    $('#modalCliente').modal('show');
  });
  $('#containerTableResult').on('click','.sendPing',function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('.loadingJS').show();
    $.get('/admin/ping-to-Hotpots/'+id, function(resp){
    $('.loadingJS').hide();
      if (resp == 'OK') location.reload();
      if (resp == 'err01') alert('registro del Hotpots no encontrado');
      if (resp == 'err02') alert('Hotpots no responde');
      if (resp == 'err03') alert('La API no responde');
    });
  });
</script>
@endsection