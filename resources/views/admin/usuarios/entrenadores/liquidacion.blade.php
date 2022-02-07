<div class="col-xs-12 table-responsive">
    <table class="table table-borderless table-striped table-vtop">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-left">Concepto</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $total = $salary;
            ?>
            <tr>
                <td class="text-center"><strong><?php echo $i ?></strong></td>
                <td class="text-left font-s20" style="text-transform: uppercase;">Salario Base</td>
                <td class="text-right font-s20"><strong><?php echo $salary; ?>€</strong></td>
            </tr>
            <?php foreach ($classLst as $key => $pago): ?>
                <?php $i++ ?>
                <tr>
                    <td class="text-center"><strong><?php echo $i ?></strong></td>
                    <td class="text-left font-s18 turnos">
                        
                        <?php echo $classLst[$key]?>
                        <?php 
                        if(isset($pagosClase[$key])):
                            foreach ($pagosClase[$key] as $turno){
                            echo '<br/><small>'.$turno.'</small>';
                            }
                        endif;
                        ?>
                    </td>
                    <td class="text-right font-s20"><strong><?php echo $totalClase[$key] ?>€</strong></td>
                </tr>
                <?php $total += $totalClase[$key]; ?>
            <?php endforeach ?>
            <?php foreach ($totalExtr as $key => $pago): ?>
                <?php $i++ ?>
                <tr>
                    <td class="text-center"><strong><?php echo $i ?></strong></td>
                    <td class="text-left font-s18 turnos">{{$nExtr[$key]}}</td>
                    <td class="text-right font-s20"><strong><?php echo $pago ?>€</strong></td>
                </tr>
                <?php $total += $pago; ?>
            <?php endforeach ?>
            <tr class="success">
                <td colspan="2" class="text-right text-uppercase"><strong>Total:</strong></td>
                <td class="text-right font-s24"><strong><?php echo $total ?>€</strong></td>
            </tr>
        </tbody>
    </table>
</div>
