<h2>Tipo de Pago</h2>
<div class="table-responsive">
  <table class="table table-fixed">
    <thead >
      <tr class="fixed-head">
        <th class="static"></th>
        <th class="first-col">Total</th>
        <th class="text-center">%</th>
        @foreach($lstMonths as $month)
        <th class="text-center">{{getMonthSpanish($month['m'])}} {{$month['y']}}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($salesBy_TypePay as $type=>$item)
      <tr class="salary_first">
        <td class="static">{{$type}}</td>
        <td class="first-col nowrap"><?php echo number_format($item['total'], 1, '.', ','); ?> €</td>
        <td class="line-td text-center">{{$item['percent']}}%</td>
        @foreach($lstMonths as $k=>$month)
        @if(isset($item[$k]))
        <td class="line-td nowrap"><?php echo number_format($item[$k], 1, '.', ','); ?> €</td>
        @else
        <td class="line-td" >0 €</td>
        @endif
        @endforeach
      </tr>
      @endforeach
    </tbody>
  </table>
</div>