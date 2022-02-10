<form action="{{ url('/admin/citas/chargeAdvanced') }}" method="post" id="chargeDate">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="idDate" id="idDate" value="<?php echo $id ?>">
  <input type="hidden" name="rate_id" value="<?php echo $id_serv ?>">
  <div class="col-xs-12 col-md-6 push-20">
    <div class="box-payment-card">
      <h4>PAGAR AHORA</h4>
      <div class="row">
        <div class="col-md-9">
          <select class="likeBtn" name="type_payment" id="type_payment" multiple>
            <option value="cash">Efectivo</option>
            <option value="banco">Banco</option>
          </select>
        </div>
        <div class="col-md-3">
          <button class="btn btn-lg btn-success sendForm" type="button" data-id="chargeDate">
            Cobrar
          </button>
        </div>
        <div class="col-xs-12">
        
        
      </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 push-20">
    @include('admin.blocks.stripe-buttons')
  </div>
</form>
<script type="text/javascript">
$(document).ready(function () {
    $('#type_payment').change(function (e) {
        var value = $("#type_payment option:selected").val();
        if (value == "bono") {
            $('#bonosBox').show();
            $('#stripeBox').hide();
        } else {
            $('#bonosBox').hide();
            $('#stripeBox').show();
        }

    });
});
</script>
<style>
.checkBono {
    margin: 3em 11px;
}
button.btn.btn-lg.btn-success.sharedBono {
    font-size: 12px;
    padding: 6px;
    margin-top: 3px;
}
#modal-shareBonos span#select2-customer_idBono-container {
    padding: 8px;
}
#modal-shareBonos div#lstBonos {
    padding: 0;
}
#modal-shareBonos .checkBono {
    margin: 11px;
}
#modal-shareBonos .checkBono label {
    font-size: 14px;
}
</style>
        