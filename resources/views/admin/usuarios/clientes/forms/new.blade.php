@extends('layouts.popup')
@section('content')
<h2 class="text-center font-w300 mb-1em">Nuevo 
  <span class="font-w600">Cliente</span>
</h2>
<div style="max-width: 480px; margin: 1em auto;">
  <form class="form-horizontal" action=""  id="form-new" method="post">
    <div class="mt-1em">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="role" value="user">
      <div class="form-material my-2">
        <input class="form-control" type="text" id="name" name="name" required>
        <label for="name">Nombre</label>
      </div>
      <div class="form-material my-2">
        <input class="form-control" type="email" id="email" name="email" required>
        <label for="email">E-mail</label>
      </div>
      <div class="form-material my-2">
        <input class="form-control" type="number" id="telefono" name="telefono" maxlength="9" required>
        <label for="telefono">Teléfono</label>
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