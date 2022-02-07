<!--    TABLA                                  -->
<?php 
$tIngr = $currentY['Ingresos'];
$tGast = $currentY['Gastos']; 
?>
<h2 class="font-w600">
    Listado de Ingresos / Gastos de <b><?php echo $year?></b>
</h2>
<div class="table-responsive nowrap" >
    <table class="table">
      <thead>
        <tr>
          <th class="static thBlue">Ingresos</th>
          <th class="first-col"></th>
          <th class="">Total <br/>({{ moneda($tIngr[0])}})</th>
          @foreach($monts as $k=>$v)
          <th>{{$v}} <br/>({{ moneda($tIngr[$k])}})</th>
          @endforeach
        </tr>
       </thead>
       <tbody>
         @foreach($crLst as $k1=>$v1)
          <tr>
            <td class="static" data-t="i" data-id="{{$k1}}">{{$oRateTypes[$k1]}}</td>
            <td class="first-col"></td>
            <?php 
            $aux_i[0]+= $v1[0];
            ?>
            <td><b>{{ moneda($v1[0])}}</b></td>
            @foreach($monts as $k=>$v)
            <?php $aux_i[$k]+= $v1[$k];?>
            <td>{{moneda($v1[$k])}}</td>
            @endforeach
          </tr>
            @endforeach
       </tbody>
       <thead>
        <tr>
          <th class="static thBlue">Gastos</th>
          <th class="first-col"></th>
          <th class="">Total <br/>({{ moneda($tGast[0])}})</th>
          @foreach($monts as $k=>$v)
          <th>{{$v}}<br/>({{ moneda($tGast[$k])}})</th>
          @endforeach
        </tr>
       </thead>
       <tbody>
          @foreach($ggMonth as $k1=>$v1)
          <tr>
            <td class="static detail" data-t="e" data-id="{{$k1}}">{{$ggNames[$k1]}} <i class="fa fa-eye"></i></td>
            <td class="first-col"></td>
            <?php 
            $sumMonthValue = sumMonthValue($v1);
            $aux_e[0]+= $sumMonthValue;
            ?>
            <td><b>{{moneda($sumMonthValue)}}</b></td>
            @foreach($monts as $k=>$v)
            <?php $aux_e[$k]+= $v1[$k];?>
            <td>{{moneda($v1[$k])}}</td>
            @endforeach
          </tr>
            @endforeach
       </tbody>
       <tfoot>
         <tr class="">
           <td class="static thBlue" style="height: 48px">RESULTADO</td>
            <td class="first-col"></td>
            @foreach($aux_e as $k=>$v)
            <td>{{moneda($aux_i[$k]-$v)}}</td>
            @endforeach
          </tr>
       </tfoot>
       
</table>
</div>
<!--    TABLA                                  -->