<!DOCTYPE html>
<html lang="es">
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include('invoices.invoice-style')
  </head>
  <body>
    <div class="content">
      @include('invoices.invoice-content')
      <?php printInvoiceContent($oInvoice, $items); ?>
    </div>
    @if(isset($url))
    <div style="width: 100%;text-align: center;"> 
      Descarga tu factura haciendo click <a href="{{$url}}" type="Descargar factura" target="_black">Aquí</a>
    </div>
    @endif
  </body>
</html>

