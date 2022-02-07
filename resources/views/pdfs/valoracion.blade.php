<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>VALORACIÓN DE SALUD Y PREPARACIÓN AL ENTRENAMIENTO</title>
    <!-- Latest compiled and minified CSS & JS -->
    <style type="text/css">
      body{max-width: 800px; margin-top: 25px; margin: auto;}
      .table-borderless {
        border: none;
      }
      .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        border-style: solid;
      }
      .table > thead > tr > th, .table > tfoot > tr > th {
        padding: 16px 10px 12px;
        font-family: "Source Sans Pro", "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
      }
      .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
      }

      .text-center {
        text-align: center;
      }
      .text-right {
        text-align: right;
      }
      th {
        text-align: left;
      }
      .row{
        display: contents;
        clear: both;
        overflow: auto;
      }
      .col-md-6{
        width: 46%;
        display: inline-grid;
        margin: 1%;
      }

      .turnos small{
        margin-left: 1em;
        display: block;
        font-size: 12px;
      }
      h1 {color: #7b7b7b;}
      h1 b {color: #000000;}
      thead{background-color: #cfddef;}
      tfoot{background-color: #f0f0f0;}
      .formValora u { margin: -8px 0 12px !important;}

      .formValora .field {
        min-height: 1.4em !important;}
      .qstion1 .form-material.mt-2 {
        margin: 1em 0;
      }
      .formValora h4.mt-1 {
        color: #000 !important;
        border-bottom: 2px solid  !important;
        border-top: 2px solid !important;
      }
      .formValora h5 {
        font-size: 14px !important;
      }
      .formValora table th {font-size: 14px !important;}
      .mt-1{margin-top: 1em;}
      .mt-2{margin-top: 4px;}
      .btn-print {
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        background-color: #19191982;
        left: 0;
        padding: 1em;
      }
      .btn-print button{
        background-color: #d44242;
        border-radius: 4px;
        padding: 10px;
        color: #FFF;
        font-weight: bold;
      }

      .formValora .field.txt {
    min-height: 7em !important;
}
      @page {
        size: letter;
        margin: .5in;
      }
      header, footer {
        width: 100%; height: .5in;
      }
      header {
        position: absolute;
        top: 0;
        height: 60px;
        background-color: white;
        width: 800px;
        text-align: center;
      }

      .logo{
        width: 175px;
      }
      @media screen {
        .paging h1{
          padding-top: 2em;
        }
        .printBreak,.block-logo{display: none;}
      }
      @media print {
        .btn-print{display: none;}

        table.paging thead td, table.paging tfoot td {
          height: .5in;
        }

/*        header, footer {
          position: fixed;
          left: 0px;
          width: 100%;
        }
        header{
          top: 0px;
        }
        footer {
          bottom: 0px !important;
          text-align: center;
          height:10px;
        }*/
        .paging{
          padding: 8em 0px;
        }
        .printBreak{
          break-before: page;
        }
      }

    </style>
  </head>
  <body>
    <header>
      <img src="/assets/logo-retina.png" alt="Evolutio" class="logo"/>
    </header>
    <div class="paging">

      <h1>VALORACIÓN DE SALUD Y PREPARACIÓN AL ENTRENAMIENTO</h1>
      @include('customers.blocks.valoracion')

    </div>
    <div class="btn-print">
      <button onclick="window.print();">Imprimir</button>
    </div>

  </body>
</html>
