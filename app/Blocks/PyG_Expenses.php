<h2>Registros de <?php echo strtoupper($gTypesNames[$type]).': '.moneda($items->sum('import'))?></h2>

<div class="table-responsive">
<table class="table">
  <tr>
    <th>Fecha</th>
    <th>Tipo</th>
    <th>Concepto</th>
    <th>Monto</th>
    <th>Met. Pago</th>
  </tr>
    <?php
    foreach ($items as $i){
      ?>
<tr>
  <td><?php echo dateMin($i->date);?></td>
  <td><?php echo $aTypeLst[$i->type];?></td>
  <td><?php echo $i->concept;?></td>
  <td><?php echo moneda($i->import);?></td>
  <td><?php echo $payType[$i->typePayment];?></td>
</tr>
      <?php
    }
     ?>
</table>
</div>