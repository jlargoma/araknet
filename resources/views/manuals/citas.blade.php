@extends('layouts.admin-master')

@section('title') Manual - Citas  @endsection
@section('headerTitle')  Manual - Citas  @endsection
@section('content')
<div class="content content-full bg-white">
  <h2>Citas FISIO / NUTRICIÓN</h2>
  <div class="box-items">
    <h3>Calendario</h3>
    <p></p>
    <img src="/img/manuales/citas1.png" alt="alt"/>
    <ol>
      <li>Cambiar a la vista de Listado de Citas</li>
      <li>Calendario del FISIO / NUTRICIONISTA</li>
      <li>
        <b>Horarios:</b>Abre la ventana con los horarios del personal<br/>
        <b>Bloqueos:</b>Abre la ventana para bloquear fechas de un personal en particular (por ejemplo, por vacaciones)<br/>
      </li>
      <li>Filtrar citas por SERVICIOS</li>
      <li>Més seleccionado del calendario</li>
      <li>Buscar un Cliente por nombre</li>
      <li><b style="font-size: 26px;">½</b> indica que la cita no comienza en la hora absoluta (por ejemplo: la cita es a las 11:45hrs )</li>
      <li>Bloqueo de Fecha: el Personal no se encuentra ese Día / Hora</li>
      <li><span class="no-pay"></span> Indica que la Cita no se encuentra abonada</li>
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
   span.no-pay {
    background-color: red;
    height: 8px;
    width: 8px;
    display: inline-block;
    border-radius: 50%;
    margin-right: 3px;
  }
</style>
@endsection