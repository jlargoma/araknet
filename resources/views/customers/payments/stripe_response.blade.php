@extends('layouts.app')

@section('content')
<div class="">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"></div>

        <div class="card-body">
          @if ($success)
          <div class="alert alert-success" role="alert">
            <h3>Pago realizado con éxito</h3>
            <p class="text-center">Muchas Gracias..!</p>
          </div>
          @endif
          @if ($cancel)
          <div class="alert alert-danger" role="alert">
            <h3>Pago cancelado</h3>
            <p class="text-center">Muchas Gracias..!</p>
          </div>
          @endif
          
        </div>
      </div>
    </div>
  </div>
  @endsection