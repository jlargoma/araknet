@extends('layouts.admin-master')

@section('title') Configuración TXT emails  @endsection
@section('headerTitle')  Configuración TXT emails  @endsection
@section('content')
<div class="content content-full bg-white">
  <div class="row mb-1em">
    <div class="col-md-8"><h2>CONFIGURACIONES - TEXTOS</h2></div>
    <div class="col-md-4"><button type="button" data-toggle="modal" data-target="#modal_variables"><i class="fa fa-eye"></i> Variables</button></div>
  </div>
  
   
  <div class="box-items">
    <div class="lstKeys">
    <ul class="list-options">
      @foreach($lstKeys as $k=>$v)
      <li><a href="{{route('settings.msgs',$k)}}" <?php if ($k == $key) echo 'class="active"'; ?>>{{$v}}</a></li>
      @endforeach
    </ul>
    </div>
     <div class=" text-center  mt-1em">
    <a href="/test-text/{{$key}}" title="ver pagina" target="_black">Ver Página >> </a><br>
  </div>
    <div class="form">
       <form method="POST" action="{{route('settings.msgs.upd')}}">
      <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="key" id="key" value="{{$key}}">
      <div class="fc-1 fbox">
        <div class="text-center">
          @if($ckeditor)
            <textarea class="ckeditor" name="{{$key}}" id="{{$key}}" rows="20" cols="80">{{$content}}</textarea>
             @else
            <textarea class="form-control" name="{{$key}}" id="{{$key}}" rows="20" cols="80">{{$content}}</textarea>
            @endif
        </div>
      </div>
      @if(in_array($key,$kWSP))
        <br/>
        <strong>Negrita:</strong> Para escribir texto en <b>negrita</b>, coloca un asterisco antes y después del texto:
        <br/>*texto*  (si es el final de una linea, agregar un espacio luego)
        @endif
      <div class="col-xs-12 text-center">
        <button class="btn btn-primary m-t-20">Guardar</button>
      
      </div>
    </form>
    </div>
  </div>
</div>

<div class="modal fade slide-up in" id="modal_variables" tabindex="-1" role="dialog" aria-hidden="true" style=" z-index: 9999;">
    <div class="modal-dialog modal-xd">
      <div class="modal-content p-3">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
          <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
        </button>
        <div style="padding: 2em 2em 21px;">
        <h3>Variables</h3>
        @foreach($varsTxt as $k=>$v)
        <b>{{$v}}:</b> {{$k}}<br/>
        @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script src="{{ asset('/admin-css/assets/js/plugins/ckeditor/ckeditor.js') }}"></script>

<style>
  .list-options{
    margin: 0;
    padding: 0;
  }
  .list-options li{
    list-style: none;
    margin-bottom: 1em;
    display: inline-block;
  }
  .list-options li a{
    padding: 7px;
    border: solid 1px #949494;
    box-shadow: 1px 1px 1px #000;
    margin: 2px;
  }
  .list-options li a.active {
    background-color: #4ec37a;
    font-weight: bold;
    color: #FFF;
}
</style>
@endsection