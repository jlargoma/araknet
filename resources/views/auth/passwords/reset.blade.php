@extends('layouts.app')
@section('content')
<div class="col-md-12 text-center">
  <h2>Restablecer contraseña</h2>
</div>
<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
  {{ csrf_field() }}

  <input type="hidden" name="token" value="{{ $token }}">

  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    <label for="email" class="control-label">E-Mail</label>

    <div class="">
      <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}">

      @if ($errors->has('email'))
      <span class="help-block">
        <strong>{{ $errors->first('email') }}</strong>
      </span>
      @endif
    </div>
  </div>

  <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    <label for="password" class="control-label">Password</label>

    <div class="">
      <input id="password" type="password" class="form-control" name="password">

      @if ($errors->has('password'))
      <span class="help-block">
        <strong>{{ $errors->first('password') }}</strong>
      </span>
      @endif
    </div>
  </div>

  <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
    <label for="password-confirm" class="control-label">Repetir Password</label>
    <div class="">
      <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

      @if ($errors->has('password_confirmation'))
      <span class="help-block">
        <strong>{{ $errors->first('password_confirmation') }}</strong>
      </span>
      @endif
    </div>
  </div>

  <div class="form-group text-center" style="margin-top: 1em;">
    <button type="submit" class="btn btn-success">
      <i class="fa fa-btn fa-refresh"></i> Restablecer contraseña
    </button>
  </div>
</form>

@endsection
