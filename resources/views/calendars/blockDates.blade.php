@extends('layouts.popup')
@section('content')
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ assetV('css/custom.css') }}">
<div class="col-xs-12">
  <h2 class="text-center">Bloqueo de Fechas</h2>
  <div class="row">
    <form action="{{ url('/admin/citas/bloqueo-horarios') }}" method="post" id="formEdit">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="date_type" value="{{$type}}">
      <input type="hidden" name="blocked" value="1">
      <div class="row">
        <div class="col-xs-3 col-md-2 push-20">
          <label for="user_id">Usuario</label>
          <select class="js-select2 form-control" id="user_id" name="user_id" style="width: 100%; cursor: pointer" data-placeholder="Seleccione coach.." >
            <option></option>
            <?php foreach ($coachs as $key => $coach): ?>
              <option value="<?php echo $key; ?>">
                <?php echo $coach; ?>
              </option>
            <?php endforeach ?>
          </select>

        </div>
        <div class="col-xs-3 col-md-2  push-20">
          <label for="date">Fecha Inicio</label>
          <input class="js-datepicker form-control" type="text" id="start" name="start" placeholder="Fecha y hora..." style="cursor: pointer;" data-date-format="dd-mm-yyyy"/>
        </div>
        <div class="col-xs-3 col-md-2  push-20">
          <label for="date">Fecha Fin</label>
          <input class="js-datepicker form-control" type="text" id="end" name="end" placeholder="Fecha y hora..." style="cursor: pointer;" data-date-format="dd-mm-yyyy"/>
        </div>
        <div class="col-xs-12 ck-box push-20">
          <h3>Hora</h3>
          <?php for ($i = 8; $i <= 22; $i++) : ?>
            <?php
            if ($i < 10) {
              $hour = "0" . $i;
            } else {
              $hour = $i;
            }
            ?>
          <div id="ck-button">
            <label>
               <input type="checkbox" name="hours[]" value="<?php echo $hour ?>"><span><?php echo $hour ?>: 00</span>
            </label>
         </div>
           
          <?php endfor; ?>
        </div>
        <div class="col-xs-12 text-center">
          <button class="btn btn-lg btn-success sendForm">
            Crear
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/moment.min.js')}}"></script>
<script type="text/javascript">
jQuery(function () {
    App.initHelpers(['datepicker', 'select2']);
    });

</script>
<style>

.ck-box {
      position: relative;
    clear: both;
    overflow: auto;
}
#ck-button {
    margin:4px;
    background-color:#EFEFEF;
    border-radius:4px;
    border:1px solid #D0D0D0;
    overflow:auto;
    float:left;
}

#ck-button label {
    float:left;
    width:4.0em;
}

#ck-button label span {
    text-align:center;
    padding:3px 0px;
    display:block;
    border-radius:4px;
}

#ck-button label input {
    position:absolute;
    top:-20px;
}

#ck-button input:hover + span {
    background-color:#efE0E0;
}

#ck-button input:checked + span {
    background-color:#911;
    color:#fff;
}

#ck-button input:checked:hover + span {
    background-color:#c11;
    color:#fff;
}
</style>
@endsection