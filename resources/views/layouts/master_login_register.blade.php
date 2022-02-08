<!DOCTYPE html>
<html dir="ltr" lang="en-US">
  <head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700|Roboto:300,400,500,700" rel="stylesheet" type="text/css" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->

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

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

    <link rel="stylesheet" href="{{ asset('/admin-css/assets/js/plugins/slick/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin-css/assets/js/plugins/slick/slick-theme.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/admin-css/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('/admin-css/assets/css/oneui.css') }}">
    <title>Login/Register</title>

  </head>

  <body class="stretched no-transition" data-loader="11" data-loader-color="#543456">

    <div id="wrapper" class="clearfix">

      <section id="content">

        <div class="content-wrap nopadding">

          <div class="section nopadding nomargin fondo"></div>

          <div class="container vertical-middle divcenter clearfix">

            <div class="panel panel-default divcenter noradius" style=" ">
              <div class="panel-body" style="padding: 40px;">
                <div class="col-xs-12 center">
          <!--                        <img src="{{ asset('assets/logo-retina.png') }}" class="img-responsive">-->
                  <h1>ARAKNET</h1>
                </div>

                @yield('content')

              </div>
            </div>
          </div>

      </section>

    </div>

    <div id="gotoTop" class="icon-angle-up"></div>
    <script src="{{ asset('/admin-css/assets/js/core/jquery.min.js') }}" ></script>
    <script src="{{ asset('/admin-css/assets/js/core/bootstrap.min.js') }}" ></script>
    <style>
      h2{
        background-color: #1b246cba;
        margin: 9px 0px;
        padding: 4px;
        display: block;
        color: #FFF;
        font-weight: bold;
        font-size: 19px;
      }
      .fondo{
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        background: url('{{ asset('/img/login.webp') }}') center center no-repeat;
        background-size: cover;
      }
      .container {
        position: absolute;
        width: 100% !important;
        text-align: center;
      }
      .panel{
        margin: 1em auto;
        max-width: 400px;
        background-color: rgba(255,255,255,0.95);
      }
      label {
        font-size: 16px;
        font-weight: 800;
        margin-top: 1em;
      }
      .invalid-feedback{
        color: red;
        font-size: 12px;
      }
    </style>
  </body>
</html>