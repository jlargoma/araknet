<div class="col-xs-12">
  <h2 class="text-center">Bloqueo de Fecha</h2>
  <div class="row">
    <form action="{{ url('/admin/citas/create') }}" method="post" id="formEdit">
      <input type="hidden" name="idDate" value="{{$id}}">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="date_type" value="<?php echo $date_type; ?>">
      <input type="hidden" name="blocked" value="1">
      <div class="row">
        <div class="col-xs-1 col-md-3  push-20"></div>
        <div class="col-xs-3 col-md-2  push-20">
          <label for="date">Fecha</label>
          <input class="js-datepicker form-control" value="{{$date}}" type="text" id="date" name="date" placeholder="Fecha y hora..." style="cursor: pointer;" data-date-format="dd-mm-yyyy"/>
        </div>
        <div class="col-xs-3 col-md-2 not-padding  push-20">
          <label for="id_user">hora</label>
          <select class="form-control" id="hour" name="hour" style="width: 100%;" data-placeholder="hora" required >
            <?php for ($i = 8; $i <= 22; $i++) : ?>
              <?php
              if ($i < 10) {
                $hour = "0" . $i;
              } else {
                $hour = $i;
              }
              ?>
              <option value="<?php echo $hour ?>" <?php if ($time == $i) echo 'selected'; ?>>
                <?php echo $hour ?>: 00
              </option>
            <?php endfor; ?>

          </select>
        </div>
        <div class="col-xs-3 col-md-2 push-20">
          <label for="id_coach"><?php echo $date_type_u; ?></label>
          <select class="js-select2 form-control" id="id_coach" name="id_coach" style="width: 100%; cursor: pointer" data-placeholder="Seleccione coach.." >
            <option></option>
            <?php foreach ($coachs as $key => $coach): ?>
              <option value="<?php echo $coach->id; ?>" <?php if (isset($id_coach) && $id_coach == $coach->id) echo 'selected' ?>>
                <?php echo $coach->name; ?>
              </option>
            <?php endforeach ?>
          </select>

        </div>
        <div class="col-xs-2 col-md-3  push-20"></div>
      </div>
    </form>
    <div class=" col-xs-12 form-group push-20">
      <div class="col-xs-12 text-center">
        <button class="btn btn-lg btn-success sendForm" data-id="formEdit"  type="button" >
          Guardar
        </button>
        @if($id>0)   
        <button class="btn btn-lg btn-danger btnDeleteCita" type="button">
          Eliminar
        </button>
        @endif
      </div>
    </div>
    <hr/>
  </div>
</div>