@extends('layouts.popup')
@section('content')
<div class="content" style="max-width:975px;">
  <h2 class="text-center push-20"> ASIGNAR Y GENERAR COBRO PARA <?php echo strtoupper($customer->name) ?></h2>
  <form class="form-toPayment" method="post" action="{{ url('/admin/cobros/cobrar-cliente') }}">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="customer_id" value="<?php echo $customer->id; ?>">
    <div class="row">
      <div class="col-xs-6 col-md-4 push-20">
        <label for="rate_id">Tarifa</label>
        <select class="form-control" id="rate_id" name="rate_id" style="width: 100%; cursor: pointer"
                placeholder="Seleccione tarifas.." required="">
          <option></option>
          <?php
          $old = old('rate_id');
            foreach ($rateFamily as $k=>$v):
              echo '<optgroup label="'.$v['n'].'">';
              foreach ($v['l'] as $rate):
                $sel = ($rate->id == $old) ? 'selected' : '';
                         
                $price = $rate->price;
              
                ?>
                
                <option value="<?php echo $rate->id ?>" 
                    data-price="<?php echo $price ?>"
                    orig="<?php echo $rate->price ?>"
                    {{$sel}}>
                <?php echo $rate->name ?>
                </option>
                <?php
              endforeach;
              echo '</optgroup>';
            endforeach; 
            ?>
        </select>
      </div>

       <div class="col-xs-6 col-md-3 push-20">
        <label for="rate_id">Personal</label>
        <select class="form-control" id="user_id" name="user_id" style="width: 100%; cursor: pointer"
                placeholder="Personal asignado" >
          <option value="null">--</option>
          <?php
          $old = old('user_id');
          foreach ($coachs as $v):
            $sel ='';
            ?>
            <option value="<?php echo $v->id ?>">
            <?php echo $v->name ?>
            </option>
            <?php
          endforeach;
          ?>
        </select>
      </div>
      <div class="col-xs-6 col-md-2 push-20">
        <label for="date_payment">Fecha de cobro</label>
        <input class="js-datepicker form-control" type="text" id="date_payment" name="date_payment"
               data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" value="{{ old('date_payment',date('d-m-Y'))}}"
               style="cursor: pointer;" required="">
      </div>
      <div class="col-xs-6 col-md-1 push-20">
        <label for="type_payment">% DTO</label>
        <input type="text" id="discount" name="discount"  class="form-control only-number" value="{{ old('discount') }}"/>
      </div>
      <div class="col-xs-6 col-md-2 push-20">
        <label>Total</label>
        <div class="pull-left">
          <input id="importeFinal" type="number" step="0.01" name="importe" class="form-control" value="{{ old('importe') }}"/>
        </div>
      </div>
    </div>
    <div class="text-center" id="showTartifa"></div>
    
      <div class="row">
          <div class="col-md-6 col-xs-12 push-20">
            <div class="box-payment-card">
            <h4>PAGAR AHORA</h4>
            <div class="row">
              <div class="col-xs-9">
              <?php $old = old('type_payment', 'card'); ?>
              <select class="likeBtn" name="type_payment" id="type_payment" multiple>
                <option value="card" <?php if ($old == 'card') echo 'selected'; ?>>Tarjeta</option>
                <option value="cash" <?php if ($old == 'cash') echo 'selected'; ?>>Efectivo</option>
                <option value="banco" <?php if ($old == 'banco') echo 'selected'; ?>>Banco</option>
              </select>
              </div>
              <div class="col-xs-3">
                <button class="btn btn-lg btn-success" type="submit" id="submitFormPayment" style="margin-left: -1em;">
                  PAGAR
                </button>
              </div>
            </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 push-20">
          </div>
        <input type="hidden" id="importeCobrar">
      </div>

  </form>
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
  var origPrice = 0;
  $('#rate_id').change(function (event) {
    var that = $("#rate_id option:selected");
    var price = that.data('price');
    $('#importeFinal').val(price);
    origPrice = price;
  });

  $('#discount').change(function (event) {
    var discount = $(this).val();
    var percent = discount / 100;

    $('#importeFinal').val(origPrice - (origPrice * percent));

  });

  $('.only-number').keydown(function (e) {
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        (e.keyCode >= 35 && e.keyCode <= 40)) {
      return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
      e.preventDefault();
    }
  });

  
});
</script>
@endsection