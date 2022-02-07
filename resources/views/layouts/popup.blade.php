<!DOCTYPE html>
<html dir="ltr" lang="en-US">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />

        <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700|Roboto:300,400,500,700" rel="stylesheet" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--[if lt IE 9]>
            <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="{{ asset('/admin-css/assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset('/admin-css/assets/css/oneui.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ assetV('/css/custom.css') }}">
        <script src="{{ asset('/admin-css/assets/js/core/jquery.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/bootstrap.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.slimscroll.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.scrollLock.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.placeholder.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/js.cookie.min.js') }}" ></script>
        <script src="{{ asset('/js/vendor/notify.min.js') }}" ></script>
        <script src="{{ assetV('/admin-css/assets/js/app.js') }}" ></script>
        <script src="{{ assetV('/admin-css/assets/js/custom.js') }}" ></script>
    </head>
    <body class="popup-body">
        <div class="content">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
        <toltip></toltip>
        <style>
            .popup-body{
                padding: 0px !important;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="{{ assetV('/admin-css/assets/css/styles.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ assetV('/admin-css/assets/css/mobile.css')}}">
        @yield('scripts')
    </body>
</html>