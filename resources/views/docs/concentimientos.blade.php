<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
      .contenedor{
        margin: 1em auto;max-width: 840px;
      }
      img{
        width: 320px;
        margin: 3px auto;
        display: block;
      }
      .btn-print{
        display: block;
        text-align: right;
      }
      button{
        padding: 9px 44px;
        font-size: 18px;
        font-weight: bold;
      }
      .uname {
          text-align: center;
      }
      @media print {
        button{display: none;}
      }
    </style>
  </head>
  <body>
    <div class="contenedor">
      @include('customers.docs.'.$file)
      @if($sign)
      <img src="/admin/usuarios/sign/{{$fileName}}" >
      @else
      <p>Documento no Firmado</p>
      @endif
      <div class="uname">{{$u_name}}</div>
      <div class="btn-print">
      <button onclick="window.print()">Imprimir</button>
      </div>
    </div>
  </body>
</html>

