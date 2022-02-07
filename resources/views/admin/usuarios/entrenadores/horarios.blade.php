@extends('layouts.popup')
@section('content')
<form action="/admin/horariosEntrenador" method="post" class="form-horarios">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <div style="max-width: 800px; margin: 1em auto;">
    <div class="form-material" style="max-width: 704px;margin: 3px auto 19px;">
      <label for="role">Usuario</label>
      <select class="form-control" id="uid" name="uid">
        <option value="">Usuario</option>
        <?php
        if ($aUsers):
          foreach ($aUsers as $k => $v):
            $sel = ($id == $k) ? 'selected' : '';
            echo "<option value='$k' $sel>$v</option>";
          endforeach;
        endif;
        ?>
      </select>
    </div>
    <div class="table-responsive">
      <table class="table">
        <tr>
          <th class="static">Día</th>
          <th class="first-col"></th>
          <th colspan="2" class="th2">1er Rango Horario</th>
          <th colspan="2" class="th2">2do Rango Horario</th>
        </tr>
        <?php
        if ($days):
          foreach ($days as $k => $v):
            ?>
            <tr>
              <th class="static">{{$v}}</th>
              <td class="first-col"></td>
              <td><input type="number" min="8" max="22" name="d_{{$k}}-0" value="{{$times[$k][0]}}"></td>
              <td><input type="number" min="8" max="22" name="d_{{$k}}-1" value="{{$times[$k][1]}}"></td>
              <td><input type="number" min="8" max="22" name="d_{{$k}}-2" value="{{$times[$k][2]}}"></td>
              <td><input type="number" min="8" max="22" name="d_{{$k}}-3" value="{{$times[$k][3]}}"></td>
            </tr>
            <?php
          endforeach;
        endif;
        ?>
      </table>
    </div>
    <div class="text-center mt-1">
      <button class="btn btn-success">Guardar</button>
    </div>
  </div>
</form>
@endsection
@section('scripts')
<script type="text/javascript">
  $(document).ready(function () {
    $('#uid').on('change', function () {
      location.href = '/admin/horariosEntrenador/' + $(this).val();
    });

  });
</script>
<style>
  .form-horarios input[type="number"] {
    background: transparent;
    border: 1px solid;
    text-align: center;
  }
</style>
@endsection