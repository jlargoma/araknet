<?php $result = []; ?>
<div class="table-responsive">
    <table class="table table-bordered table-liq table-header-bg">
        <thead>
            <tr>
              <th class="text-center static" style="height: 66px;">{{$year}}</th>
                <th class="first-col"></th>
                @foreach($aMonths as $k=>$v)
                <?php 
                  $aux = (isset($liqLst[$k])) ? $liqLst[$k] : 0;
                  $aux += (isset($CommLst[$k])) ? $CommLst[$k] : 0;
                  $aux += (isset($payMonth[$k])) ? $payMonth[$k] : 0;
                ?>
                <th class="text-center">{{$v}}<br>{{moneda($aux)}}</th>
                @endforeach
                 <?php 
                  $aux = $liqLst[0] + $CommLst[0] + $payMonth[0];
                ?>
                <th class="text-center">Total<br>{{moneda($aux)}}</th>
                
            </tr>
        </thead>
        <tbody>
            <!-- LIQUIDACIONES -->
            <tr>
              <td class="text-center static" style="height: 56px;">
                    <b>PAGOS MENS.</b>
                </td>
                <td class="first-col"></td>
                @foreach($aMonths as $k=>$v)
                <?php 
                  $aux = (isset($liqLst[$k])) ? $liqLst[$k] : 0;
                  $result[$k] = $aux;
                ?>
                <td class="text-center">
                    <input type="number" 
                           data-k="{{$k}}" 
                           class="form-control liquidation" 
                           value="{{$aux}}">
                </td>
                @endforeach
                <td class="text-center">{{moneda($liqLst[0])}}</td>
            </tr>
              <td class="text-center static" style="height: 56px;">
                    <b>Comisiones</b>
                    <small>Calculado</small>
                </td>
                <td class="first-col"></td>
                @foreach($aMonths as $k=>$v)
                <?php 
                  $aux = "";
                  if (isset($CommLst[$k])){
                    $aux = $CommLst[$k];
                    $result[$k] += $aux;
                  }
                  
                  
                ?>
                <td class="text-center">
                    <input type="number" 
                           data-k="{{$k}}" 
                           class="form-control commision" 
                           value="{{$aux}}">
                    <small>{{moneda($CommLstCalc[$k])}}</small>
                </td>
                @endforeach
                <td class="text-center">{{moneda($CommLst[0])}}</td>
            </tr>
            <tr>
                <td class="text-center static">
                    <b>Extraordinarios</b>
                </td>
                <td class="first-col"></td>
                @foreach($aMonths as $k=>$v)
                <?php 
                  $aux = (isset($payMonth[$k])) ? $payMonth[$k] : 0;
                  $result[$k] += $aux;
                ?>
                <td class="text-center">
                  {{moneda($aux)}}
                </td>
                @endforeach
                <td class="text-center">{{moneda(array_sum($payMonth))}}</td>
            </tr>
        </tbody>
    </table>
 
</div>
<small><b>Importante:</b> Los cambios en <i>Comisiones</i> no se refleja en la <i>Liquidación Mensual</i></small>
<style>

.table-liq td{
    margin: 0 !important;
    padding: 10px 0 !important;
    min-width: 80px;
}
    
input.form-control.liquidation,
input.form-control.commision {
    padding: 2px !important;
        border-left: none;
    text-align: center;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}

input[type=number] {
    -moz-appearance:textfield; /* Firefox */
}


tfoot tr td {
    background-color: aliceblue;
    font-weight: 700;
    text-align: center;
}

tfoot tr td.td-red {
    background-color: red;
    color: #FFF;
}
td.td-gren {
    background-color: #55f755;
}
.first-col {
    max-width: 0px !important;
    min-width: 0px !important;
}
.table-liq small {
    font-size: 10px;
    background-color: #ececec;
    width: 100%;
    display: block;
    padding: 3px;
    font-weight: bolder;
    border: 1px solid #FFF;
}

</style>
  