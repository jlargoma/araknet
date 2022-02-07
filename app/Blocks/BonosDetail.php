<?php
if ($oBonoCharges && count($oBonoCharges)>0):
  ?>
  <h2>Registors de Bonos: <?php echo moneda($oBonoCharges->sum('import')) ?></h2>
  <div class="table-responsive">
    <table class="table">
      <tr>
        <th>Fecha</th>
        <th>Bono</th>
        <th>Monto</th>
        <th>Met. Pago</th>
      </tr>
      <?php
      foreach ($oBonoCharges as $c) {
        ?>
        <tr>
          <td><?php echo dateMin($c->date_payment); ?></td>
          <td><?php echo $aBonos[$c->bono_id]; ?></td>
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
  <h2>Registors de Bonos</h2>
  <p class="alert alert-warning">Sin Registros</p><?php
endif;
