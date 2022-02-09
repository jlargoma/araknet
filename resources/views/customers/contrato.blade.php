@extends('layouts.app')

@section('content')
<?php if ($error): ?>
  <p class="alert alert-danger"><?php echo "$error"; ?> </p>
  <?php
else:
  ?>
  
  <?php
  if ($sign):
    ?>
    <h1>CONTRATO CON EL CENTRO - {{$tit}}</h1>
    <div class="text-center mY-1em">
      <a href="{{$url}}" class="btn btn-success">Descargar</a>
      <br/>
      <br/>
      <br/>
    </div>
    <?php
  else:
    ?>
  <table>
    <tr>
      <td><b>NOMBRE APELLIDOS: </b><?php echo $customer->name; ?></td>
    </tr>
    <tr>
      <td><b>DIRECCIÓN: </b><?php echo $customer->address; ?></td>
    </tr>
    <tr>
      <td><b>TELÉFONO: </b><?php echo $customer->phone; ?></td>
    </tr>
    <tr>
      <td><b>E-MAIL: </b><?php echo $customer->email; ?></td>
    </tr>
  </table>
  <table>
    <tr><td></td></tr>
  </table>
  <table>
    <tr>
      <td><b>FECHA INICIO: </b><?php echo $uF_start; ?></td>
      <td><b>FECHA FIN: </b><?php echo $uF_end; ?></td>
    </tr>
  </table>
    <h1>CONTRATO CON EL CENTRO - {{$tit}}</h1>
    <h3>*Este contrato y sus condiciones son personales e intransferibles</h3>
    <div class="textContent">
    <p>Las presentes condiciones regulan la relación entre la mercantil GET FIT VILLAVICIOSA S.L (en adelante Araknet.tech) y sus socios, siempre y cuando no existan supuestos especiales que incluyan condiciones acordadas individualmente.</p>
    <p>El socio conoce y acepta la utilización de cámaras de vigilancia en el gimnasio, con excepción de los vestuarios y los sanitarios.<br/>Solo podrán ser socios los mayores de edad (18 años en adelante), o los menores de edad siempre con autorización del tutor o tutora legal.</p>

    <p>Araknet.tech no se hace responsable por las pérdidas o daños de pertenencias u objetos de valor que se puedan sufrir por descuido o mal manejo de las mismas. A fin de garantizar la seguridad de estos objetos, se deben guardar en las taquillas habilitadas en los vestuarios.</p>
    <p></p>
    <p></p>
    <h2>NORMAS DE USO DE LAS INSTALACIONES Y PROTOCOLO COVID-19</h2>
    <ul>
    <li>Uso obligatorio de mascarilla dentro del centro.</li>
    <li>Desinfección obligatoria de manos antes y después de la práctica del ejercicio, utilizando gel propio o el del dispensador del centro.</li>
    <li>Será obligatorio presentar un certificado de vacunación o documento que acredite haber recibido la pauta completa contra el COVID-19. El personal del centro se encargará de realizar la comprobación y archivarla para que no sea necesario repetir el proceso en sucesivas visitas.</li>
    <li>Los usuarios deben desinfectar el material al finalizar su uso. El centro se encargará de proveer los productos necesarios para ello.</li>
    <li>Debe mantenerse siempre la distancia mínima de seguridad entre usuarios. En caso de que sea posible, es preferible aumentarla.</li>
    <li>El centro abrirá puertas y ventanas cada 30 minutos a fin de ventilar las instalaciones.</li>
    <li>A fin de garantizar la seguridad en las salas de fisioterapia, su personal desinfectará la maquinaria y material utilizado con productos específicos. Además, las salas se ventilarán 5 minutos entre un paciente y otro.</li>
    </ul>
    <div class='saltopagina'></div>
    <h2><u>CONDICIONES GENERALES DEL CONTRATO - {{$tit}}</u></h2>
      <?php echo $text; ?>
    </div>
    <h5 class="formTit">DNI Y FIRMA DEL USUARO (o tutor legal en caso de menores de edad)</h5>
    <form  action="{{ $url }}" method="post" style="width: 325px; margin: 1em auto;"> 
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="sign"  id="sign" value="">

      <h5>Firma</h5>
      <div class="sing-box">
        <canvas width="320" height="300" id="cSign"></canvas>
      </div>
      <input type="text" name="dni" id="dni" class="form-control" placeholder="DNI">
      <p class="alert alert-danger" id="errDNI" style="display:none;">Ingrese su email  (o tutor legal en caso de menores de edad) para continuar </p>
      <br/>
      <button class="btn btn-success" type="button" id="saveSign">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
      </button>
      <button class="btn btn-danger" type="button" id="clearSign">
        <i class="fa fa-trash" aria-hidden="true"></i> Limpiar
      </button>
    </form>
  <?php
  endif;
endif;
?>
@endsection
@section('scripts')
<style>
  .content-box{
    max-width: 840px;
  }
  h1 {
    font-size: 24px;
    padding: 25px 0 0;
    margin: 0;
    font-weight: bold;
    text-decoration: underline;
  }
  h2 {
    font-weight: bold;
    font-size: 19px;
    margin-top: 2em;
  }
  h3 {
    margin-bottom: 14px;
    font-size: 14px;
    text-align: center;
    font-weight: bold;
  }
  ul {
    padding-left: 15px;
  }
  ol{
    padding-left: 20px;
  }
  ul li {
    list-style: disc;
    text-align: left;
    margin: auto;
  }

  li {
    text-align: left;
    margin: auto;
  }

  h5.formTit{
    background-color: #4aa771;
    padding: 8px;
    color: #FFF;
    border-radius: 4px;
  }

  .sing-box {
    border: 1px solid;
    width: 325px;
    padding: 5px;
    margin: 1em auto;
  }
  .alert-danger {
    font-size: 11px;
    padding: 3px;
  }
  div.saltopagina{
      display: none;
   }
   .textContent{
     text-align: left;
     font-size: 13px;
     padding: 35px;
   }
   table{
     width: 100%;
     text-align: left;
     border: 1px solid #000;
   }
   table td{
     padding: 7px;
   }
</style>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    var canvas = document.querySelector("canvas");
    var signaturePad = new SignaturePad(canvas);
    $('#clearSign').on('click', function (e) {
        signaturePad.clear();
    });
    $('#saveSign').on('click', function (e) {
        e.preventDefault();
        if ($('#dni').val().length < 6) {
            $('#errDNI').show();
            return null;
        }
        $('#sign').val(signaturePad.toDataURL()); // save image as PNG
        $(this).closest('form').submit();
    });

    $('#dni').on('keypress', function () {
        $('#errDNI').hide();
    });
});

</script>
@endsection