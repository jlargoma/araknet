<!DOCTYPE html>
    <head>
        <meta charset="utf-8">

        <title>@yield('title')</title>

        <meta name="description" content="Admin">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">


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
        
        <!--<script src="https://js.stripe.com/v3/"></script>-->
        @yield('externalScripts')


    </head>
    <body>
       
        <div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed">            
            <nav id="sidebar">                
                <div id="sidebar-scroll">                                        
                    <div class="sidebar-content">                        
                        <div class="side-header side-content">                            
                            <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times"></i>
                            </button>
                            <a class="h5 text-white text-left" href="{{ url('/admin') }}">
                                <span class="sidebar-mini-hide font-w600">ARAKNET</span>
                            </a>
                        </div>                        
                        
                        <div class="side-content">
                            @include('layouts.year_selector', ['minimal' => true])
                            @include('layouts.navs')
                            
                        </div>                        
                    </div>                    
                </div>                
            </nav>            
            
            <header id="header-navbar" class="content-mini content-mini-full">                
                <ul class="nav-header pull-right">
                    <li class="text-center">
                        <a href="{{ url('/admin/clientes') }}" class="btn btn-success btn-home">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <?php 
                    $uRole = Auth::user()->role;
                    if ($uRole == "admin"):
                    ?>
                    <li class="text-center">
                        <a href="{{ url('admin/informes/cliente-mes') }}" class="btn btn-success btn-home">
                            Inf. Clientes mes
                        </a>
                    </li>
                    <li class="text-center">
                        <a href="{{ url('admin/informes/cuotas-mes') }}" class="btn btn-success btn-home">
                            Inf. Cuotas Mes
                        </a>
                    </li>
                    <li class="text-center">
                        <a href="{{ url('admin/informes/caja') }}" class="btn btn-success btn-home">
                            Caja
                        </a>
                    </li>
                    <li class="text-center">
                        <a href="{{ url('admin/informes/cobros-mes') }}" class="btn btn-success btn-home">
                            Cobros Mes
                        </a>
                    </li>
                    <?php else: ?>
                      <li class="text-center">
                        <button id="button-checkCash" class="btn btn-success btn-home" data-toggle="modal" data-target="#checkCash">
                            Caja
                        </button>
                    </li>
                    <?php endif; ?>
                </ul>                
                
                <ul class="nav-header pull-left">
                    <li class="hidden-md hidden-lg">                        
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                            <i class="fa fa-navicon"></i>
                        </button>
                    </li>
                    <li class="hidden-xs hidden-sm">                        
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                    </li>
                    <li><h1>@yield('headerTitle')</h1></li>
                    <li>@yield('headerButtoms')</li>
                </ul>                
            </header>            


            <main id="main-container" >
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
            </main>

            <div class="modal fade" id="checkCash" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-popout">
                    <div class="modal-content">
                        <div class="block block-themed block-transparent remove-margin-b">
                            <div class="block-header bg-primary-dark">
                                <ul class="block-options">
                                    <li>
                                        <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                    </li>
                                </ul>
                            </div>
                            <div class="row block-content" id="content-checkCash">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer id="page-footer" class="content-mini content-mini-full font-s12 bg-gray-lighter clearfix">
                <div class="content content-boxed">
                    <div class="pull-right">
                        
                    </div>
                    <div class="pull-left">
                        <a class="font-w600" href="/admin">Evolutio</a> &copy; <span class="js-year-copy"></span>
                    </div>  
                </div>
                
            </footer>
        </div>

        <link rel="stylesheet" id="css-main" href="{{ assetV('/css/custom.css') }}">
        <script src="{{ asset('/admin-css/assets/js/core/jquery.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/bootstrap.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.slimscroll.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.scrollLock.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.placeholder.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/core/js.cookie.min.js') }}" ></script>
        <script src="{{ asset('/js/vendor/notify.min.js') }}" ></script>
        <script src="{{ asset('/admin-css/assets/js/app.js') }}" ></script>
        <script src="{{ assetV('/admin-css/assets/js/custom.js') }}" ></script>
        <link rel="stylesheet" type="text/css" href="{{ assetV('/admin-css/assets/css/styles.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ assetV('/admin-css/assets/css/mobile.css')}}">
        @yield('scripts')

        <script>

          $(document).ready(function() {
            $('#button-checkCash').click(function(event) {
              event.preventDefault();
              $('#content-checkCash').empty().load('/admin/informes/cajas');
            });
          
              $('#years').change(function () {
              var yearId = $(this).val();
              $.post("{{ route('years.change') }}", { year: yearId }).done(function( data ) {
                      location.reload();
                });
              });
        });
</script>
        <toltip></toltip>
        
    </body>
</html>