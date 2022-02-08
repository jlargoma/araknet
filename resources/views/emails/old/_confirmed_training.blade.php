<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<!-- Latest compiled and minified CSS & JS -->
		<link rel="stylesheet" href="{{ asset('/admin-css/assets/css/bootstrap.min.css') }}">
		<script src="{{ asset('/admin-css/assets/js/core/jquery.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/bootstrap.min.js') }}"></script>
	</head>
	<body>
		<div class="container">
			<div class="col-xs-12">
				<h2 class="text-center">Hola, <?php echo $apuntado->user->name ?></h2>
				<p style="color: black">
					Te confirmamos que has solicitado plaza para la clase de  <strong><?php echo $apuntado->schedule->classes->name; ?></strong> el día <?php echo date('d-m-Y', strtotime($apuntado->date_assistance)) ?>
				</p>
				<h3 style="color: black ;margin-bottom: 5px;">
					Muchas gracias por elegir Araknet HTS.
				</h3>
				<p style="color: black">
					Estaremos esperandote
				</p>
			</div>
		</div>
	</body>
</html>