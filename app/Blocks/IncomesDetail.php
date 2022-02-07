<?php
if ($oCharges && count($oCharges)>0):
  ?>
  <h2>Registors de <?php echo $oType->name.': '.moneda($oCharges->sum('import')) ?></h2>
  <div class="table-responsive">
    <table class="table">
      <tr>
        <th>Fecha</th>
        <th>Servicio</th>
        <th>Monto</th>
        <th>Met. Pago</th>
      </tr>
      <?php
      foreach ($oCharges as $c) {
        ?>
        <tr>
          <td><?php echo dateMin($c->date_payment); ?></td>
          <td><?php echo $servic[$c->id_rate]; ?></td>
          <td><?php echo moneda($c->import); ?></td>
          <td><?php echo isset($pMeth[$c->type_payment]) ? $pMeth[$c->type_payment] : 'Otro'; ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>
  <?php
else:
  ?>
  <h2>Registors de <?php echo $oType->name; ?></h2>
  <p class="alert alert-warning">Sin Registros</p>
  <?php
endif;
