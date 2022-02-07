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
				<h2 class="text-center">Hola,</h2>
				<p style="color: black;text-align: center;">
					Hemos recibido satisfactoriamente un pago de nuestra oferta de<strong> BONO ENTRENAMIENTO PERSONAL</strong> el día <strong><?php echo $data['date']->format('d-M-Y')?> </strong> el usuario que ha comprado esta oferta es: <strong><a href="mailto:<?php echo $data['email_customer']?>"><?php echo $data['email_customer']?></a></strong>
				</p>
				<p style="color: black; text-align: center; ">
					Este es código que hemos generado:<br>
					<span style="font-size: 72px; font-weight: 800"><?php echo $code; ?></span><br>
					Esta actualmente inactivo, no olvides activar el codigo y cargar el bono para este cliente.
				</p>
			</div>
		</div>
	</body>
</html>