<?php 
  $price = $rate->price;
?>
@extends('layouts.popup')
@section('content')
<div class="content" style="max-width:1480px;">
  <div class="col-xs-12 not-padding push-20">
    <h2 class="text-center font-w300">
      COBRO DE <span class="font-w600">{{getMonthSpanish($month,false).' '.$year}}</span> A
      <span class="font-w600"><?php echo strtoupper($customer->name); ?></span>
    </h2>
  </div>
  <form class="form-toPayment" method="post" action="{{ url('/admin/cobros/cobrar') }}">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="id_cRate" value="<?php echo $cRate; ?>">
    <input type="hidden" id="importeCobrar" value="<?php echo $price; ?>">
    <div class="col-md-12 push-20">
      <h2 class="text-center font-w300">
        Cuota a cobrar <span class="font-w600 mbl-br"><?php echo $rate->typeRate->name . ': ' . $rate->name; ?></span>
        <br/>{{moneda($price)}}
      </h2>
    </div>
    <div class="row">
      <div class="col-md-4 col-xs-12">
        <label for="rate_id">Personal</label>
        <select class="form-control" id="user_id" name="user_id" style="width: 100%; cursor: pointer"
                placeholder="Personal asignado" >
          <option value="null">--</option>
          <?php
          $old = old('user_id');
          foreach ($allUsers as $k=>$v):
            $sel = ($user_id == $k) ? 'selected' : '';
            ?>
          <option value="<?= $k ?>" <?php echo $sel; ?>>
            <?php echo $v->n ?>
            </option>
            <?php
          endforeach;
          ?>
        </select>
      </div>
      <div class="col-md-2 col-xs-6 mb-1em">
        <label for="discount">DTO %:</label>
        <input type="number" id="discount" name="discount" class="form-control" value=""/>
      </div>
      <div class="col-md-3 col-xs-6">
        <label for="importeFinal">Total:</label>
        <input id="importeFinal" type="number" step="0.01" name="importe" class="form-control"
               value="<?php echo $importe; ?>"/>
      </div>
      <div class="col-md-3 col-xs-12 text-right mbl-tc">
          <a class="btn btn-lg btn-danger mt-1"
             href="{{ url('/admin/rates/unassigned')}}/<?php echo $cRate; ?>">
            <i class="fa fa-trash"></i> Desasignar
          </a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-xs-12">
        <div class="box-payment-card row">
          <h4>PAGAR AHORA</h4>
          <div class="row">
            <div class="col-xs-9 likeOption">
              <?php $old = old('type_payment', 'card'); ?>
              <input type="hidden" name="type_payment" id="type_payment" value="<?php echo $old; ?>">
              <button  data-v="card"  type="button" <?php if ($old == 'card') echo 'class="active"'; ?>>Tarjeta</button>
              <button  data-v="cash"  type="button" <?php if ($old == 'cash') echo 'class="active"'; ?>>Efectivo</button>
              <button  data-v="banco"  type="button" <?php if ($old == 'banco') echo 'class="active"'; ?>>Banco</button>
            </div>
            <div class="col-xs-3">
              <button class="btn btn-lg btn-success" type="submit" id="submitFormPayment">
                GUARDAR
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-md-6">
      </div>
    </div>
  </form>
</div>
@endsection
@section('scripts')

<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/moment.min.js')}}"></script>
<script type="text/javascript">
jQuery(function () {
  App.initHelpers(['datepicker']);
});

$(document).ready(function () {
  $('#discount').change(function (event) {
    var discount = $(this).val();
    var importe = $('#importeCobrar').val();
    var percent = discount / 100;

    $('#importeFinal').val(importe - (importe * percent));

  });

  $('#type_payment').change(function (e) {
    var value = $("#type_payment option:selected").val();
    if (value == "card") {
//            $('#stripeBox').show();
      $('#stripeBox').find('.disabled').show();
      $('.form-toPayment').attr('id', 'paymentForm');
    } else {
//            $('#stripeBox').hide();
      $('#stripeBox').find('.disabled').hide();
      $('.form-toPayment').removeAttr('id');
    }

  });

});
</script>

@endsection