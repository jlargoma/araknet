<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
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
</style>
<div class="col-xs-12">
    <div class="col-xs-12 not-padding push-20">
        <h2 class="text-center font-w300">
			<?php setlocale(LC_TIME, "ES"); ?>
			<?php setlocale(LC_TIME, "es_ES"); ?>
            COBRO DE <span class="font-w600"><?php echo strtoupper($date->copy()->formatLocalized('%B'))?></span> A
            <span class="font-w600"><?php echo strtoupper($customer->name); ?></span>
        </h2>
    </div>

    <form class="form-toPayment" method="post" action="{{ url('/admin/cobros/cobrar') }}">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" name="customer_id" value="<?php echo $customer->id; ?>">
        <input type="hidden" id="importeCobrar" value="<?php echo $rate->price; ?>">
        <h1>En construcción</h1>
          
        <div class="col-xs-12">
            <div class="col-md-12 push-20">
                <h2 class="text-center font-w300">
                    Cuota a cobrar <span class="font-w600"><?php echo $rate->name; ?></span>
                </h2>
                <div class="row push-20">
                    <p class="text-danger text-center font-w600 font-s32"><?php echo $rate->price; ?>€</p>
                </div>
                <div class="col-xs-12 col-md-6 push-20">
                    <label for="date_payment">Fecha de cobro</label>
                    <input class="js-datepicker form-control" type="text" id="date_payment" name="fecha_pago"
                           data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd"
                           value="<?php echo \Carbon\Carbon::now()->format('Y-m-d'); ?>"
                           style="cursor: pointer;"
                           required="">
                </div>
                <div class="col-xs-6 push-20">
                    <label for="date_payment">DTO:</label>
                    <input type="number" id="discount" name="discount" class="form-control"/>

                    <input type="hidden" id="id_tax" name="id_tax" class="form-control"
                           value="<?php echo $rate->id; ?>"/>
                </div>
            </div>

            <div style="clear: both;"></div>
            <div class="col-md-12 push-20">
                <div class="col-xs-6">
                    <label for="type_payment">Forma de pago</label>
                    <select class="form-control" name="type_payment" id="type_payment">
                        <option value="cash">Efectivo</option>
                        <option value="card">Banco</option>
                        <option value="banco">Tarjeta</option>
                    </select>
                </div>
                <div class="col-xs-6">
                    <div class="col-md-5">
                        <h3>Total</h3>
                    </div>
                    <div class="col-md-7">
                      <input id="importeFinal" type="number" step="0.01" name="importe" class="form-control"
                               value="<?php echo $rate->price; ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="content-payment" style="display: none;">
                <div class="row alert alert-info fade in alert-dismissable"
                     style="margin-top: 30px; background-color: #daeffd!important;">

                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-left push-20">
                            <label for="email">Email</label>
                            <input type="email" class="form-control stripe-price" name="email"
                                   placeholder="example@example.com" value="<?php echo $customer->email; ?>"/>
                        </div>
                        <div class="form-row col-xs-12 push-20">
                            <label for="card-element">
                                Datos de la tarjeta
                            </label>
                            <div id="card-element">
                                <!-- a Stripe Element will be inserted here. -->
                            </div>

                            <!-- Used to display form errors -->
                            <div id="card-errors" role="alert"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center push-20">
                <div class="col-md-6 col-xs-12">
                    <button class="btn btn-lg btn-success" type="submit" id="submitFormPayment">
                        Cobrar
                    </button>
                </div>
                <div class="col-md-6 col-xs-12">
                    <a class="btn btn-lg btn-danger"
                       href="{{ url('/admin/rates/unassigned')}}/<?php echo $customer->id; ?>/<?php echo $rate->id; ?>/<?php echo $date->format('Y-m-d'); ?>">
                        Desasignar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
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
      var importe = $('#importeCobrar').val();
      var percent = discount / 100;

      $('#importeFinal').val(importe - (importe * percent));

    });

    $('#type_payment').change(function (e) {
      var value = $("#type_payment option:selected").text();
      console.log(value);

      if (value == "Tarjeta") {
        $('#content-payment').show();
        $('.form-toPayment').attr('id', 'paymentForm');
      } else {
        $('#content-payment').hide();
        $('.form-toPayment').removeAttr('id');
      }

    });
  });
</script>
@include('admin.stripeScript')