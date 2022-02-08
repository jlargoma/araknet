<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<script src="//code.jquery.com/jquery.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container">
			<div class="col-xs-12">
				<h3 class="text-center">Hola, <?php echo $solicitud->nombre ?></h3>
				<p class="text-center">
					Aquí tienes los accesos a tu cuenta de usuario de Araknet HTS:<br><br>
					Usuario: <?php echo $solicitud->email ?><br>
					Password: <?php echo $solicitud->email ?><br><br>

					Puedes acceder mediante la siguiente URL: <a href="http://Araknet.tech/login">Araknet zona clientes</a>
				</p>
				<p class="text-center" style="margin-bottom: 5px;">
					Por favor no olvides cambiar tu contraseña despues de acceder.
				</p>
				<p class="text-center" style="margin-bottom: 5px;">
					Muchas gracias por elegir Araknet HTS.
				</p>
			</div>
		</div>
	</body>
</html>