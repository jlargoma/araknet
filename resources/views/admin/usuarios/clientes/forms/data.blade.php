<h3 class="text-left">Datos del Usuario
  <button class="btn btn-default add_rate" data-idUser="<?php echo $user->id; ?>">
    <i class="fa fa-usd" aria-hidden="true"></i> Asignar Servicio
  </button>
  <button class="btn btn-default add_bono" data-idUser="<?php echo $user->id; ?>">
    <i class="fa fa-plus-circle" aria-hidden="true"></i> Asignar Bono
  </button>
  
  @if(isset($encNutr))
  <a href="/admin/ver-encuesta/{{$btnEncuesta}}" class="btn btn-default" target="_black">
    <i class="fa fa-eye" aria-hidden="true"></i> Ver encuesta Nutrición
  </a>
  @endif
</h3>
<form class="row" action="{{ url('/admin/clientes/update') }}" method="post">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="id" value="{{ $user->id }}">
  <div class="col-md-6 ">
    <div class="form-material mt-2">
      <input class="form-control" type="text" id="name" name="name" required value="<?php echo $user->name ?>">
      <label for="name">Nombre</label>
    </div>
    <div class="form-material mt-2">
      <input type="text" id="email" class="form-control" name="email" required value="<?php echo $user->email ?>">
      <label for="email">E-mail</label>
    </div>
    <div class="form-material mt-2">
      <input class="form-control" type="number" id="telefono" name="telefono" required maxlength="9" value="<?php echo $user->telefono ?>">
      <label for="telefono">Teléfono</label>
    </div>
  </div>
  <div class="col-md-6 ">
    <div class="form-material mt-2">
      <input class="form-control" type="text" id="dni" name="dni" value="<?php echo $user->dni ?>">
      <label for="name">DNI</label>
    </div>
    <div class="form-material mt-2">
      <input type="text" id="address" class="form-control" name="address" value="<?php echo $user->address ?>">
      <label for="email">Dirección</label>
    </div>
  </div>
  <div class="col-md-6 hidden">
    <div class="form-material ">
      <input class="form-control" type="password" id="password" name="password" value="">
      <label for="password">Contraseña</label>
    </div>
  </div>
  <div class="col-md-2 mt-1 fFIDELITY">
    <select name="fidelity" class="form-control">
      <option value="none" <?php if($uPlan == 'none') echo "selected"; ?>>SIN PLAN</option>
      <option value="basic" <?php if($uPlan == 'basic') echo "selected"; ?>>PLAN BASICO</option>
      <option value="fidelity" <?php if($uPlan == 'fidelity') echo "selected"; ?>>FIDELITY</option>
    </select>
    <?php if($uPlan == 'fidelity') echo '<i class="fa fa-heart text-success"></i>'; ?>
    <?php if($uPlan == 'basic') echo '<i class="fa fa-heart text-danger"></i>'; ?>
  </div>
  <div class="col-md-2 mt-1">
    <select name="status" class="form-control">
      <option value="1" <?php if($user->status == 1) echo "selected"; ?>>Activo</option>
      <option value="0" <?php if($user->status != 1) echo "selected"; ?>>No Activo</option>
    </select>
  </div>
  <div class="col-md-2 mt-1">
    <button class="btn btn-success" type="submit">
      <i class="fa fa-floppy-o" aria-hidden="true"></i> Actualizar
    </button>
  </div>
</form>


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
              <?php echo moneda($totalUser[$key]);?>
            </td>
          <?php endforeach; ?>
          <td >
              <?php  echo moneda($totalAnualUser); //+ $totAnualBonoUser + $totAnualBonoEspUser;   ?>
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