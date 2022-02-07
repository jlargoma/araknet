@extends('layouts.popup')
@section('content')
<style type="text/css">
  .StripeElement {
    background-color: white;
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid transparent;
    box-shadow: 0 1px 3px 0 #e6ebf1;
    -webkit-transition: box-shadow 150ms ease;
    transition: box-shadow 150ms ease;
  }

  .StripeElement--focus {
    box-shadow: 0 1px 3px 0 #cfd7df;
  }

  .StripeElement--invalid {
    border-color: #fa755a;
  }

  .StripeElement--webkit-autofill {
    background-color: #fefde5 !important;
  }

  .stripe-price {
    background-color: white !important;
    padding: 8px 12px !important;
    border-radius: 4px !important;
    border: 1px solid transparent !important;
    box-shadow: 0 1px 3px 0 #e6ebf1 !important;
    -webkit-transition: box-shadow 150ms ease !important;
    transition: box-shadow 150ms ease !important;
  }
  .stripeEmail{height: 0px;    overflow: hidden;}
</style>
<div class="content" style="max-width:975px;">
  <h2 class="text-center push-20"> ASIGNAR Y GENERAR COBRO PARA <?php echo strtoupper($user->name) ?></h2>
  <form class="form-toPayment" method="post" action="{{ url('/admin/cobros/cobrar-usuario') }}">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="id_user" value="<?php echo $user->id; ?>">
    <div class="row">
      <div class="col-xs-6 col-md-4 push-20">
        <label for="id_rate">Tarifa</label>
        <select class="form-control" id="id_rate" name="id_rate" style="width: 100%; cursor: pointer"
                placeholder="Seleccione tarifas.." required="">
          <option></option>
          <?php
          $old = old('id_rate');
            foreach ($rateFamily as $k=>$v):
              echo '<optgroup label="'.$v['n'].'">';
              foreach ($v['l'] as $rate):
                $sel = ($rate->id == $old) ? 'selected' : '';
                         
                $price = $rate->price;
                $tarifa = '';
                if ($rate->tarifa == 'fidelity'){
                  if ($uPlan == 'basic'){
                    $price = priceNoFidelity($price);
                    $tarifa = 'nofidelity';
                  }
                  if ($uPlan == 'fidelity') $tarifa = 'fidelity';
                }
              
                ?>
                
                <option value="<?php echo $rate->id ?>" 
                    data-price="<?php echo $price ?>"
                    orig="<?php echo $rate->price ?>"
                    data-tarifa="{{$tarifa}}"
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
        <label for="id_rate">Personal</label>
        <select class="form-control" id="id_coach" name="id_coach" style="width: 100%; cursor: pointer"
                placeholder="Personal asignado" >
          <option value="null">--</option>
          <?php
          $old = old('id_coach');
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
                  Cobrar
                </button>
              </div>
              <div class="col-xs-12">
              @include('admin.blocks.stripeBox')
              </div>
            </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 push-20">
            @include('admin.blocks.stripe-actions')
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
  $('#id_rate').change(function (event) {
    var that = $("#id_rate option:selected");
    var price = that.data('price');
    $('#importeFinal').val(price);
    origPrice = price;
    
    
    var tarifa = that.data('tarifa');
    $('#showTartifa').html('');
    if (tarifa == 'fidelity') $('#showTartifa').html('<i class="fa fa-heart text-success"></i> Plan Fidelity');
    if (tarifa == 'nofidelity') $('#showTartifa').html('<i class="fa fa-heart text-danger"></i> Plan Básico');
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

  $('.btnStripe').on('click', function () {
    var type = $(this).data('t');
    var posting = $.post('/admin/cobros/cobrar-usuario', {
    _token: '{{csrf_token()}}',
    id_coach: $('#id_coach').val(),
    id_rate: $('#id_rate').val(),
    id_user: {{$user->id}},
    u_email: $('#u_email').val(),
    u_phone: $('#u_phone').val(),
    discount: $('#discount').val(),
    importe: $('#importeFinal').val(),
    type: type
    });
    posting.done(function (data) {
    if (data[0] == 'OK') {
      if (type == 'mail') {
        window.show_notif('success', data[1]);
      }
      if (type == 'wsp') {
        if (window.detectMob()) {
          var url = 'whatsapp://send?text=' + encodeURI(data[1]);
        } else {
          var url = 'https://web.whatsapp.com/send?phone=' + $('#u_phone').val() + '&text=' + encodeURI(data[1]);
        }
        const newWindow = window.open(url, '_blank', 'noopener,noreferrer')
        if (newWindow)
          newWindow.opener = null
      }
      if (type == 'copy') {
        $('#cpy_link').val(data[1]);
        document.getElementById("cpy_link").style.display = "block";
        document.getElementById("cpy_link").select();
        document.execCommand("copy");
        document.getElementById("cpy_link").style.display = "none";
        window.show_notif('success', 'Mensaje copiado');
      }

    } else {
      window.show_notif('error', data[1]);
    }
    });
  });
});
</script>
@include('admin.blocks.cardScripts')
@endsection