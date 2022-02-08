<?php function printInvoiceContent($oInvoice,$items){ ?>
<div class="panel panel-default main">
  <div class="panel-heading">
    <div class="clearfix">
      <div class="col-logo">
        <img src="{{url('/assets/logo/logo.svg')}}" class="img-responsive">
        <div class="inv_numb"><?php echo $oInvoice->num ?></div>
        <div><b><?php echo convertDateToShow($oInvoice->date,true) ?></b></div>
      </div>
      <div class="col-xs-5 info-empresa">

        <b><?php echo $oInvoice->name_business ?></b><br>
        <b><?php echo $oInvoice->nif_business ?></b><br>
        <b><?php echo $oInvoice->address_business ?></b><br>
        <b><?php echo $oInvoice->zip_code_business ?></b><br>
      </div>
    </div>
  </div>
  <!--row-->
  <div class="panel-body">
    <div class="row">
      <div class="col-lg-9 col-md-9 col-sm-9">
        <h3><?php echo ucfirst($oInvoice->name) ?></h3>
          <?php
          if (trim($oInvoice->nif) != '') echo '<b>DNI</b> '. $oInvoice->nif .'<br/>';
          if (trim($oInvoice->email) != '') echo '<b>Email</b> '. $oInvoice->email .'<br/>';
          if (trim($oInvoice->phone) != '') echo '<b>Teléfono</b> '. $oInvoice->phone .'<br/>';
          if (trim($oInvoice->address) != '') echo '<b>Dirección</b> '. $oInvoice->address .'<br/>';
          if (trim($oInvoice->zip_code) != '') echo '<b>C. Postal</b> '. $oInvoice->zip_code .'<br/>';
          ?>
      </div>
    </div>
    <!--row-->
    <table class="table">
      <thead>
        <tr>
          <th>Descripción</th>
          <th class="text-center">IVA</th>
          <th class="text-right">Importe</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $subtotal = 0;
        if ($items):
          foreach ($items as $item):
            $detail = convertBold($item['detail']);
            $iva = floatval($item['iva']);
            $price = floatval($item['price']);
            if ($iva > 0) {
              $sinIvaPVP = removeIVA($price, $iva);
              $subtotal += $sinIvaPVP;
              echo '<tr><td>' . $detail . '</td><td class="text-center">' . $iva . '%</td><td class="text-right">' . moneda($sinIvaPVP, true, 2) . '</td></tr>';
            } else {
              $subtotal += $price;
              echo '<tr><td>' . $detail . '</td><td class="text-center">--</td><td class="text-right">' . moneda($price, true, 2) . '</td></tr>';
            }
          endforeach;
        endif;
        ?>
        <tr><td colspan="3"><br/><br/></td></tr>
      </tbody>
      <tfoot>
        <tr class="subtotal">
          <th class="text-right" colspan="2">Total Neto</th>
          <th class="text-right">{{moneda($subtotal,true,2)}}</th>
        </tr>
        @if($oInvoice->total_price>$subtotal)
        <tr class="subtotal">
          <th class="text-right" colspan="2">IVA</th>
          <th class="text-right">{{moneda($oInvoice->total_price-$subtotal,true,2)}}</th>
        </tr>
        @endif
        <tr>
          <th class="text-right" colspan="2">Total</th>
          <th class="text-right">{{moneda($oInvoice->total_price,true,2)}}</th>
        </tr>
      </tfoot>
    </table>
    <br/>
  </div>
  <div class="panel-footer">
    <p class="text-center">
      <a href="www.Araknet.tech" title="www.Araknet.tech">www.Araknet.tech</a>
      <br/>
      Salud - Actividad Física - Rendimiento
    </p>
  </div>
</div>
<?php } ?>