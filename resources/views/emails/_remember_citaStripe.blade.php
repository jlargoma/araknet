<?php
$tit = 'Su cita en Araknet';
?>
@include('emails.head')

Hola! <?php echo $customer->name ?><br><br>

<p style="color: black">
  Le recordamos su cita en nuestro centro de <b><?php echo $rate->name ?></b> en <strong> Araknet</strong>
</p>

<p style="color: black;font-size: 18px;">
    - Nombre: <?php echo $customer->name ?><br>
    <?php 
    
    if ($obj->date_type == 'nutri')
      echo '- Nutricionista: '.$oCoach->name.'<br>';    
    if ($obj->date_type == 'fisio')
      echo '- Fisioterapeuta: '.$oCoach->name.'<br>';  
    if ($obj->date_type == 'pt')
      echo '- Entrenador: '.$oCoach->name.'<br>';  
    ?>
    - Servicio: <?php echo $rate->name ?><br>
    - Fecha: <?php echo $day; ?><br>
    - Hora: <?php echo $hour; ?><br>
    @if($importe)
    - Importe: <?php echo moneda($importe,true,2) ?><br>
    @endif
</p>
<h5 style="color: black ;margin-bottom: 5px;">
  Muchas gracias por tu confianza en nosotros!! Tú compromiso es el nuestro
</h5>
@include('emails.footer')