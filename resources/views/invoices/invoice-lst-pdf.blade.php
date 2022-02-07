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
      <?php 
      
        if($oInvoices){
          foreach($oInvoices as $oInvoice){
            $items = $oInvoice->getMetaContent('items');
            if ($items) $items = unserialize($items);
            echo '<div class="whatever">';
            printInvoiceContent($oInvoice,$items);
            echo '</div>';
          }
        }
      ?>
    </div>

  </body>
</html>

