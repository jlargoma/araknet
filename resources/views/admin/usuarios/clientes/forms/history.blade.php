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
    switch ($v->date_type){
        case 'nutri': $type = 'Nutrición'; break;
        case 'fisio': $type = 'Fisioterapeuta'; break;
        case 'pt': $type = 'Entr. Pers.'; break;
        default: $type = 'Otros'; break;
    }
    $personal = isset($allCoachs[$v->id_coach]) ? $allCoachs[$v->id_coach] : '-';
        ?>
<tr>
    <td>{{convertDateToShow_text(date('Y-m-d',$dateTime),true)}}</td>
    <td>{{date('H',$dateTime)}} Hrs</td>
    <td>{{$type}}</td>
    <td>{{$personal}}</td>
    <td><?php 
    $uRates = $v->uRates;
    if ($uRates) echo ($uRates->id_charges > 0) ? 'SI' : 'NO'; 
    else echo 'NO';
    ?></td>
</tr>
        <?php
    endforeach;
endif;
?>
        </tbody>
    </table>
</div>
