<?php
$tit = 'Su cita en Araknet';
?>
@include('emails.head')

Hola! <?php echo $user->name ?><br><br>

<p style="color: black">
    Tienes una cita en nuestro centro de <b><?php echo $rate->name ?></b> en <strong> Araknet</strong>
</p>

<p style="color: black;font-size: 18px;">
    - Nombre: <?php echo $user->name ?><br>
    <?php 
    
    if ($obj->date_type == 'nutri')
      echo '- Nutricionista: '.$oCoach->name.'<br>';    
    if ($obj->date_type == 'fisio')
      echo '- Fisioterapeuta: '.$oCoach->name.'<br>';  
    ?>
    - Servicio: <?php echo $rate->name ?><br>
    - Fecha: <?php echo $day; ?><br>
    - Hora: <?php echo $hour; ?><br>
</p>
@if($urlEntr)
<p style="text-align: center;background-color: #e0f5e9;padding: 9px;color: #000;font-size: 16px;font-weight: bold;margin: 32px 0;">
    Por favor, complete la siguente encuesta antes de su cita<br/>
    <a href="{{$urlEntr}}" title="Encuesta nutrición">{{$urlEntr}}</a>
</p>
@endif
<h5 style="color: black ;margin-bottom: 5px;">
    Muchas gracias por tu confianza en nosotros!! Tú compromiso es el nuestro
</h5>
@include('emails.footer')