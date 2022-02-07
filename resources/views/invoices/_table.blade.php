<div class="table-responsive">
  <div class="date-filter">
    <label for="date">Filtro Fechas:</label>
    <input type="text" class="js-datepicker  form-control" name="fecha" id="fecha" value="<?php echo date('d-m-Y') ?>" style="font-size: 12px">
  </div>
  <table class="table table-data table-striped" id="tableInvoices" >
    <thead>
      <tr>
        <th>F. Fact</th>
        <th># Fact</th>
        <th>Cliente</th>
        <th>DNI</th>
        <th>Importe</th>
        <th>Acciones</th>
      </tr>
    </thead>
  <tbody>
    @if($invoices)
    @foreach($invoices as $item)
    <tr>
      <td class="text-left" data-order="{{$item->date}}">
        <?php echo convertDateToShow_text($item->date, true); ?>
      </td>
      <td class="text-center"><?php echo $item->num?></td>
      <td class="text-center"><?php echo $item->name?></td>
      <td class="text-center"><?php echo $item->nif?></td>
      <td class="text-center">{{moneda($item->total_price,true,2)}}</td>
      <td class="text-center font-s16">
        <div class="btn-group">
          @if (isset($invoiceModal) && $invoiceModal)
          <a href="{{ route('invoice.updModal',[-1,$item->id]) }}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
          @else
          <a href="{{ route('invoice.edit',$item->id) }}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
          @endif
          <a href="{{ route('invoice.view',$item->id) }}" class="btn btn-xs btn-primary" target="_black"><i class="fa fa-eye"></i></a>
          <a href="{{ route('invoice.downl',$item->id) }}" class="btn btn-xs btn-success" target="_black"><i class="fa fa-download"></i></a>
          <a href="#" class="btn btn-xs btn-success sendInvoiceEmail" type="button" data-id="{{$item->id}}" title="Enviar factura"><i class="fa fa-envelope"></i></a>
        </div>
      </td>
    </tr>
    @endforeach
    @endif

  </tbody>
</table>
</div>