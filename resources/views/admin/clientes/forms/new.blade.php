@extends('layouts.popup')
@section('content')
<h2 class="text-center font-w300 mb-1em">Nuevo 
  <span class="font-w600">Cliente</span>
</h2>
<div style="max-width: 480px; margin: 1em auto;">
  <form class="form-horizontal" action=""  id="form-new" method="post">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="role" value="user">
    <div class="row formLine">
      <div class="col-md-7">
        <input class="field" type="text" id="name" name="name" required value="">
        <label>Nombre</label>
      </div>
      <div class="col-md-5">
        <input class="field" type="text" id="dni" name="dni" value="">
        <label>DNI</label>
      </div>
      <div class="col-md-7">
        <input type="email" id="email" class="field" name="email" required value="">
        <label>E-mail</label>
      </div>
      <div class="col-md-5">
        <input class="field" type="number" id="phone" name="phone" required maxlength="9" value="">
        <label for="phone">Teléfono</label>
      </div>
      <div class="col-md-12">
        <input type="text" id="address" class="field" name="address" value="">
        <label>Dirección</label>
      </div>
      <div class="col-md-6">
        <input type="text" id="population" class="field" name="population" value="">
        <label>Población</label>
      </div>
      <div class="col-md-6">
        <input type="text" id="province" class="field" name="province" value="">
        <label>Provincia</label>
      </div>
      <div class="col-md-12">
        <input type="text" id="iban" class="field" name="iban" value="">
        <label>Cuenta Corriente Banco</label>
      </div>
    </div>
    <div class="text-center">
      <button class="btn btn-success" type="submit">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
      </button>
    </div>
</div>
</form>
</div>
@endsection