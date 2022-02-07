<?php
$tit = 'Bienvenido a Evolutio';
?>
@include('emails.head')

Hola! <?php echo $user->name ?><br><br><br>

<bold>Bienvenido a Evolutio, esperamos que te pongas muuuy en forma y que cumplas todos los objetivos que te has marcado.</bold><br>

<div>
	<p><bold>Tienes a tu disposición los siguientes servicios:</bold></p>
	<ul>
		<li>Servicio Exclusivo de Personal Trainer</li> 
		<li>Entrenamiento funcional  en grupos reducidos </li>
		<li>Especialista en perdida de peso</li>
		<li>Cardio Boxing</li>
		<li>Nutrición</li>
		<li>Fisioterapia</li>		
	</ul>
</div>
@include('emails.footer')