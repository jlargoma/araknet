@include('emails.head')

Hola! <?php echo $customer->name ?><br><br><br>

<bold>Te enviamos el enlace para aceptar y firmar el documento de <b><?php echo $tit; ?></b></bold><br>
<br/>
<div style="display: block; text-align: center">
  <a href="{{$link}}" title="Firmar" class="btn btn-success">Firmar</a>
</div>
<br/><br/><br/><br/>
<small>En caso de que el botón no funcione, 
  copie y pegue el siguiente link en su navegador de preferencia: <br/>{{$link}}</small>

@include('emails.footer')