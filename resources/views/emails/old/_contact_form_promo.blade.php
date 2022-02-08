<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<title>Formulario de contacto Araknet HTS</title>
		<!-- Latest compiled and minified CSS & JS -->
		<link rel="stylesheet" href="{{ asset('/admin-css/assets/css/bootstrap.min.css') }}">
		<script src="{{ asset('/admin-css/assets/js/core/jquery.min.js') }}"></script>
		<script src="{{ asset('/admin-css/assets/js/core/bootstrap.min.js') }}"></script>
	</head>
	<body>
		<div class="container">
			<div class="col-xs-12">
				<h3>Hola</h3>
				<p style="font-size: 18px;">
					Hemos recibido una solicitud de <b><?php echo ucfirst($promo); ?></b> a de: <span style="font-weight: 800;"><?php echo ucfirst($data['name']) ?></span> nos ha dejado un mensaje el dia <?php echo date('d-m-Y H:i:s') ?>  puedes contactar con el de las siguientes maneras <br><br>
					Su email es: <b><?php echo $data['email'] ?></b><br>
					Y teléfono de contacto es: <b><?php echo $data['phone'] ?></b>
				</p>
			</div>
		</div>
	</body>
</html>