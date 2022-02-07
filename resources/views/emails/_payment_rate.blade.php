<?php
$tit = 'Nueva cita en Evolutio';
?>
@include('emails.head')

Hola! <?php echo $user->name ?><br><br><br>

<p style="color: black">
    Te adjuntamos el comprobante del pago que acabas de realizar en <strong> Evolutio</strong>
</p>

<p style="color: black;font-size: 18px;">
    - Nombre: <?php echo $user->name ?><br><br>
    - Tarifa: <?php echo $rate->name ?><br><br>
    - Fecha : <?php echo $date; ?><br><br>
    - Importe: <?php echo round($importe) ?> €<br><br>
    - Método: <?php 
      switch ($typePayment){
        case 'cash':
          echo "Metalico";
          break;
        case 'bono':
          echo "Bono";
          break;
        default:
          echo  "Tarjeta";
          break;
      }
      ?>
    <br><br>

</p>
<h5 style="color: black ;margin-bottom: 5px;">
    Muchas gracias por tu confianza en nosotros!! Tú compromiso es el nuestro
</h5>
@include('emails.footer')