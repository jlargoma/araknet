@extends('layouts.admin-master')

@section('title') Control Contabilidad @endsection
@section('headerTitle') Control Contabilidad @endsection
@section('content')

<div class="content content-full bg-white">
   <div class="row">
  <form action="" method="GET">
    <div class="col-xs-1">
    <label>Año</label>
    <input class="form-control" name="year" value="{{$year}}">
    </div>
    <div class="col-xs-1">
    <label>Mes</label>
    <input class="form-control" name="mes" value="{{$mes}}">
    </div>
    <div class="col-xs-2">
    <label>Estado del Cliente</label>
    <select class="form-control" name="active">
      <option value="" <?= ($active === '') ? 'selected' : '' ?>>TODOS</option>
      <option value="1" <?= ($active === 1) ? 'selected' : '' ?>>Activos</option>
      <option value="0" <?= ($active === 0) ? 'selected' : '' ?>>No Activos</option>
    </select>
    </div>
    <div class="col-xs-2">
      <label for="type_payment">Forma de pago</label>
      <select class="form-control" name="type_payment" id="type_payment">
        <option value="" <?= ($type_payment === '') ? 'selected' : '' ?>>Todos</option>
        <option value="cash" <?= ($type_payment === 'cash') ? 'selected' : '' ?>>Efectivo</option>
        <option value="card" <?= ($type_payment === 'card') ? 'selected' : '' ?>>Banco</option>
        <option value="banco" <?= ($type_payment === 'banco') ? 'selected' : '' ?>>Tarjeta</option>
        <option value="bono" <?= ($type_payment === 'bono') ? 'selected' : '' ?>>Bono</option>
      </select>
    </div>
    <div class="col-xs-2">
      <label for="type_payment">Valor 0</label>
      <select class="form-control" name="showEmpty" id="showEmpty">
        <option value="" <?= ($showEmpty === '') ? 'selected' : '' ?>>Todos</option>
        <option value="SI" <?= ($showEmpty === 'SI') ? 'selected' : '' ?>>SI</option>
        <option value="NO" <?= ($showEmpty === 'NO') ? 'selected' : '' ?>>NO</option>
      </select>
    </div>
<div class="col-xs-1">
  <label for="type_payment">Filtrar</label>
    <button class="btn btn-success">Recargar</button>
</div>

  </form>
   </div>
  <div class="row">
    <div class="col-md-12">
      <?php
      $tPay = $tDebe = 0;
      ?>
      <h2>Servicios del Mes</h2>
      <table class="table tableControl">
        <thead>
          <tr>
            <th  class="tleft">Cliente</th>
            <th>Activo</th>
            <th>Tarifa</th>
            <th>Tipo</th>
            <th>Familia</th>
            <th>Pagado</th>
            <th>Debe</th>
            <th>Tipo Pago</th>
          </tr>
        </thead>
        <tbody>
          @foreach($uRates as $ur)
          <?php 
          $aux = ($ur->charges) ? $ur->charges->import : $ur->price ;
          if ($showEmpty == 'SI' && $aux > 0) continue;
          if ($showEmpty == 'NO' && $aux <= 0) continue;
          
          ?>
          <tr>
            <td class="tleft">{{$ur->user->name}}</td>
            <td>
              <?php echo ($ur->user->status == 1) ? 'SI' : 'NO'; ?>
            </td>
            <?php
            if (isset($oRates[$ur->id_rate])) {
              echo '<td>' . $oRates[$ur->id_rate] . '</td>';
              echo '<td>' . $rTypes[$ur->id_rate] . '</td>';
              echo '<td>' . $rfamily[$ur->id_rate] . '</td>';
            } else {
              echo '<td>-</td>';
              echo '<td>-</td>';
              echo '<td>-</td>';
            }

            if ($ur->charges) {
              echo '<td>' . $ur->charges->import . '</td>';
              echo '<td></td>';
              echo '<td>' . $ur->charges->type_payment . '</td>';
              $tPay += $ur->charges->import;
            } else {
              echo '<td></td>';
              echo '<td>' . $ur->price . '</td>';
              echo '<td></td>';
              $tDebe += $ur->price;
            }
            ?>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
        <th colspan="5" class="tleft">TOTAL</th>
        <th>{{$tPay}}</th>
        <th>{{$tDebe}}</th>
        <th>{{$tPay+$tDebe}}</th>
        </tfoot>
      </table>
    </div>
    <div class="col-md-12">
      <?php
      $tPayBono = 0;
      ?>
      <h2>Bonos comprados en el mes</h2>
      <table class="table tableControl">
        <thead>
          <tr>
            <th  class="tleft">Cliente</th>
            <th>Activo</th>
            <th>Bono</th>
            <th>Pagado</th>
            <th>Tipo Pago</th>
          </tr>
        </thead>
        <tbody>
          @foreach($bonoCharges as $ur)
          <tr>
            <td class="tleft">{{$ur->user->name}}</td>
            <td>
              <?php echo ($ur->user->status == 1) ? 'SI' : 'NO'; ?>
            </td>
            <?php
            if (isset($lstBonos[$ur->bono_id])) {
              echo '<td>' . $lstBonos[$ur->bono_id] . '</td>';
            } else {
              echo '<td>-</td>';
            }
            echo '<td>' . $ur->import . '</td>';
            echo '<td>' . $ur->type_payment . '</td>';
            $tPayBono += $ur->import;
            ?>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
        <th colspan="3" class="tleft">TOTAL</th>
        <th>{{$tPayBono}}</th>
        <td></td>
        </tfoot>
      </table>
    </div>

    <div class="col-md-12">
      <table class="table tableControl tableresult">
        <thead>
          <tr>
            <th>Pagado</th>
            <th>Por Pagar</th>
            <th>Bono</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
        <th>{{$tPay}}</th>
        <th>{{$tDebe}}</th>
        <th>{{$tPayBono}}</th>
        <th>{{moneda($tPay+$tDebe+$tPayBono)}}</th>
        </tbody>
      </table>
    </div>

  </div>
</div>

@endsection
@section('scripts')
<style>
  .tableControl th,
  .tableControl td{
    text-align: center;
  }
  .tableControl .tleft{
    text-align: left !important;
  }
  
  .tableresult,thead{
    background-color: #2c343f; color: #FFF;
  }
  th{
    font-size: 21px !important;
    font-weight: bold !important;
    }
    h2 {
    margin: 2em auto 1em;
    background-color: #46c37b;
    padding: 10px;
    color: #FFF;
}
</style>
<script type="text/javascript">
  $(document).ready(function () {
  });
</script>
@endsection