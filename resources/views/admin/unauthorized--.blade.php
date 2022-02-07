<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="SemiColonWeb" />

	<!-- Stylesheets
	============================================= -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset ('/assets/style.css')}}" type="text/css" />
	
	<link rel="stylesheet" href="{{ asset ('/assets/css/font-icons.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{ asset ('/assets/css/animate.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{ asset ('/assets/css/magnific-popup.css')}}" type="text/css" />

	<link rel="stylesheet" href="{{ asset ('/assets/css/dark.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{ asset ('/assets/css/responsive.css')}}" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- Document Title
	============================================= -->
	<title>404 - No autorizado</title>

</head>

<body class="stretched">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<section id="slider" class="slider-parallax full-screen dark error404-wrap" style="background: url({{asset('/assets/images/parallax/blur1.jpg')}}) center;">
			<div class="slider-parallax-inner">

				<div class="container vertical-middle center clearfix">

					<div class="error404">404</div>

					<div class="heading-block nobottomborder">
						<h4>Ooopps.! Lo siento <b class="text-green"><?php echo $user->name ?></b>, No puedes acceder a esta sección.</h4>
						<span>Prueba a logearte con otro usuario <a href="{{ url('/logout') }}">pincha aquí</a></span>
					</div>

				</div>

			</div>
		</section>

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>

	<!-- External JavaScripts
	============================================= -->
	<script type="text/javascript" src="{{ asset('/assets/js/jquery.js')}}"></script>
	<script type="text/javascript" src="{{ asset('/assets/js/plugins.js')}}"></script>

	<!-- Footer Scripts
	============================================= -->
	<script type="text/javascript" src="{{ asset('/assets/js/functions.js')}}"></script>

</body>
</html>