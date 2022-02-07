@extends('layouts.app')

@section('content')
<h1>VALORACIÓN DE SALUD Y PREPARACIÓN AL ENTRENAMIENTO</h1>
@if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif
  @if (session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
  @endif
<div class="text-left">
  @include('customers.blocks.valoracion')
</div>

@if($valora['valora_sign'])
<img src="/public-sign/{{$valora['valora_sign']}}" ><br/>
    <div class="fieldSing">DNI:{{$valora['valora_dni']}}</div>

 <a class="btn btn-success" href="{{$valora['url_dwnl']}}" target="_blank" >
    <i class="fa fa-file" aria-hidden="true"></i> Imprimir / Descargar
  </a>
@else
<form  action="{{ $url }}" method="post" style="width: 325px; margin: 1em auto;"> 
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="sign"  id="sign" value="">
  <h5>Firma</h5>
  <div class="sing-box">
    <canvas width="320" height="300" id="cSign"></canvas>
  </div>
  <div>
  <label><strong>DNI</strong></label>
  <input type="text" id="dni" name="dni" required="">
  </div>
  <button class="btn btn-success" type="button" id="saveSign">
    <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
  </button>
  <button class="btn btn-danger" type="button" id="clearSign">
    <i class="fa fa-trash" aria-hidden="true"></i> Limpiar
  </button>
</form>
@endif
@endsection
@section('scripts')
<style>
  .container {
    font-size: 14px;
  }
  .content-box{
    max-width: 840px;
  }
  h1 {
    font-size: 24px;
    background-color: #f7f7f7;
    padding: 15px 0;
    margin: -39px 0 10px 0px;
  }
  h2 {
    font-weight: bold;
    font-size: 19px;
    margin-top: 2em;
  }
  h3 {
    font-size: 18px;
    font-weight: bold;
    margin-top: 2em;
    border-bottom: 2px solid;
  }
  ul {
    padding-left: 40px;
  }
  li {
    list-style: disc;
    text-align: left;
    margin: auto;
  }
     
  .sing-box {
    border: 1px solid;
    width: 325px;
    padding: 5px;
    margin: 1em auto;
  }
        @media screen {
        .paging h1{
          padding-top: 2em;
        }
        .printBreak,.block-logo{display: none;}
      }
</style>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  var canvas = document.querySelector("canvas");
  var signaturePad = new SignaturePad(canvas);
  $('#clearSign').on('click', function (e) {
    signaturePad.clear();
  });
  $('#saveSign').on('click', function (e) {
    e.preventDefault();
    if ($('#dni').val() == ''){
      alert('Debe ingresar su DNI para continuar');
      return null;
    }
    $('#sign').val(signaturePad.toDataURL()); // save image as PNG
    $(this).closest('form').submit();
  });
});

</script>
@endsection