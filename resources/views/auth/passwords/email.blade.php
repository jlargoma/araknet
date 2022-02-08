@extends('layouts.app')
@section('content')
<div class="block">
<div class="col-md-12 text-center">
<h2>Enviar correo de recuperación</h2>
</div>
@if (session('status'))

<div class="col-md-12  alert alert-success" role="alert">
  {{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
  @csrf
  <div class="col-md-12 text-center">
    <label for="email" class="">E-Mail</label>

    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

    @error('email')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
  <div class="col-md-12 text-center mt-4" style="margin-top: 1em;">
    <button type="submit" class="btn btn-success">
      <i class="fa fa-btn fa-sign-in"></i> Enviar link
    </button>
  </div>
</form>
</div>
@endsection
