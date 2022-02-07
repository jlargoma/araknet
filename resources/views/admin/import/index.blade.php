@extends('layouts.admin-master')

@section('title') Importar registros desde CSV @endsection


@section('content')

<section class="content content-full bg-white">
  <h1>Importar Registros desde CSV</h1>
  @include('flash-message')
  <div class="row">
    <div class="col-md-6">
      <form action="/admin/importar/clientes" method="post" enctype="multipart/form-data" id="formfileToUpload">
        {{ csrf_field() }}
        <div class="input-image">
          <div class="upload-btn-wrapper">
            <button class="btn-Upload">Buscar Archivo Ventas</button>
            <input type="file" name="fileToUpload" id="fileToUpload" class="file-upload-default" ><br/>
            <small>Buscar e importar listado de ventas</small>
          </div>
        </div>
      </form>
    </div>

    <div class="col-md-6">
      <form action="/admin/importar/instructor" method="post" enctype="multipart/form-data" id="formfileInstructor">
        {{ csrf_field() }}
        <div class="input-image">
          <div class="upload-btn-wrapper">
            <button class="btn-Upload">Buscar Archivo instructor</button>
            <input type="file" name="fileInstructor" id="fileInstructor" class="file-upload-default" ><br/>
            <small>Buscar e importar listado de Salarios</small>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

@endsection
@section('scripts')
<script type="text/javascript">
  $(document).ready(function () {
    $("#fileToUpload").change(function () {
      $('#formfileToUpload').submit();
    });
    $("#fileInstructor").change(function () {
      $('#formfileInstructor').submit();
    });
  });
</script>
@endsection