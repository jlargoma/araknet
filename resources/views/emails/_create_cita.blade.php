<?php
$tit = 'Su cita en Evolutio';
?>
@include('emails.head')

Hola! <?php echo $user->name ?><br><br><br>

<p>
    Tienes una cita en nuestro centro, 
    el día <b>{{$dayCita}}</b> de <b>{{$monthCita}}</b>,
    a las <b>{{$hourCita}}</b>, para su servicio de <b>{{$service}}</b>.
</p>
<p>
    <b>{{$coach}}</b> le va a estar esperando.
</p>
 
@include('emails.footer')