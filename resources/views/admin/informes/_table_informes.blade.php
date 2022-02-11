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
            <th class="text-center">Usuario</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($charges as $charge): ?>
        <tr>
            <td class="text-center sorting_disabled">{{$charge->id}}</td>
            <td class="text-center"><b>{{dateMin($charge->date_payment)}}</b></td>
            <td class="text-center">
                <?php
                echo (isset($aUsers[$charge->customer_id])) ? $aUsers[$charge->customer_id] : ' - ';
                ?>
            </td>
            <td class="text-center">
                <?php
                if ($charge->rate_id>0) echo (isset($aRates[$charge->rate_id])) ? $aRates[$charge->rate_id] : ' - ';
                if ($charge->bono_id>0) echo (isset($aBonos[$charge->bono_id])) ? $aBonos[$charge->bono_id] : ' - ';
                ?>
            </td>
            <td class="text-center">
                <?php
                echo (isset($aTRates[$charge->rate_id])) ? $aTRates[$charge->rate_id] : ' - ';
                ?>
            </td>
            <td class="text-center">{{moneda($charge->import,false,1)}}</td>
            <td class="text-center">
              <?php 
              if(isset($aURates[$charge->id])){
                $monthAux = $aURates[$charge->id];
                showIsset($monthAux,$months);
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
                $user = '--';
                if (isset($aCargesusers[$charge->id])){
                  $aux = $aCargesusers[$charge->id];
                  $user = isset($ausers[$aux]) ? $ausers[$aux]: '--'; 
                }
                echo $user;
                ?>
            </td>
        </tr>
		<?php endforeach ?>
        </tbody>
    </table>
</div>