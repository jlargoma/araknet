@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$name}} <h3 style="float:right;">{{moneda($amount,true,2)}}</h3></div>

                <div class="card-body">
                    <div class="alert alert-<?php echo ($response[0] == 'OK') ? 'success' : ''; ?>" role="alert">
                        {{ $response[1] }}
                    </div>
                    <div class="text-center">
                        <a href="{{$receipt_url}}" target="_blank" title="Descargar pago">Descargar pago</a>
                    </div>
                    
                </div>
                <div class="card-footer">
                    <small>En caso de que el link no funcione, copie y pege el siguiente link en su navegador de preferencia:<br>{{$receipt_url}}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



<div class="content">

</div>