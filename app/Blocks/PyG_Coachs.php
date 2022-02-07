<h2>Registros de SUELDOS Y SALARIOS</h2>

<div class="table-responsive">
<table class="table t-center nowrap">
  <tr>
    <th class="t-left">Coach</th>
    <th>Tipo</th>
    <th>Total</th>
    <?php
    foreach ($data['months'] as $k => $v):
      echo '<th>' . $v . '</th>';
    endforeach;
    ?>
  </tr>
  <tbody>
    <?php $totalMonthCoach = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; ?>
<?php $totalYear = 0; ?>
<?php foreach ($data['liq'] as $d): ?>
      <tr>
        <td class="t-left"><b><?php echo $d['username']; ?></b></td>
        <td > 
          <?php
          switch ($d['role']) {
            case 'admin';
            case 'administrativo';
              echo 'Admin';
              break;
            case 'teach';
              echo 'P.T.';
              break;
            case 'nutri';
              echo 'Nutri';
              break;
            case 'fisio';
              echo 'Fisio';
              break;
            case 'teach_nutri';
              echo 'P.T. / Nutri';
              break;
            case 'teach_fisio';
              echo 'P.T. / Fisio';
              break;
            case 'empl';
              echo 'Empleado';
              break;
          }
          ?>
        </td>
        <td >
        <?php
          $totalYear += $d[0];
        ?>
        <?php echo moneda($d[0]); ?>
        </td>
          <?php
          foreach ($data['months'] as $k => $v):
            $liq = isset($d[$k]) ? $d[$k] : 0;
            ?>
            <td><?php echo moneda($liq); ?></td>
            <?php $totalMonthCoach[$k] += $liq; ?>
          <?php endforeach; ?>
      </tr>
<?php endforeach ?>
    <tr>
      <td  colspan="2" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 24px;">
        TOTAL ANUALES
      </td>
      <td   style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2;">
        <b><?php echo moneda($totalYear); ?></b>
      </td>
      <?php for ($i = 1; $i <= 12; $i++) : ?>
        <td  style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2;">
          <b><?php echo moneda($totalMonthCoach[$i]); ?></b>
        </td>
<?php endfor; ?>

    </tr>
  </tbody>
</table>
</div>