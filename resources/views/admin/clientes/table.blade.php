<?php $auxPay = $auxToPay = [];
for($i=1;$i<13;$i++){
  $auxPay[$i] = 0;
  $auxToPay[$i] = 0;
}
?>

<table class="table table-striped js-dataTable-full-clients table-header-bg">
    <thead>
        <tr>
            <th class="text-center tc0 hidden-xs hidden-sm"></th>
            <th class="text-center tc1">Nombre Cliente<br></th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center tc3">Total</th>
            @for($i=1;$i<13;$i++)
            <th class="text-center tc4">
                {{$months[$i]}}
                <label class="text-danger">{{moneda($toPay[$i])}}</label>
            </th>
            @endfor
        </tr>
    </thead>
    <tbody>
        <?php foreach ($customers as $key => $customer): ?>
            <tr>
                <td class="text-center hidden-xs hidden-sm tc0">
                    <label class="css-input switch switch-sm switch-success">
                        <?php $checked = ($customer->status == 1) ? 'checked' : ''; ?>
                        <input type="checkbox" class="switchStatus" data-id="<?php echo $customer->id ?>" <?php echo $checked ?>><span></span>
                    </label>
                </td>
                <td class="text-left tc1"> 
                    <a  class="openUser" data-id="<?php echo $customer->id; ?>"  data-type="user" data-original-title="Editar user" ><b><?php echo $customer->name; ?></b></a>
                </td>
                <td class="text-center tc2">
                    <button class="btn btn-default add_rate" data-toggle="modal" data-target="#modalCliente" data-iduser="<?php echo $customer->id; ?>">
                        <i class="fa fa-usd" aria-hidden="true"></i>
                    </button>
                </td>
                <?php $tCustPay = isset($custPays[$customer->id]) ? $custPays[$customer->id] : 0;?>
                <td class="text-center tc3 yb">{{moneda($tCustPay)}}</td>
                <?php 
                $pending = null;
                for ($i = 1; $i < 13; $i++):
                    
                    $textAux = '';
                    $auxPend = 0;
                    if (isset($cRates[$i][$customer->id])):
                      if ($pending == null) $pending = false;
                      foreach ($cRates[$i][$customer->id] as $rate):
                        foreach ($rate as $r):
                          if($r['paid']):
                            $auxPay[$i] += $r['price'];
                            $textAux.= '<div class="label events label-success openEditCobro" data-cobro="'.$r['cid'].'"  data-id="'.$r['id'].'">';
                          else:
                            $pending = true;
                            $auxPend += $r['price'];
                            $auxToPay[$i] += $r['price'];
                            $textAux.= '<div class="label events label-danger openCobro" data-rate="'.$r['id'].'" data-id="'.$r['id'].'">';
                          endif;
                          $textAux.= moneda($r['price']).'</div>';
                        endforeach;
                      endforeach;
                    endif;
                        ?>
                    <td class="text-center tc4 yb" data-order="<?php echo $auxPend; ?>">
                          
                          <?php echo $textAux; ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot style="display: none;">
      <tr>
        <td colspan="4"></td>
        <?php 
          for ($i = 1; $i < 13; $i++): 
        ?>
        <td>{{$auxPay[$i]}} / {{$auxToPay[$i]}}</td>
        <?php
          endfor;
        ?>
      </tr>
    </tfoot>
</table>