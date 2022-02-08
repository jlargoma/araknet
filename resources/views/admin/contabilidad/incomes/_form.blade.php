<link rel="stylesheet"
      href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet"
      href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
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
<div class="col-xs-12 bg-white">
    <div class="row">
        <div class="col-xs-12 push-20">
            <h2 class="text-center">Nuevo Ingreso </h2>
        </div>
        <div class="col-xs-12">
            <form action="{{ url('/admin/ingresos/create') }}" class="form-toPayment" method="post">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class=" col-xs-12 form-group push-20">

                    <div class="col-xs-12 col-md-6 push-20">
                        <label for="type">Tipo de ingreso</label>
                        <select class="js-select2 form-control" id="type" name="type" style="width: 100%;"
                                data-placeholder="Seleccione un tipo" required>
                            <option></option>
                            <option value="Cursos de Formación">
                                Cursos de Formación
                            </option>
                            <option value="Eventos especiales Empresas">
                                Eventos especiales Empresas
                            </option>
                            <option value="Venta Material Deportivo">
                                Venta Material Deportivo
                            </option>
                            <option value="Vending">
                                Vending
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-6 push-20">
                        <label for="date">fecha</label>
                        <input type="text" class="js-datepicker form-control" name="date" data-date-format="dd-mm-yyyy"
                               placeholder="Fecha" value="<?php echo date('d-m-Y') ?>"/>

                    </div>
                </div>
                <div class=" col-xs-12 form-group push-20">

                    <div class="col-xs-12 col-md-6 push-20">
                        <label for="import">Importe</label>
                        <input type="text" name="import" id="import" class="form-control"/>
                    </div>
                    <div class="col-xs-6">
                        <label for="type_payment">Forma de pago</label>
                        <select class="form-control" name="type_payment" id="type_payment">
                            <option value="cash">Efectivo</option>
                            <option value="banco">Tarjeta</option>
                        </select>
                    </div>
                </div>
                <div class=" col-xs-12 form-group ">
                    <div class="col-xs-12 col-md-12">
                        <label for="concept">Concepto</label>
                        <textarea class="form-control" name="concept"></textarea>
                    </div>
                </div>
                <div class="col-md-12" id="content-payment" style="display: none;">
                    <div class="row alert alert-info fade in alert-dismissable"
                         style="margin-top: 30px; background-color: #daeffd!important;">

                        <div class="row">
                            <div class="col-md-12 col-xs-12 text-left push-20">
                                <label for="email">Email</label>
                                <input type="email" class="form-control stripe-price" name="email"
                                       placeholder="example@example.com" value="cobrostpv@Araknet.tech"/>
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

                <div class=" col-xs-12 form-group push-20">
                    <div class="col-xs-12 text-center">
                        <button class="btn btn-lg btn-success" type="submit" id="submitFormPayment">
                            Añadir
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $("#import").keydown(function (e) {
      // Allow: backspace, delete, tab, escape, enter and .
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          // Allow: Ctrl+A, Command+A
          (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
          // Allow: home, end, left, right, down, up
          (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        return;
      }
      // Ensure that it is a number and stop the keypress
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
      }
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