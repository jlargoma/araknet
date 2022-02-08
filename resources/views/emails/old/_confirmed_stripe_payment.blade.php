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
				<h2 class="text-center" style=" text-align: center;">Hola</h2>
				<p style="color: black;text-align: center;">
					Te confirmamos que has comprado satisfactoriamente nuestra oferta de<strong> BONO ENTRENAMIENTO PERSONAL 10 SESIONES</strong> el día <?php echo $data['date']->format('d-M-Y')?>
				</p>
				<p style="color: black; text-align: center; ">
					Este es tu código:<br>
					<span style="font-size: 116px; font-weight: 800"><?php echo $code; ?></span><br>
					Llamanos al <a href="tel:911723217">911723217</a> y activa este código.
				</p>
				<h3 style="color: black ;margin-bottom: 5px;text-align: center;">
					Muchas gracias por elegirnos Araknet Centro de entrenamiento y salud.
				</h3>
				<p style="color: black;text-align: center;">
					Estamos esperandote
				</p>
			</div>
		</div>
	</body>
</html>