
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">

<div class="row push-30">
  <div class="block bg-white">
      <h3 class="text-center">
        Formulario para añadir Servicios
      </h3>
    <form class="form-horizontal" action="{{ url('/admin/tarifas/create') }}" method="post">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="max_pax" value="100">
      <input type="hidden" name="status" value="100">
      <input type="hidden" name="status" value="1">
      <div class="row  mx-1em">
        <div class="col-md-4 mx-1em">
          <div class="form-material">
            <input class="form-control" type="text" id="name" name="name" required>
            <label for="nombre">Nombre del Servicio</label>
          </div>
        </div>
        <div class="col-md-4 mx-1em">
          <div class="form-material">
            <select class="js-select2 form-control" id="type" name="type" style="width: 100%;" data-placeholder="Tipo de tarifa..." required>
              <option></option>
              <?php foreach ($typesRate as $typeRate): ?>
                <option value="<?php echo $typeRate->id ?>">
                  <?php echo $typeRate->name ?>
                </option>
              <?php endforeach ?>
            </select>
            <label for="name">Familia</label>
          </div>
        </div>
        <div class="col-md-4 mx-1em">
          <div class="form-material">
            <select class="form-control" id="subfamily" name="subfamily">
              <option></option>
              <?php foreach ($subtype as $k=>$v): ?>
                <option value="<?php echo $k ?>">
                  <?php echo $v ?>
                </option>
              <?php endforeach ?>
            </select>
            <label for="name">Sub-Familia</label>
          </div>
        </div>
      </div>
      <div class="row  mx-1em">
        <div class="col-md-6">
          <div class="form-material">
            <select class="form-control" id="mode" name="mode" required>
              <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?php echo $i ?>">
                  <?php echo $i ?>
                  <?php if ($i > 2): ?>
                    Meses
                  <?php else: ?>
                    Mes
                  <?php endif ?>
                </option>
              <?php endfor ?>
            </select>
            <label for="mode">PERIODICIDAD</label>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="form-material">
            <input class="form-control only-numbres" type="text" id="price" name="price" required>
            <label for="nombre">Precio</label>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-material">
            <label for="nombre">Nº SESION / SEM</label>
            <input class="form-control only-numbres" type="text" id="max_pax" name="max_pax" required>
          </div>
        </div>
      </div>
      <div class="row text-center">
        <button class="btn btn-success" type="submit">
          <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>



<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script>
jQuery(function () {
  App.initHelpers(['datepicker', 'select2', 'summernote', 'ckeditor']);
});
$(document).ready(function () {
  $(".only-numbers").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl/cmd+A
                    (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: Ctrl/cmd+C
                            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                            // Allow: Ctrl/cmd+X
                                    (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                                    // Allow: home, end, left, right
                                            (e.keyCode >= 35 && e.keyCode <= 39)) {
                              // let it happen, don't do anything
                              return;
                            }
                            // Ensure that it is a number and stop the keypress
                            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                              e.preventDefault();
                            }
                          });
                });

</script>