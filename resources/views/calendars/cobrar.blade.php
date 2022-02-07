<?php 
$oBonoLst = $oUser->bonosServ($id_serv);
$tBonos = $oBonoLst[0];
$oBonoLst = $oBonoLst[1];
?>
<form action="{{ url('/admin/citas/chargeAdvanced') }}" method="post" id="chargeDate">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="idDate" id="idDate" value="<?php echo $id ?>">
  <input type="hidden" name="id_rate" value="<?php echo $id_serv ?>">
  <div class="col-xs-12 col-md-6 push-20">
    <div class="box-payment-card">
      <h4>PAGAR AHORA</h4>
      <div class="row">
        <div class="col-md-9">
          <select class="likeBtn" name="type_payment" id="type_payment" multiple>
            <option value="card" selected="">Tarjeta</option>
            <option value="cash">Efectivo</option>
            <option value="banco">Banco</option>
            @if($tBonos>0)
            <option value="bono" >Bonos ({{$tBonos}})</option>
            @else
            <option value="bono" disabled="">Bonos</option>
            @endif
          </select>
        </div>
        <div class="col-md-3">
          <button class="btn btn-lg btn-success sendForm" type="button" data-id="chargeDate">
            Cobrar
          </button>
          <button class="btn btn-lg btn-success sharedBono" type="button" data-id="chargeDate">
            Bono Compartido
          </button>
        </div>
        <div class="col-xs-12">
        @include('admin.blocks.stripeBox')
        <div id="bonosBox" style="display: none;">
          <?php 
          if (count($oBonoLst)>0){
            foreach ($oBonoLst as $b){
              ?>
                <div class="checkBono" >
                <input type="radio" name="id_bono" value="{{$b[0]}}" class="form-control"/>
                <label>{{$b[1]}} ({{$b[2]}})</label>
                </div>
              <?php
            }
          } else {
            ?>
            <p class="alert alert-warning">No tiene bonos asignados</p>
            <?php
          }
          ?>
        </div>
      </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 push-20">
    @include('admin.blocks.stripe-buttons')
  </div>
</form>
    
@include('calendars.shareBonos')

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
    $('.sharedBono').on('click',function(e){
      e.preventDefault();
      $('#content-sharedBono').empty().load('/admin/bonos/sharedBono/{{$oUser->id}}/{{$id_serv}}');
      $('#modal-shareBonos').modal();
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
#modal-shareBonos span#select2-id_userBono-container {
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
        