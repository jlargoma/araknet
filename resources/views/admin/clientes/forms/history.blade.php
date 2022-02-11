<h3 class="text-left">HISTORIAL CITAS</h3>
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th>Día</th>
        <th>Horario</th>
        <th>Tipo</th>
        <th>Personal</th>
        <th>Pagado</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($oDates):
        foreach ($oDates as $k => $v):
          $dateTime = strtotime($v->date);
          $type = '';
          switch ($v->date_type) {
            case 'nutri': $type = 'Nutrición';
              break;
            case 'fisio': $type = 'Fisioterapeuta';
              break;
            case 'pt': $type = 'Entr. Pers.';
              break;
            default: $type = 'Otros';
              break;
          }
          ?>
          <tr>
            <td>{{convertDateToShow_text(date('Y-m-d',$dateTime),true)}}</td>
            <td>{{date('H',$dateTime)}} Hrs</td>
            <td>{{$type}}</td>
            <td>
              <?php
              if (isset($allUsers[$v->user_id])) {
                $personal = $allUsers[$v->user_id];
                echo "<b>$personal->n</b> ($personal->rn)";
              }
              ?>
            </td>
            <td><?php
              $uRates = $v->uRates;
              if ($uRates)
                echo ($uRates->charge_id > 0) ? 'SI' : 'NO';
              else
                echo 'NO';
              ?></td>
          </tr>
    <?php
  endforeach;
endif;
?>
    </tbody>
  </table>
</div>
