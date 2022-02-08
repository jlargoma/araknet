<?php
$tit = $type.' en Araknet';
?>
@include('emails.head')

Hola! <?php echo $user->name ?><br><br><br>

<p style="color: black">
    Te adjuntamos el enlace para la compra de <?php echo $bono_name ?> en <strong> Araknet</strong>
</p>

<p style="color: black;font-size: 18px;">
    - Nombre : <?php echo $user->name ?><br><br>
    - Bono : <?php echo $bono_name ?><br><br>
    - Importe: <?php echo moneda($importe,true,2) ?><br><br>
</p>
<p>
    Para realizar el pago, haga click en el enlace o copie y pegue el mismo en su navegador de preferencia<br/>
    <a href="{{$pStripe}}" title="link pago Stripe">{{$pStripe}}</a>
</p>
<h5 style="color: black ;margin-bottom: 5px;">
    Muchas gracias por tu confianza en nosotros!! Tú compromiso es el nuestro
</h5>
@include('emails.footer')