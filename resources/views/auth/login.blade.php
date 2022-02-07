@extends('layouts.master_login_register')

@section('content')

<section id="content">

    <div class="content-wrap nopadding">

        <div class="section nopadding nomargin" style="width: 100%; height: 100%; position: absolute; left: 0; top: 0; background: url('{{ asset('assets/login.jpg') }}') center center no-repeat; background-size: cover;"></div>

        <div class="container vertical-middle divcenter clearfix">

            <div class="panel panel-default divcenter noradius noborder" style="    margin: 6em auto;max-width: 400px; background-color: rgba(255,255,255,0.93);">
                <div class="panel-body" style="padding: 40px;">
                    <div class="col-xs-12 center">
                        <img src="{{ asset('assets/logo-retina.png') }}" class="img-responsive">
                    </div>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">E-Mail</label>
                            {{ $email = old('email')  }}
                            <?php if (isset($_GET['e']) && $_GET['e'] != '') {
                                $email = base64_decode($_GET['e']);
                            } ?>
<?php if (isset($_GET['p']) && $_GET['p'] != '') {
    $password = base64_decode($_GET['p']);
} else {
    $password = "";
} ?>
                            <div class="col-md-12">
                                <input type="email" class="form-control" name="email" value="{{ $email }}">

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label">Contraseña</label>

                            <div class="col-md-12">
                                <input type="password" class="form-control" name="password" value="{{ $password }}">

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Recuerdame
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-btn fa-sign-in"></i> Entrar
                                </button>
                            </div>
                            <div class="col-xs-12 text-center">
                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Has olvidado tu contraseña?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

                    <!-- <div class="row center dark"><small>Copyrights &copy; All Rights Reserved</small></div> -->

        </div>

    </div>

</section>

<style>
    .container {
        position: absolute;
        width: 100% !important;
        text-align: center;
    }
    label {
        font-size: 16px;
        font-weight: 800;
        margin-top: 1em;
    }
</style>
@endsection