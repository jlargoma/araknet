<div class="row">
  <form action="{{ url('/admin/clientes/update') }}" method="post">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="id" value="{{ $user->id }}">

    <div class="col-md-12 col-lg-6 lineRight">
      <h3 class="text-left">Datos del Usuario
        <button class="btn btn-default add_rate" data-idUser="<?php echo $user->id; ?>">
          <i class="fa fa-usd" aria-hidden="true"></i> Asignar Servicio
        </button>
        @if(isset($encNutr))
        <a href="/admin/ver-encuesta/{{$btnEncuesta}}" class="btn btn-default" target="_black">
          <i class="fa fa-eye" aria-hidden="true"></i> Ver encuesta Nutrición
        </a>
        @endif
      </h3>
      <div class="row formLine">
        <div class="col-md-7">
          <input class="field" type="text" id="name" name="name" required value="<?php echo $user->name ?>">
          <label>Nombre</label>
        </div>
        <div class="col-md-5">
          <input class="field" type="text" id="dni" name="dni" value="<?php echo $user->dni ?>">
          <label>DNI</label>
        </div>
        <div class="col-md-7">
          <input type="text" id="email" class="field" name="email" required value="<?php echo $user->email ?>">
          <label>E-mail</label>
        </div>
        <div class="col-md-5">
          <input class="field" type="number" id="phone" name="phone" required maxlength="9" value="<?php echo $user->phone ?>">
          <label for="phone">Teléfono</label>
        </div>
        <div class="col-md-12">
          <input type="text" id="address" class="field" name="address" value="<?php echo $user->address ?>">
          <label>Dirección</label>
        </div>
        <div class="col-md-6">
          <input type="text" id="population" class="field" name="population" value="<?php echo $user->population ?>">
          <label>Población</label>
        </div>
        <div class="col-md-6">
          <input type="text" id="province" class="field" name="province" value="<?php echo $user->province ?>">
          <label>Provincia</label>
        </div>
        <div class="col-md-12">
          <input type="text" id="iban" class="field" name="iban" value="<?php echo $user->iban ?>">
          <label>Cuenta Corriente Banco</label>
        </div>
      </div>
    </div>
    <div class="col-md-12 col-lg-6 ">

      <div class="row">
        <div class="col-md-6 lineRight">
          <h2>Datos Comerciales</h2>
          <table class="table formLine2">
            <tr>
              <td>
                <select  class="field" id="coach_id" name="coach_id" style="width: 100%; cursor: pointer;font-weight:bold;" placeholder="Comercial asignado" >
                  <option value="">Comercial</option>
                  <?php
                  foreach ($allCoachs as $k => $v):
                    $sel = ($user->coach_id == $k) ? 'selected' : '';
                    ?>
                    <option value="<?= $k ?>" <?= $sel; ?>>
                      <?php echo $v ?>
                    </option>
                    <?php
                  endforeach;
                  ?>
                </select>
              </td>
              <td><input class="field" type="text" id="costComercial" name="costComercial" value="<?php showIsset('costComercial', $lstMetas) ?>"></td>
            </tr>
            <tr>
              <td ><b>Alq. Mensual Hotspot</b></td>
              <td><input class="field" type="text" id="costAlquiler" name="costAlquiler" value="<?php showIsset('costAlquiler', $lstMetas) ?>"></td>
            </tr>
            <tr class="topLine">
              <td><b>TOTAL GASTO MENSUAL</b></td>
              <td><input class="field" type="text" id="costTotal" name="costTotal" value="<?php showIsset('costTotal', $lstMetas) ?>"></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6 formLine">
          <h2>Datos del Hotspot</h2>
          <div class="col-md-12">
            <input class="field" type="text" id="hotspot_imac" name="hotspot_imac" required value="<?php echo $user->hotspot_imac ?>">
            <label>IMAC</label>
          </div>
          <div class="col-md-12">
            <input class="field" type="date" id="hotspot_date" name="hotspot_date" value="<?php echo $user->hotspot_date ?>">
            <label>Activación</label>
          </div>
        </div>
      </div>




      <div class="row">
        <div class="col-md-4 mt-1">
          <select name="status" class="form-control">
            <option value="1" <?php if ($user->status == 1) echo "selected"; ?>>Activo</option>
            <option value="0" <?php if ($user->status != 1) echo "selected"; ?>>No Activo</option>
          </select>
        </div>
        <div class="col-md-4 mt-1">
          <button class="btn btn-success" type="submit">
            <i class="fa fa-floppy-o" aria-hidden="true"></i> Actualizar
          </button>
        </div>
      </div>

    </div>


  </form>
</div>

<div class="col-md-12 push-30 bg-white" style="padding: 20px 0px;">

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-header-bg">
      <thead>
        <tr>
          <th class="text-center static">{{$year}}</th>
          <th class="first-col"></th>
          <?php foreach ($months as $month): ?>
            <th class="text-center"><?php echo $month; ?></th>
          <?php endforeach ?>
          <th class="text-center">Total ANUAL</th>
        </tr>
      </thead>
      <tbody>
        <!-- MENSUALIDADES -->
        <?php
        $totalAnualUser = $totalAnualNP = 0;
        if (isset($usedRates))
          foreach ($usedRates as $id => $name):
            $totalServiceUser = 0;
            $totalNoPay = 0;
            ?>
            <tr>
              <td class="text-center static"><b>{{$name}}</b></td>
              <td class="first-col"></td>
              <?php foreach ($months as $key => $month): ?>
                <td class="text-center">
                  <?php
                  $empty = true;
                  if (isset($uLstRates[$key][$id])) {
                    $aux = $uLstRates[$key][$id];
                    $tAux = 0;
                    if (count($aux) > 0) {
                      $empty = false;
                      foreach ($aux as $k => $v) {
                        $import = $v['price'];
                        if ($v['paid']):
                          ?>
                          <div class="label label2 label-success events openEditCobro" data-cobro="<?php echo $v['cid'] ?>" data-id="<?php echo $v['id'] ?>">
                            {{$import}} €
                          </div>
                          <?php
                          $totalServiceUser += $import;
                        else:
                          ?>
                          <div class="label label2 label-danger events openCobro" data-rate="<?php echo $v['id'] ?>" data-id="<?php echo $v['id'] ?>">
                            {{$import}} €<toltip data-k="2"/>
                          </div>
                          <?php
                          $totalNoPay += $import;
                        endif;
                      }
                    }
                  }
                  if ($empty)
                    echo '--';
                  ?>
                </td>
              <?php endforeach ?>
              <td class="text-center">
                <b>
                  <?php
                  $totalAnualUser += $totalServiceUser;
                  echo $totalServiceUser
                  ?>€

                  <?php
                  if ($totalNoPay > 0) {
                    $totalAnualNP += $totalNoPay;
                    echo '/ <span class="no-pay">' . moneda($totalNoPay) . '</span>';
                  }
                  ?>
                </b> 
              </td>
            </tr>
          <?php endforeach ?>

        <tr class="tbl_totales">
          <td class="static" >
            <b>TOTALES</b>
          </td>
          <td class="first-col"></td>
          <?php foreach ($months as $key => $month): ?>
            <td>
              <?php echo moneda($totalUser[$key]); ?>
            </td>
          <?php endforeach; ?>
          <td >
            <?php echo moneda($totalAnualUser); //+ $totAnualBonoUser + $totAnualBonoEspUser;    ?>
          </td>
        </tr>
        @if($totalAnualNP>0)
        <tr>
          <td class="text-center static" style="background-color: #ffc3c3;">
            <b>DEBE</b>
          </td>
          <td class="first-col"></td>
          <?php foreach ($months as $key => $month): ?>
            <td class="text-center" style="background-color: #ffc3c3;">
              <?php echo moneda($totalUserNPay[$key]); ?>
            </td>
          <?php endforeach; ?>
          <td class="text-center" style="background-color: #ffc3c3;">
            {{moneda($totalAnualNP)}}
          </td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>