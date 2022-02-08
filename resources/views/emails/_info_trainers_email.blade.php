<?php
$tit = 'Acceso a Control de asistencia ';
?>
@include('emails.head')
Hola! <?php echo $user->name ?><br><br><br>

<p>Te adjuntamos tu usuario y contraseña:</p>
<b>Usuario:</b> <?php echo $user->email; ?><br>
<b>Password:</b> <?php echo $user->email; ?><br><br>

<b> 
    Para entrar en tu sección, al final del mes se te enviara la liquidación , no olvides registrar los clientes que vienen a clase y asignarte como monitor de esa clase.
</b><br><br>

<p>puedes acceder mediante esta url: <a href="http://Araknet.tech/login">Control de asistencia</a>
</p>
@include('emails.footer')
