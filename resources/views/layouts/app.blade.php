<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('/admin-css/assets/img/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-96x96.png') }}" sizes="96x96">
    <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-160x160.png') }}" sizes="160x160">
    <link rel="icon" type="image/png" href="{{ asset('/admin-css/assets/img/favicons/favicon-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/admin-css/assets/img/favicons/apple-touch-icon-180x180.png') }}">
  </head>
  <body>
    <div class="fondo"></div>

    <div class="container">
      <div class="content-box">
        <!--<img src="{{ asset('assets/logo-retina.png') }}" class="img-logo">-->
        <h1 class="siteName">{{ config('app.name', 'Laravel') }}</h1>
        @yield('content')
      </div>
    </div>
    <style>
      .fondo{
        width: 100%; height: 100%; position: fixed; left: 0; top: 0; background: url('{{ asset('/img/login.webp') }}') center center no-repeat; background-size: cover; 
      }
      .panel.divcenter{
        margin: 6em auto; background-color: rgba(255,255,255,0.93);
      }
      .container{
        text-align: center;
        font-size: 17px;
        position: absolute;
        min-width: 100%;
        letter-spacing: 1.1;
      }
      .content-box{
        max-width: 680px;
        margin: 4em auto;
        background-color: #fff;
        padding: 2em 1em;
        border-radius: 10px;
        box-shadow: 4px 4px 2px 0px #5a5a5a;
      }
      ul {
        padding: 0;
      }
      .container img.img-logo{
        width: 100%;
        position: relative;
        max-width: 320px;
        margin-bottom: 4em;
      }
      li {
        list-style: none;
        text-align: center;
        margin: 1em auto;
      }
      .card-header {
        text-align: left;
      }
      .siteName{
        padding: 5px;
        font-size: 43px;
        font-weight: bold;
        text-decoration: none;
        text-transform: uppercase;
        font-family: ui-monospace;
        background-color: #e3e3e3;
        margin-bottom: 1em;
      }
    </style>
    @yield('scripts')
  </body>
</html>
