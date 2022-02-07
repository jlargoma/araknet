<hr/>
<form action="{{ url('/admin/citas/bloqueo-horarios') }}" method="post" id="formEdit">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="date_type" value="{{$type}}">
  <input type="hidden" name="blocked" value="1">
  <input type="hidden" name="start" value="{{$date}}">
  <input type="hidden" name="end" value="{{$date}}">
  <input type="hidden" name="hours[]" value="{{$time}}">
  <h2 class="text-center">Bloquear Horario</h2>
  <br/>
  <div class="row mt-1em">
    <div class="col-xs-1 col-md-4  push-20"></div>
    <div class="col-xs-3 col-md-2 push-20">
      <label for="id_coach">{{$date_type_u}}</label>
      <select class="js-select2 form-control" id="id_coach" name="id_coach" style="width: 100%; cursor: pointer" data-placeholder="Seleccione coach.." >
        <option></option>
        <?php foreach ($coachs as $key => $coach): ?>
          <option value="<?php echo $coach->id; ?>" <?php if (isset($id_coach) && $id_coach == $coach->id) echo 'selected' ?>>
            <?php echo $coach->name; ?>
          </option>
        <?php endforeach ?>
      </select>
    </div>
    <div class="col-xs-3 col-md-2 text-center">
      <button class="btn btn-lg btn-info sendForm" style="margin-top: 17px;">
        Bloquear
      </button>
    </div>
    <div class="col-xs-1 col-md-3  push-20"></div>
  </div>
</form>
