<?php
$tit = 'Su cita en Araknet';
?>
@include('emails.head')

Hola! <?php echo $user->name ?><br><br>

<p style="color: black">
  Necesitamos que nos completes una breve encuesta para tu cita de Nutrición en <strong> Araknet</strong>
</p>
<p>
Para ello puede hacer click en el siguiente enlace ó cópielo y péguelo en su navegador de confianza
<a href="{{$urlEntr}}" title="Encuesta nutrición">{{$urlEntr}}</a>
</p>
<h5 style="color: black ;margin-bottom: 5px;">
    Muchas gracias por tu confianza en nosotros!! Tú compromiso es el nuestro
</h5>
@include('emails.footer')