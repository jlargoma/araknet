@extends('layouts.app')

@section('title') {{$tit}} @endsection

@section('content')
<style type="text/css">

  .contratoBox{
    max-width: 1024px !important;
    margin: 0 auto;
    font-size: 16px;
    padding: 4px 7px 45px 16px;
    background-color: #FFF;
  }
  .rateCalendar .item {
    width: 200px;
  }
  .signs img {
    width: 330px;
    max-width: 90%;
    margin: 2em auto;
  }

  .sing-box {
    border: 1px solid;
    width: 275px;
    padding: 5px;
    margin: 15px auto;
  }
  .contratoBox h1 {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    margin-left: -1em;
  }
  .contratoBox h3 {
    margin-bottom: 14px;
    font-size: 12px;

  }
  .contratoBox .body,
  .contratoBox .body p{
    font-size: 13px;
    /*text-indent: 5px;*/
    text-align: justify;
  }
  @media only screen and (max-width: 540px) {
    .contratoBox{
      padding: 30px 15px;
    }
    .contratoBox h1{
      margin-left: 0px;
      line-height: 1.5;
    }
    .contratoBox h2 {
      font-size: 15px;
      line-height: 1;
      letter-spacing: 1px;
      margin: 1em 4px;
    }
    .rateCalendar .item {
      width: 48%;
    }
    .sessionType {
      overflow: auto;
      margin: 1em auto;
    }
    .sessionType table td {
      padding: 5px 10px !important;
      font-size: 13px;
      text-align: center;
    }
    .rateCost table th {
      font-size: 10px;
      min-width: 46px !important;
    }
    .rateCost table td {
      font-size: 10px !important;
    }
    .rateCost {
      width: 100%;
      overflow: auto;
    }
  }
</style>


<div class="contratoBox">
  <h1>{{$tit}}</h1>
  @if($sign)
  <div class="text-center mY-1em">
    <p class="alert alert-success">En contrato ya se encuetra firmado</p>
  <a href="{{$url}}" title="Ver contrato">Ver Contrato</a>
  </div>
  @else
    <div class="body">
      <?php echo $text; ?>
    </div>
    <div class="row" style="margin-top:2em;">
      <div class="col-md-6">
        <h5 class="text-center">ARAKNET GLOBAL GROUP SL</h5>
      </div>
      <div class="col-md-6 text-center">
        <h5 >EL TIITULAR DE LA VIVIENDA</h5>
      </div>
    </div>
    <div class="row signs">
      <div class="col-md-6">
        <img src="data:image/png;base64,<?php echo base64_encode($signAdmin) ?>" >
      </div>
      <div class="col-md-6 text-center">
        <form  action="{{ $url }}" method="post" style="width: 325px; margin: 1em auto;"> 
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <input type="hidden" name="sign"  id="sign" value="">
          <div class="sing-box">
            <canvas width="270" height="200" id="cSign"></canvas>
          </div>
          <button class="btn btn-success" type="button" id="saveSign">
            <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
          </button>
          <button class="btn btn-danger" type="button" id="clearSign">
            <i class="fa fa-trash" aria-hidden="true"></i> Limpiar
          </button>
        </form>
      </div>
    </div>
  @endif

</div>
@endsection

@section('scripts')
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
        $('#sign').val(signaturePad.toDataURL()); // save image as PNG
        $(this).closest('form').submit();
    });
});

</script>
@endsection