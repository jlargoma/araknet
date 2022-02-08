@extends('layouts.popup')
@section('content')
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<h2 class="text-center font-w300">
  ACTUALIZAR COBRO DE <b>{{$date}}</b> A <span class="font-w600"><?php echo strtoupper($user->name); ?></span>
</h2>
<?php 
$disableType = ($charge->type_payment == "card");
?>
<h3 class="text-center font-w300 mt-1">
  Cuota a actualizar <span class="font-w600"><?php echo $rate->typeRate->name.': '.$rate->name; ?></span>
</h3>
<?php 
  $price = $rate->price;
?>
<form class="fomr-horizontal content-md" method="post" action="{{ url('/admin/cobros/cobrar/' . $charge->id) }}" id="forms">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" id="importeOrig" value="<?php echo $price; ?>">
  <input type="hidden" id="deleted" name="deleted" value="0">
  <p class="text-center font-w600 font-s32" >
    <span class="text-danger"><?php echo $price; ?>€</span>
  </p>
  <input type="hidden" id="id_tax" name="id_tax" class="form-control" value="<?php echo $rate->id; ?>" />	 
  <div class="row">
    <div class="col-md-4">
      <label for="type_payment">Forma de pago</label>
      <select class="form-control" name="type_payment" id="type_payment" 
        <?php if($disableType) echo 'disabled'; ?>>
        <option value="card" @if ($charge->type_payment == "card") selected @endif>Tarjeta</option>
        <option value="cash" @if ($charge->type_payment == "cash") selected @endif>Efectivo</option>
        <option value="banco" @if ($charge->type_payment == "banco") selected @endif>Banco</option>
      </select>
    </div>
    
    <div class="col-md-3">
        <label for="id_rate">Personal</label>
        <select class="form-control" id="id_coach" name="id_coach" style="width: 100%; cursor: pointer"
                placeholder="Personal asignado" required="">
          <option></option>
          <?php
          $old = old('id_coach');
          foreach ($coachs as $v):
            $sel = ($coach_id == $v->id) ? 'selected' : '';
            ?>
          <option value="<?php echo $v->id ?>" <?php echo $sel; ?>>
            <?php echo $v->name ?>
            </option>
            <?php
          endforeach;
          ?>
        </select>
      </div>
    
    
    <div class="col-md-2">
      <label>Dto</label>
      <input type="number" id="discount" name="discount" class="form-control" value="{{$charge->discount}}" />
    </div>
    <div class="col-md-3">
      <label>Total</label>
      <input id="importeFinal" type="number" step="0.01" name="importe" class="form-control" value="{{ $charge->import}}" />
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-md-4 col-xs-4 text-center">
      <button class="btn btn-info btn-lg " id="open_invoice" type="button" data-id="{{$charge->id}}" title="Factura">
        <i class="fa fa-files-o"></i> Factura
      </button>
    </div>
    <div class="col-md-4 col-xs-4 text-center">
      <button class="btn btn-lg btn-success" type="submit">
        Actualizar
      </button>
    </div>
    <div class="col-md-4 col-xs-4  text-center">
      <button class="btn btn-lg btn-danger" type="button" id="delete">
        Eliminar
      </button>
    </div>
  </div>
</div>
</form>
<div class="text-center mt-1em">
<span>Creado {{$charge->created_at}}</span>
</div>
@endsection
@section('scripts')
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/moment.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
jQuery(function () {
  App.initHelpers(['datepicker', 'select2']);
});
$(document).ready(function () {
  $('#discount').change(function (event) {
    var discount = $(this).val();
    var importe = $('#importeOrig').val();
    var percent = discount / 100;

    $('#importeFinal').val(importe - (importe * percent));

  });
  $('#delete').on('click', function (event) {
    if (confirm('Eliminar cobro?')) {
      $('#deleted').val(1);
      $('#forms').submit();

    }

  });
  //INVOICE
  $('#open_invoice').on('click', function (event) {
    var bID = $(this).data('id');
    location.href = '/admin/facturas/modal/editar/' + bID;
  });
});
</script>
@endsection