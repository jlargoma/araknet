<?php 
$auxTM = [];
$pmName = ['c'=>'Cash','v'=>'Visa','b'=>'Banco'];
foreach ($monts as $k=>$v){
  $auxTM[$k] = $pay_method['c'][$k] + $pay_method['v'][$k] + $pay_method['b'][$k];
}
?>
<br class='mt-1em' />
<h2 class="font-w600">
    Formas de cobros en <b><?php echo $year?></b>
</h2>
<div class="table-responsive" >
    <table class="table">
        <tr>
          <th class="static thBlue">Tipo</th>
          <th class="first-col"></th>
          <th class="">Total <br/>({{ moneda(array_sum($auxTM))}})</th>
          @foreach($monts as $k=>$v)
          <th>{{$v}} <br/>({{ moneda($auxTM[$k])}})</th>
          @endforeach
        </tr>
         @foreach($pay_method as $k1=>$v1)
          <tr>
            <td class="static"><?php echo $pmName[$k1]; ?></td>
            <td class="first-col"></td>
            <td><b>{{ moneda(array_sum($v1))}}</b></td>
            @foreach($monts as $k=>$v)
            <td>{{moneda($v1[$k])}}</td>
            @endforeach
          </tr>
            @endforeach
</table>
</div>
<!--    TABLA                                  -->