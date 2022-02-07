<div class="col-md-12 col-xs-12">
    <table class="table table-striped table-header-bg">
        <thead>
        <tr>
            <th class="text-center sorting_disabled"></th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Nombre cliente</th>
            <th class="text-center">Cuota / Concepto</th>
            <th class="text-center">Familia</th>
            <th class="text-center">Importe</th>
            <th class="text-center">MES</th>
            <th class="text-center">Forma pago</th>
            <th class="text-center">ENTRENADOR/FISIO</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($extrasCharges as $charge): ?>
        <tr>
            <td class="text-center sorting_disabled"></td>
            <td class="text-center"><b>{{dateMin($charge->date)}}</b></td>
            <td class="text-center">
		        <b><?php  echo (!empty($charge->comment)) ?strtoupper($charge->comment) : strtoupper($charge->concept) ?></b>
            </td>
            <td class="text-center">
                <b><?php  echo strtoupper($charge->concept) ?></b>
            </td>
            <td class="text-center">
                
            </td>
            <td class="text-center">
                <b><?php  echo $charge->import; ?> €</b>
            </td>
            <td class="text-center">
                <b>METALICO</b>
            </td>
            <td class="text-center">
            </td>
        </tr>
        <?php endforeach ?>
		<?php foreach ($charges as $charge): ?>
        <tr>
            <td class="text-center sorting_disabled">{{$charge->id}}</td>
            <td class="text-center"><b>{{dateMin($charge->date_payment)}}</b></td>
            <td class="text-center">
                <?php
                echo (isset($aUsers[$charge->id_user])) ? $aUsers[$charge->id_user] : ' - ';
                ?>
            </td>
            <td class="text-center">
                <?php
                if ($charge->id_rate>0) echo (isset($aRates[$charge->id_rate])) ? $aRates[$charge->id_rate] : ' - ';
                if ($charge->bono_id>0) echo (isset($aBonos[$charge->bono_id])) ? $aBonos[$charge->bono_id] : ' - ';
                ?>
            </td>
            <td class="text-center">
                <?php
                echo (isset($aTRates[$charge->id_rate])) ? $aTRates[$charge->id_rate] : ' - ';
                ?>
            </td>
            <td class="text-center">{{moneda($charge->import,false,1)}}</td>
            <td class="text-center">
              <?php 
              if(isset($aURates[$charge->id])){
                $monthAux = $aURates[$charge->id];
                show_isset($monthAux,$months);
              }
              ?>
            </td>
            <td class="text-center">
                <?php 
                switch ($charge->type_payment){
                  case 'banco':
                    echo 'BANCO';
                    break;
                  case 'cash':
                    echo 'METALICO';
                    break;
                  case 'card':
                    echo 'TARJETA';
                    break;
                  case 'bono':
                    echo 'BONO';
                    break;
                }
                ?>
            </td>
            <td class="text-center">
                <?php 
                $coach = '--';
                if (isset($aCargesCoachs[$charge->id])){
                  $aux = $aCargesCoachs[$charge->id];
                  $coach = isset($aCoachs[$aux]) ? $aCoachs[$aux]: '--'; 
                }
                echo $coach;
                ?>
            </td>
        </tr>
		<?php endforeach ?>
        </tbody>
    </table>
</div>