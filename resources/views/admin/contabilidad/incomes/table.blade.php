<!--    TABLA                                  -->
<h2 class="font-w600">
    Listado de Ingresos de <b><?php echo $year?></b>
</h2>
<div class="table-responsive" >
    <table class="table ticomes">
       <thead>
        <tr>
          <th class="static thBlue">Servicio</th>
          <th class="first-col"></th>
          <th class="">Total<br>{{sumMonthValue($totals)}}</th>
          @foreach($monts as $k=>$v)
          <th>{{$v}}<br/>{{moneda($totals[$k])}}</th>
          @endforeach
        </tr>
       </thead>
       <tbody>
          @foreach($lst as $k1=>$d1)
          <tr class="d1" data-k="{{$k1}}">
            <td class="static"><i class="fa fa-eye"></i> {{$d1['name']}}</td>
            <td class="first-col"></td>
            <td><b>{{sumMonthValue($d1)}}</b></td>
            @foreach($monts as $km=>$vm)
            <td>{{moneda($d1[$km])}}</td>
            @endforeach
          </tr>
            @if(count($d1['slst'])>0)
              @foreach($d1['slst'] as $k2=>$d2)
                <tr class="d2 d1_{{$k1}} " data-k="{{$k1}}_{{$k2}}" >
                  <td class="static"><i class="fa fa-eye"></i> {{$family[$k2]}}</td>
                  <td class="first-col"></td>
                  <td><b>{{sumMonthValue($familyTotal[$k2])}}</b></td>
                  @foreach($monts as $km=>$vm)
                  <td>{{moneda($familyTotal[$k2][$km])}}</td>
                  @endforeach
                </tr>
                @foreach($d2 as $k3=>$d3)
                  <?php 
                  if (count($d3)<1) continue; ?>
                  <tr class="d3 d2_{{$k1}}_{{$k2}} " >
                    <td class="static">{{$d3['name']}}</td>
                    <td class="first-col"></td>
                    <td><b>{{sumMonthValue($d3)}}</b></td>
                    @foreach($monts as $km=>$vm)
                    <td>{{moneda($d3[$km])}}</td>
                    @endforeach
                  </tr>
                @endforeach
              @endforeach
            @endif
            @if(count($d1['lst'])>0)
              @foreach($d1['lst'] as $d2)
              <?php 
              if (count($d2)<1) continue; ?>
              <tr class="d3 d1_{{$k1}} ">
                <td class="static"><?php echo isset($d2['name']) ? $d2['name']:""; ?></td>
                <td class="first-col"></td>
                <td><b>{{sumMonthValue($d2)}}</b></td>
                @foreach($monts as $k=>$v)
                <td><?php echo isset($d2[$k]) ? moneda($d2[$k]):""; ?></td>
                @endforeach
              </tr>
              @endforeach
            @endif
          @endforeach
       </tbody>
</table>
</div>
<!--    TABLA                                  -->