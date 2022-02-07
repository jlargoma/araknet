@extends('layouts.admin-master')

@section('title') Importar Clientes Servicios @endsection
@section('headerTitle') Importar Clientes Servicios @endsection
@section('content')

<div class="row">
  <form action="{{ url('/admin/citas/create') }}" method="post" id="formEdit">
    @if($id>0)            			
    <input type="hidden" name="idDate" value="{{$id}}">
    @endif
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="date_type" value="fisio">
    <div class="row">
      <div class="col-xs-12 col-md-4 push-20">
        <label for="id_user" id="tit_user">
          @if($id<1) 
          <i class="fa fa-plus" id="newUser"></i>
          @endif
          Cliente</label>
        <div >
          <select class="form-control" id="id_rate" name="id_rate">
            <option></option>
            <?php foreach ($aRates as $group): ?>
            <optgroup>{{$group['name']}}</optgroup>
            <?php foreach ($group['lst'] as $rate): ?>
              <option value="<?php echo $rate->id; ?>" data-price="{{$rate->price}}">
                <?php echo $rate->name; ?>
              </option>
            <?php endforeach ?>
            <?php endforeach ?>
          </select>
        </div>
        <input class="form-control" type="text" id="u_name" name="u_name" placeholder="Nombre del usuario" style="display:none"/>
      </div>
      <div class="col-xs-12 col-md-4 push-20"></div>
      <div class="col-xs-12 col-md-4 push-20"></div>
      <div class="col-xs-12 col-md-12 push-20">
        <div id="dualSelectExample" style="width:500px; height:300px; background-color:#F0F0F0; padding:10px;"></div><br>
      </div>
      <div class="col-xs-12 col-md-12 push-20">
        <button class="btn btn-success">Importar</button>
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
'candidateItems': [{'id': 0, 'value': 'Item 01'},
  {'id': 1, 'value': 'Item 02'},
  {'id': 2, 'value': 'Item 03'},
  {'id': 3, 'value': 'Item 04'},
  {'id': 4, 'value': 'Item 05'}],
'selectionItems': [{'id': 5, 'value': 'Item 06'},
  {'id': 6, 'value': 'Item 07'},
  {'id': 7, 'value': 'Item 08'}],
'colors': {
  'itemText': 'white',
  'itemBackground': 'rgb(0, 51, 204)',
  'itemHoverBackground': '#0066ff'
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
});
</script>
@endsection