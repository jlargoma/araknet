@extends('layouts.popup')
@section('content')
<div class="content">
  <div class="col-xs-12 not-padding push-20">
    <h2 class="text-center font-w300">COMPRA DE BONOS</h2>
  </div>
  <form class="form-toPayment" method="post" action="{{ url('/admin/bonos/comprar') }}" id="BonoPunch">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="id_user" value="<?php echo $user->id; ?>">
    <input type="hidden" name="type" value="<?php echo $type; ?>">
    <input type="hidden" name="id_back" value="<?php echo $id_back; ?>">
    <div class="row">
      <div class="col-md-6 col-xs-12 mt-1em">
        <div class="table-responsive">
        <table class="table t-center tableBono" style="margin-top: 2em;">
          <thead>
            <tr>
                <th class="static"></th>
                <th  class="t-left static-2">Bono</th>
                <td class="first-col"></td>
                <th>Servicio (familia)</th>
                <th>Cantidad</th>
                <th>Precio</th>
              </tr>
          </thead>   
            <tbody>
            @if($oBonos)
              @foreach($oBonos as $b)
              <tr > 
                <td class="static"><input type="radio" name="id_bono" value="{{$b->id}}" class="form-control"/></td>
                <td class="t-left static-2"><label>{{$b->name}}</label><?php 
                if ($b->tarifa == 'fidelity'){
                  if ($uPlan == 'basic' ) echo ' <i class="fa fa-heart text-danger"></i>';
                  if ($uPlan == 'fidelity' ) echo ' <i class="fa fa-heart text-success"></i>';
                }
                ?></td>
                <td class="first-col"></td>
                <td>
                  <?php 
                  if ($b->rate_subf && isset($rate_subf[$b->rate_subf]))
                    echo $rate_subf[$b->rate_subf];
                  if ($b->rate_type && isset($typesRate[$b->rate_type]))
                    echo '<b>'.$typesRate[$b->rate_type].'</b>';
                  ?>
                </td>
                <td>{{$b->qty}}</td>
                <?php 
                  $price = $b->price;
                  if ($uPlan == 'basic' && $b->tarifa == 'fidelity') $price = priceNoFidelity($price);
                  ?>
                <td><input type="text" name="price_{{$b->id}}" value="{{$price}}" class="form-control only-numbers"></td>
              </tr>
              @endforeach
            @endif
          </tbody>
      </table>
          </div>
      </div>
      <div class="col-sm-6 col-xs-12 mt-1em">
        <div class="box-payment-card row">
          <h4>PAGAR AHORA</h4>
          <div class="col-xs-12">
            <div class="col-xs-9">
              <select class="likeBtn" name="type_payment" id="type_payment" multiple>
                <option value="card" selected="">Tarjeta</option>
                <option value="cash">Efectivo</option>
                <option value="banco">Banco</option>
              </select>
            </div>
            <div class="col-xs-3">
              <button class="btn btn-lg btn-success" type="submit" id="submitFormPayment">
                Cobrar
              </button>
            </div>
            <div class="col-xs-12">
              @include('admin.blocks.stripeBox')
            </div>
            <div class="col-xs-12">
              @include('admin.blocks.stripe-actions')
              <small><strong>Importante:</strong> Se cobrará el valor original del Bono seleccionado</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-6 col-md-6">
        
      </div>
    </div>
  </form>
</div>
@endsection
@section('scripts')

<script type="text/javascript">
$(document).ready(function () {
  
  $('input[type=radio][name=id_bono]').on('change', function() {
    var val = $(this).val();
    $('.checkBono').removeClass('active');
    $(this).closest('.checkBono').addClass('active');
  });



  $('#type_payment').change(function (e) {
    var value = $("#type_payment option:selected").val();
    if (value == "card") {
      $('#stripeBox').find('.disabled').show();
      $('.form-toPayment').attr('id', 'paymentForm');
    } else {
      $('#stripeBox').find('.disabled').hide();
      $('.form-toPayment').removeAttr('id');
    }

  });
<?php if ($card): ?>
    $('#card-element').hide();
    $('#changeCreditCard').on('click', function () {
      $('#cardExists').hide();
      $('#cardLoaded').val(0);
      $('#card-element').show();
      $(".new_cc").prop('required', true);
    });
<?php endif; ?>


  $('.btnStripe').on('click', function () {
    var type = $(this).data('t');
    var posting = $.post('/admin/send/cobro-bono', {
      _token: '{{csrf_token()}}',
      u_ID: <?php echo $user->id; ?>,
      u_bono:  $('#BonoPunch').find('input[name=id_bono]:checked').val(),
      u_email: $('#u_email').val(),
      u_phone: $('#u_phone').val(),
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
<style>
  .tableBono .static{
    width: 20px;
    padding: 11px 0 !important;
}

  @media only screen and (max-width: 426px){
  .tableBono .static{
    width: 25px;
    overflow-x: scroll;
    margin-top: 1px;
    position: absolute;
    border-right: 1px solid #efefef;
    background-color: #f5f5f5;
    z-index: 9;
    padding: 5px 2px !important;
    height: 50px;
  }
  .tableBono .static-2{
    background-color: #f5f5f5;
    left: 45px;
    width: 110px;
        padding-left: 0;
    text-align: left;
  }
  .tableBono tr .first-col {
    padding-left: 130px !important;
  }
  }
</style>
@include('admin.blocks.cardScripts')
@endsection