<div class="box-payment-btn">
  <h4>Generar Link Pago Único Stripe</h4>
  <div class="row">
    <div class="col-imput">
      <label>Email:</label>
      <input id="u_email" type="text" class="form-control" value="<?php echo $user->email ?>"/>
    </div>
    <div class="stripeAction">
      <button type="button" class="btn btn-default btnStripe" data-t="mail">
        <i class="fa fa-envelope"></i> Enviar Mail
      </button>
    </div>
    <div class="col-xs-12"><br/></div>
    <div class="col-imput">
      <label >Tel. Mobil:</label>
      <input id="u_phone" type="text" class="form-control" value="<?php echo $user->telefono ?>"/>
    </div>
    <div class="stripeAction">
      <button type="button" class="btn btn-default btnStripe" data-t="wsp">
        <i class="fa fa-whatsapp"></i> Enviar WSP 
      </button>
    </div>
    <div class="col-xs-12"><br/>
      <button type="button" class="btn btn-default btnStripe" data-t="copy">
        <i class="fa fa-copy"></i> Copiar link Stripe
      </button>
      <textarea id="cpy_link" style="height: 0px; width: 0px; border: none; display: none;"></textarea>
    </div>
  </div>
</div>