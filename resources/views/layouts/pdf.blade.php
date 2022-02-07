<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    <title>@yield('title')</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no"/>
    
    @yield('styles')
    <style>
        .dt {
          width: 24px !important;
          text-align: center;
          display:inline-block;
          padding: 0px !important;
        }
        .dt2 {
          width: 170px !important;
          border: 1px solid;
          vertical-align:top;
          display: inline-block;
          height: 113px;
          margin-right: 5px;
          margin-bottom: 3px;
          padding: 1px 7px;
          font-size: 11px;
        }
        .break{page-break-after: always;}
        
      </style>
  </head>
  <body style="max-width: 860px; margin: auto;">
    @yield('content')
    <script type="text/php">
    if (isset($pdf)) {
        $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
  </body>
</html>
