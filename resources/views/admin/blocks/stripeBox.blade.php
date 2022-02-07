<div class="col-md-12" id="stripeBox">
    <div class="disabled"></div>
    <div class="row alert alert-info fade in alert-dismissable">
        <div class="row">
            <div class="form-row col-xs-12 push-20">
                @if($card)
                <div id="cardExists">
                    <input type="hidden" name="cardLoaded" id="cardLoaded" value="1">
                    <div class="form-group">
                        <label for="cc_number">Tarjeta</label>
                        <input type="text" class="form-control" readonly="" value="{{$card['brand']}}">
                    </div>
                    <div class="form-group">
                        <label for="cc_number">Tarjeta terminada en</label>
                        <input type="text" class="form-control" readonly="" value="{{$card['last4']}}">
                    </div>
                    <div class="form-group">
                        <label for="cc_number">Expira</label>
                        <input type="text" class="form-control" readonly="" value="{{$card['exp_month'].' / '.$card['exp_year']}}">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger" id="changeCreditCard">Eliminar</button>
                    </div>
                </div>
                @endif
                <div id="card-element">
                    <div class="form-group">
                        <label for="cc_number">Número de tarjeta</label>
                        <input type="text" class="form-control new_cc formated" name="cc_number" id="cc_number" data-t="card" placeholder="---- ---- ---- ----" >
                        <small id="cc_number" class="form-text text-muted">Ingrese los números del frente de su tarjeta de credito</small>
                    </div>
                    <div class="form-group">
                        <label for="expide_mm">Fecha de vencimiento (mm/yy)</label>
                        <input type="text" class="form-control new_cc formated" data-t="date" placeholder="MM/YY" name="card_expide">
                        <small id="expide_mm" class="form-text text-muted">Mes y año de vencimiento que figura en su tarjeta</small>
                        <input type="hidden" name="cc_expide_mm" id="cc_expide_mm" value="">
                        <input type="hidden" name="cc_expide_yy" id="cc_expide_yy" value="">
                    </div>
                    <div class="form-group">
                        <label for="cc_cvc">CVC / CVV</label>
                        <input class="form-control new_cc formated"  data-t="cvc" name="cc_cvc" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="number" min="1" max="9999"  maxlength="4" placeholder="---" >
                        <small class="form-text text-muted">Código de seguridad del reverso de la tarjeta</small>
                    </div>
                </div>


                <!-- Used to display form errors -->
                <div id="card-errors" role="alert"></div>
            </div>
        </div>
    </div>
</div>