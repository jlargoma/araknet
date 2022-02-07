<h3>Resumen Gastos / Mes</h3>
<div class="table-responsive">
  <table class="table table-resumen">
    <thead>
      <tr class="resume-head">
        <th class="static">Concepto<br>&nbsp;</th>
        <th class="static-2">Total<br>{{moneda(array_sum($tYear))}}</th>
        <th class="first-col-2"></th>
        @foreach($lstMonths as $k => $month)
        @if ($k>0)
        <th>{{$month}}<br/>{{moneda($tYear[$k])}}</th>
        @endif
        @endforeach
      </tr>
    </thead>
    <tbody>

      @foreach($listGasto_g as $k=>$item)
      <tr>
        <td class="static">{{$gTypeGroup[$k] ?? $k}}</td>
        <td class="static-2">{{moneda($item[0],false)}}</td>
        <td class="first-col-2"></td>
        @foreach($lstMonths as $k_month=>$month)
        <?php if ($k_month == 0) continue; ?>
        <td>{{moneda($item[$k_month],false)}}</td>
        @endforeach
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

