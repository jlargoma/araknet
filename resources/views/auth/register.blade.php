@extends('layouts.app')

@section('content')

    <section id="content">

        <div class="content-wrap nopadding">

            <div class="section nopadding nomargin" style="width: 100%; height: 100%; position: absolute; left: 0; top: 0; background: url('assets/images/parallax/home/5.jpg') center center no-repeat; background-size: cover;"></div>

            <div class="section nobg full-screen nopadding nomargin">
                <div class="container vertical-middle divcenter clearfix">

                    <div class="row center">
                        <a href="/"><img src="canvas/images/logo-dark.png" alt="Canvas Logo"></a>
                    </div>

                    <div class="panel panel-default divcenter noradius noborder" style="max-width: 400px; background-color: rgba(255,255,255,0.93);">
                        <div class="panel-body" style="padding: 40px;">
                            <form class="nobottommargin" method="post" action="{{ url('/register') }}">
                                {!! csrf_field() !!}

                                <h3>Registra un usuario</h3>

                                <div class="col_full">
                                    <label for="name" >Nombre</label>
                                    <input type="text" class="form-control not-dark" id="name" name="name" value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col_full">
                                    <label for="phone" >Teléfono</label>
                                    <input type="text" class="form-control not-dark" id="phone" name="phone" value="{{ old('phone') }}">
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col_full">
                                    <label for="name" >Tarifa</label>
                                    <select class="form-control" name="id_tax">
                                        <option value="1">Dia suelto</option>
                                        <option value="2">Tarifa 2 clases/semana</option>
                                        <option value="3">Ilimitada</option>
                                    </select>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="col_full">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control not-dark" />
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col_full">
                                    <label for="password">Password:</label>
                                    <input type="password" name="password" id="password" class="form-control not-dark" />
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col_full">
                                    <label for="password_confirmation">Confirm Password:</label>
                                    <input type="password"  name="password_confirmation" id="password_confirmation" class="form-control not-dark" />
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col_full nobottommargin">
                                    <button class="button button-3d button-black nomargin">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row center dark"><small>Copyrights &copy; All Rights Reserved</small></div>

                </div>
            </div>

        </div>

    </section>

@endsection