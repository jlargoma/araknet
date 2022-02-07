@extends('layouts.admin-master')

@section('title') Importar Clientes Servicios @endsection
@section('headerTitle') Importar Clientes Servicios @endsection
@section('content')
<div class="content content-full bg-white">
  <form action="{{ url('/admin/citas/create') }}" method="post" id="formEdit">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <div class="row">
      <div class="col-xs-12 col-md-4 push-20">
        <div class="col-xs-12 col-md-8 push-20">
          <label>Servicio</label>
          <div >
            <select class="form-control" id="id_rate" name="id_rate">
              <option></option>
              <?php foreach ($aRates as $group):?>
              <optgroup label="{{$group['name']}}">
              <?php foreach ($group['lst'] as $rate): ?>
                <option value="<?php echo $rate->id; ?>" data-price="{{$rate->price}}">
                  <?php echo $rate->name; ?>
                </option>
              <?php endforeach ?>
                </optgroup>
              <?php endforeach ?>
            </select>
          </div>
          <input class="form-control" type="text" id="u_name" name="u_name" placeholder="Nombre del usuario" style="display:none"/>
        </div>
        <div class="col-xs-12 col-md-4 push-20">
          <label>Precio</label>
          <input class="form-control" type="text" id="price" name="price" placeholder="precio"/>
        </div>
         <div class="col-xs-12 col-md-12 push-20">
          <button class="btn btn-success" type="button">Importar</button>
        </div>
      </div>
      <div class="col-xs-12 col-md-8 push-20"> 
        <div class="col-xs-12 col-md-8 push-20">
          <div id="dualSelectExample" style="width:98%; height: 80vh; background-color:#F0F0F0; padding:10px;"></div><br>
        </div>
        <div class="col-xs-2 col-md-4 push-20">
          <label>Nuevo Usuario</label>
          <input class="form-control" type="text" id="addIterms" name="addIterms" placeholder="Nombre del usuario"/>
          <div id="addSel" class="btn btn-success" style="display: block">Agregar Usuario</div>
        </div>

       
      </div>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" type="text/css" href="/vendor/bala.DualSelectList/css/bala.DualSelectList.css">
<script type="text/javascript" src="/vendor/bala.DualSelectList/js/bala.DualSelectList.jquery.js"></script>
<script type="text/javascript">
$(document).ready(function () {
var dsl = $('#dualSelectExample').DualSelectList({

// Change Item from pure String to an Json Object.
// the "value" field will be displayed on the list.
'candidateItems': [
  <?php foreach ($aUsers as $uid=>$u_name): ?>
           {'id': {{$uid}}, 'value': '{{strtolower($u_name)}}'},
  <?php endforeach ?>
  ],
'selectionItems': [],
'colors': {

  'itemHoverBackground': '#45c37b'
}
});

$('#getSel').click(function () {
var strOnly = $('#strOnly').prop("checked");
var res = dsl.getSelection(strOnly);
var str = '';
for (var n = 0; n < res.length; ++n)
  str += JSON.stringify(res[n]) + '\n';
$('#selResult').val(str);
});

$('#addSel').click(function () {
var items = $('#addIterms').val().split('\n');
var res = dsl.setCandidate(items);
$('#addIterms').val('');
});
$('#id_rate').on('change',function () {
var price = $(this).find(':selected').data('price')
$('#price').val(price);
});
});
</script>
@endsection