<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<title>Formulario de contacto</title>
		<!-- Latest compiled and minified CSS & JS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<script src="//code.jquery.com/jquery.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container">
			<div class="col-xs-12">
				<h3 class="text-center">Hola, ADMINISTRADOR</h3>
				<p style="font-size: 16px;">
					<span><b><?php echo ucfirst($usuario['name']) ?></b></span> nos ha dejado un mensaje el dia <?php echo $usuario['date'] ?> desde el formulario de contacto<br><br>
					Su email es: <b><?php echo $usuario['email'] ?></b><br>
					Y teléfono de contacto es: <b><?php echo $usuario['phone'] ?></b><br>
					Con el siguiente mensaje.
					<blockquote>
						<?php echo $usuario['message'] ?>
					</blockquote>
				</p>
			</div>
		</div>
	</body>
</html>