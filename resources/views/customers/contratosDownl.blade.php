@extends('layouts.pdf')

@section('title') Contratos @endsection

@section('styles')
<style type="text/css">

  .img-logo{
    width: 290px;
    margin-bottom: 50px;
    margin-top: -50px;
  }
  .contratoBox{
    max-width: 860px;
    margin: 50px auto;
    font-size: 12px;
    padding: 5px 10px 15px 40px;
    background-color: #FFF;
  }
  .rateCalendar .item {
    width: 200px;
  }
  .sing-box {
    width: 80px;
    padding: 5px;
    margin: 0px auto;
  }
  .sing-box p{
    width: 100%;
    margin: 0;
    text-align: center;
  }
  .sing-box img{
    max-width: 100%;
  }
ul {
    padding-left: 15px;
  }
  li {
    padding-bottom: 10px;
  }
.contratoBox h1 {
    font-size: 24px;
    padding: 25px 0 0;
    margin: 0;
    font-weight: bold;
    text-decoration: underline;
    text-align: center;
}
.contratoBox h2 {
    font-size: 16px;
    padding: 15px 0 0;
    margin: 0;
    font-weight: bold;
    text-align: center;
}
.contratoBox h3 {
  margin-bottom: 14px;
    font-size: 14px;
    text-align: center;
    font-weight: bold;
}
.contratoBox .body,
.contratoBox .body p{
  font-size: 11px;
  /*text-indent: 5px;*/
  text-align: justify;
}
.sessionType{
  display: none;
}
.month {
    height: 20px;
    font-weight: bold;
}
div.saltopagina{
   display:block;
   page-break-before:always;
}

   .textContent{
     text-align: left;
     font-size: 13px;
     padding: 10px 35px;
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
@endsection
@section('content')

<div class="contratoBox">
  <img src="https://desarrollo.Araknet.tech/img/logocontrato.png" class="img-logo">
  <div class="body">
    <table>
    <tr>
      <td><b>NOMBRE APELLIDOS: </b><?php echo $user->name; ?></td>
    </tr>
    <tr>
      <td><b>DIRECCIÓN: </b><?php echo $user->address; ?></td>
    </tr>
    <tr>
      <td><b>TELÉFONO: </b><?php echo $user->telefono; ?></td>
    </tr>
    <tr>
      <td><b>E-MAIL: </b><?php echo $user->email; ?></td>
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
    <h2><u>CONDICIONES GENERALES DEL CONTRATO - {{$tit}}</u></h2>
    <?php echo $text; ?>
  </div>
  <div class="sing-box">
  <img src="data:image/png;base64,{{$signFile}}" >
  <p>{{$name}}</p>
  <p>{{$dni}}</p>
  </div>
</div>
@endsection