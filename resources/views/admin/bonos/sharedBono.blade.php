<h2 class="text-center">Asignar bono desde otro usuario</h2>
<form action="{{ url('/admin/bonos/sharedBono') }}" method="post" id="formEdit">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <input type="hidden" name="uID" value="<?php echo $uID; ?>">

  <div class="row">
    <div class="col-xs-12 col-md-4 push-20">
      <label for="id_user" id="tit_user">Cliente</label>
        <select class="form-control" id="id_userBono" name="id_userBono" style="width: 100%; cursor: pointer" data-placeholder="Seleccione usuario.."  >
          <option></option>
          <?php foreach ($users as $key => $user): ?>

            <option value="<?php echo $user->id; ?>" <?php if (isset($id_user) && $id_user == $user->id) echo 'selected' ?>>
              <?php echo $user->name; ?>
            </option>
          <?php endforeach ?>
        </select>

    </div>
    <div class="col-xs-12 col-md-6 push-20">
      <label for="id_type_rate">Bonos</label>
      <div id="lstBonos"></div>
    </div>
    <div class="col-xs-12 col-md-2 push-20">
      <button class="btn btn-lg btn-success" >
        Traspasar
      </button>
    </div>

  </div>

</form>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script>
  $(document).ready(function() {
  $("#id_userBono").select2({
     dropdownParent: $("#modal-shareBonos")
  });
  $('#id_userBono').on('change',function(){
    $('#lstBonos').load('/admin/bonos/sharedBono-get/'+$(this).val()+'/{{$serv}}');
    console.log($(this).val());
  })
});
    
</script>