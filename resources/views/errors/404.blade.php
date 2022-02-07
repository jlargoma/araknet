<!DOCTYPE html>
<html class="no-focus" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>Error 404</title>
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Stylesheets -->
        <!-- Web fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

        <link rel="stylesheet" href="{{ asset('/admin-css/assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset('/admin-css/assets/css/oneui.css') }}">
    </head>
    <body>
        <!-- Error Content -->
        <div class="content bg-white text-center pulldown overflow-hidden">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <!-- Error Titles -->
                    <h1 class="font-s128 font-w300 text-modern animated zoomInDown">404</h1>
                    <h2 class="h3 font-w300 push-50 animated fadeInUp">Uuppss!! Tenemos un problemilla con nuestro servidor...</h2>
                    <!-- END Error Titles -->
                    <pre>
                        {{ $exception->getMessage() }}
                    </pre>
                    
                </div>
            </div>
        </div>
        <!-- END Error Content -->

        <!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
        <script src="{{ asset('admin-css/assets/js/core/jquery.min.js')}}"></script>
        <script src="{{ asset('admin-css/assets/js/core/bootstrap.min.js')}}"></script>
        <script src="{{ asset('admin-css/assets/js/core/jquery.slimscroll.min.js')}}"></script>
        <script src="{{ asset('admin-css/assets/js/core/jquery.scrollLock.min.js')}}"></script>
        <script src="{{ asset('admin-css/assets/js/core/jquery.appear.min.js')}}"></script>
        <script src="{{ asset('admin-css/assets/js/core/jquery.countTo.min.js')}}"></script>
        <script src="{{ asset('admin-css/assets/js/core/jquery.placeholder.min.js')}}"></script>
        <script src="{{ asset('admin-css/assets/js/core/js.cookie.min.js')}}"></script>
        <script src="{{ asset('admin-css/assets/js/app.js')}}"></script>
    </body>
</html>