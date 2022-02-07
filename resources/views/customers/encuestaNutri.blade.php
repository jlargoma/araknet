@extends('layouts.app')

@section('content')
<h1>ENCUESTA NUTRICIÓN</h1>
@if (isset($already))
<div class="alert alert-success">
  Su encuesta ya fue enviada
</div>
@else

@if (session('error'))
<div class="alert alert-danger">
  {{ session('error') }}
</div>
@endif
@if (session('success'))
<div class="alert alert-success">
  {{ session('success') }}
</div>
@else
<form  action="/encuesta-nutricion" method="post" style="margin: 1em auto;"> 
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="_code" value="<?php echo $code; ?>">
  <input type="hidden" name="_control" value="<?php echo $control; ?>">
  <div class="text-left">
    @include('customers.blocks.encNutri')
  </div>
  <button class="btn btn-success">
    <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
  </button>
</form>

@endif
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

  @media screen {
    .paging h1{
      padding-top: 2em;
    }
    .printBreak,.block-logo{
      display: none;
    }
  }
</style>
<script>

  function formateafecha(fecha)
  {
      var fecha = fecha.replaceAll('-', '');
      fecha = fecha.replaceAll('D', '');
      fecha = fecha.replaceAll('M', '');
      fecha = fecha.replaceAll('Y', '');
      var long = fecha.length;
      var newDate = '';
      if (long > 9)
      long = 9;
      for (var i = 0; i < long; i++) {
          if (i == 2 || i == 4)
        newDate += '-';
          newDate += fecha[i];
      }
      return newDate;

  }
</script>
@endsection