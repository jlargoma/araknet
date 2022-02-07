<div class="table-responsive">
<table class="table t-center">
  <thead>
    <tr>
      <th class="t-left">Evento</th>
      <th>Ingreso</th>
      <th>Egreso</th>
      <th>Balance</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($logs as $l) {
      ?>
      <tr>
        <td class="t-left">
          <b><?php echo $l->text; ?></b><br/>
          <small><?php echo dateMin($l->created_at); ?></small>
        </td>
        <td><?php if ($l->incr) echo $l->incr; ?></td>
        <td><?php if ($l->decr) echo $l->decr; ?></td>
        <td class="total-log"><?php echo $l->total; ?></td>
      </tr>         
      <?php
    }
    ?>
  </tbody>
</table>
  <form class="form-horizontal lstBono" id="updBonoBalance" action="/admin/bonos/updCant" method="post">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="id" id="updBonoBalanceID" value="<?php echo $id; ?>">
    <h3>Modificar balance total:</h3>
    <div class="row  mx-1em">
      <div class="col-md-6 mx-1em">
        <label for="nombre">Motivo</label>
        <input class="form-control" type="text" name="motiv" required>
      </div>
      <div class="col-md-3 mx-1em">
        <label for="nombre">Cantidad</label>
        <input class="form-control" type="text" name="qty" required>
      </div>
      <div class="col-md-3">
        <button class="btn btn-success" style="margin-top: 3em;">Actualizar</button>
      </div>
     </div>
  </form>
</div>
<style>
  .total-log{
    font-size: 25px;
    font-weight: bold;
    padding-top: 3px;
  }
  </style>
  