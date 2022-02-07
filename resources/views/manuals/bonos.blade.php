@extends('layouts.admin-master')

@section('title') Manual - Bonos  @endsection
@section('headerTitle')  Manual - Bonos  @endsection
@section('content')
<div class="content content-full bg-white">
  <h2>Compra de Bonos</h2>
  <div  id="manual-clientes"></div>
  <div class="box-items">
    <h3>Desde la página de Clientes</h3>
    <p>Cuando un cliente desea comprar un Bono, la primer opción es buscarlo en la página de <b>CLIENTES</b> y utilizar el método rápido para compra de bonos</p>
    <img src="/img/manuales/usrBonos1.png" alt="alt"/>
    <ol>
      <li>Hacer Click en el botón con el signo <b>$</b></li>
      <li>Se desplegará 2 nuevos botones: <b>$ Asignar Servicios</b> y <b><i class="fa fa-plus-circle"></i> Asignar Bonos</b> (nos centramos en éste último)</li>
      <li>Al hacer click en el botón <b><i class="fa fa-plus-circle"></i> Asignar Bonos</b> se abrirá una nueva ventana con <a href="#manual-compra">las opciones de compra</a></li>
    </ol>
  </div>
  <div  id="manual-ficha-usuario"></div>
  <div class="box-items">
    <h3>Desde la ventana de <b>INFORMES</b> del Clientes </h3>
    <p>Cuando se accede a la ventana del cliente haciendo click en el nombre del cliente ó haciendo click en el botón <b>FICHA USUARIO</b> dentro de las ventanas de <a href="#manual-cita"><b>CITAS</b></a></p>
    <img src="/img/manuales/usrBonos2.png" alt="alt"/>
    <ol>
      <li>Pestaña <b>Bonos</b>: ésta contiene la información actual de los bonos del cliente</li>
      <li>Botón comprar: abre la ventana para la compra de Bonos</li>
      <li>Listado de Bonos actuales del cliente: Al hacer click sobre uno de ellos, mostrará la información asociada a ese bono</li>
      <li>
        Información asociada a un bono: Aquí lista el LOG del bono (copras, usos, etc) y la opción para editar el balance total del bono.<br/>
        <b>Modificar balance total:</b> Para correcciones de la cantidad de bonos. Se debe ingresar un <b>Motivo</b> y la <b>Cantidad</b> total que le quedaría al cliente
      </li>
    </ol>
  </div>
  <div  id="manual-compra"></div>
  <div class="box-items">
    <h3>Compra de Bonos</h3>
    <p>Ésta ventana se abre desde el botón <b><i class="fa fa-plus-circle"></i> Asignar Bonos</b> en la página del listado de <a href="#manual-clientes"><b>CLIENTES</b></a> <br/>
      ó desde el botón <b>Botón comprar</b> en <a href="#manual-ficha-usuario">la ventana de INFORMES</a> del Clientes</p>
    <img src="/img/manuales/usrBonos3.png" alt="alt"/>
    <ol>
     <li>Listado de Bonos disponibles</li>
     <li>Compra directa por tarjeta, efectivo ó banco (éste último es cuando se copró por otro medio) </li>
     <li><b>Enviar Mail:</b> envía un correo al cliente con el bono seleccionado y el enlace para la compra del mismo con tarjeta</li>
     <li><b>Enviar WSP:</b> Abre un Whatsapp en el nro de móvil del cliente con el bono seleccionado y el enlace para la compra del mismo con tarjeta</li>
     <li><b>Copia Link Stripe:</b> Copia el link de pago para el bono seleccionado, para enviar el mismo al cliente por un sistema externo  (Ctrl+v ó botón derecho del mause / pegar)</li>
    </ol>
  </div>
  <div  id="manual-cita"></div>
  <div class="box-items">
    <h3>Desde la página de CITA</h3>
    <p>Desde la ventana de la cita del cliente, se puede acceder al pago de la misma de múltiples maneras:<br/>
    Tarjeta, Efectivo, Banco, Con Bonos y a través de link de pago que se pueden enviar por: Correo, Whatsapp y terceros (con la copia del link de Stripe)<br/>
    <b>NOTA:</b> Puede adquirir nuevos bonos desde el botón <a href="#manual-ficha-usuario"><b>Ficha Usuario</b></a>
    </p>
    <img src="/img/manuales/usrBonos4.png" alt="alt"/>
    <ol>
      <li>Opción de pago con Bonos: si el cliente dispone de bonos, puede abonarse la cita con el uso de los mismos</li>
      <li>Listado de Bonos disponibles para abonar la CITA (Corresponde a la familia del Servicio asociado a la CITA)</li>
      <li><a href="#manual-compartir-bono"><b>Bono compartido:</b></a> ésta opción es para utilizar los bonos de otro CLIENTE.<br/> 
        Por ejemplo: Si un familiar posee bonos, se le puede transferir un al cliente actual para abonar la CITA</li>
      <li><b>Enviar Mail:</b> envía un correo al cliente con el link de pago de la cita ( a abonar con tarjeta )</li>
      <li><b>Enviar WSP:</b> abre el Whatsaap con el número de móvil del cliente y el link de pago de la cita ( a abonar con tarjeta )</li>
      <li><b>Copia Link Stripe:</b> Copia el link de pago para la cita, para enviar el mismo al cliente por un sistema externo (Ctrl+v ó botón derecho del mause / pegar)</li>
    </ol>
  </div>
  <div  id="manual-compartir-bono"></div>
  <div class="box-items">
    <h3>Compartir Bonos</h3>
    <p>Permite que un familiar ó conocido del CLIENTE, le ceda un bono para el pago de la CITA</p>
    <img src="/img/manuales/usrBonos5.png" alt="alt"/>
    <ol>
      <li>Cliente del cual se obtendrá el bono para el pago</li>
      <li>Listado de bonos disponibles del Cliente donante</li>
    </ol>
  </div>


</div>
@endsection

@section('scripts')
<style>
  .box-items{
    margin: 3em 0px 3em 15px;
    font-size: 18px;
  }

  .box-items h3 {
    background-color: #0b9a48;
    padding: 11px 5px;
    color: #FFF;
  }
  .box-items img{
    max-width: 100%;
    margin: 1em;
  }
</style>
@endsection