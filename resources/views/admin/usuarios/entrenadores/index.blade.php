@extends('layouts.admin-master')

@section('title') Entrenadores - Araknet HTS @endsection

@section('externalScripts')
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
@endsection

@section('headerButtoms')
<li class="text-center">
  <button id="newUser" class="btn btn-sm btn-success font-s16 font-w300" data-toggle="modal" data-target="#modal-newUser" style="padding: 10px 15px;">
    <i class="fa fa-plus"></i> Entrenador
  </button>
</li>
@endsection


@section('content')

<div class="content content-full bg-white">
  <h3 class="text-center">
    Listado de Entrenadores
  </h3>
  <div class="row">
    <div class="col-xs-12 push-20 not-padding">
      <button class="btn btn-success" id="btn-sendEmail" type="button">
        <i class="fa fa-envelope"></i> Enviar emails acceso
      </button>
      <a class="btn btn-info <?php if ($type == '') echo 'active' ?>" href="/admin/entrenadores">
        Todos
      </a>
      <a class="btn btn-info <?php if ($type == 'activos') echo 'active' ?>" href="/admin/entrenadores/activos">
        Entrenadores Activos
      </a>
      <a class="btn btn-info  <?php if ($type == 'desactivados') echo 'active' ?>" href="/admin/entrenadores/desactivados">
        Entrenadores No Activos
      </a>
    </div>
    </div>
    <div class="table-responsive t-entrenadores">
      <table class="table table-bordered table-striped table-header-bg js-table-checkable">
        <thead>
          <tr>
            <th class="text-left nowrap static">
              <label class="css-input css-checkbox css-checkbox-primary remove-margin-t remove-margin-b">
                <input type="checkbox" id="check-all" name="check-all"><span></span>
              </label>
              Nombre</th>
            <th class="first-col"></th>
            <th class="text-center hidden-xs hidden-sm">Activo</th>
            <th class="text-center">Tel<span class="hidden-xs hidden-sm">éfono</span></th>
            <th class="text-center">Tipo</th>
            <th class="text-center">Totales</th>
            <?php foreach ($months as $i): ?>
              <th class="text-center"><?php echo $i ?></th>
            <?php endforeach ?>
          </tr>
        </thead>
        <tbody>
          <?php $totalMonthCoach = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; ?>
          <?php $totalYear = 0; ?>
          <?php foreach ($users as $key => $user): ?>
            <tr>
              <td class="text-left nowrap static"> 
                <label class="css-input css-checkbox css-checkbox-primary">
                  <input type="checkbox" class="user-checker" name="email[<?php echo $key; ?>]" value="<?php echo $user->id ?>"><span></span>
                </label>
                <a class="btn-user" data-toggle="modal" data-target="#modal-popout" data-idUser="<?php echo $user->id; ?>" type="button" data-toggle="tooltip" title="" data-type="user" data-original-title="Editar Entrenador">
                  <b><?php echo ($user->name) ? $user->name : '--'; ?></b>
                </a>
              </td>
              <td class="first-col"></td>
              <td class="text-center hidden-xs hidden-sm"> 
                <?php if ($user->status == 1): ?>
                  <a href="{{ url('/admin/usuarios/disable')}}/<?php echo $user->id ?>" class="btn btn-xs btn-success" type="button" data-toggle="tooltip" title="" data-original-title="Desactivar entrenador"><i class="fa fa-circle"></i></a>
                <?php else: ?>
                  <a href="{{ url('/admin/usuarios/activate')}}/<?php echo $user->id ?>" class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Activar entrenador"><i class="fa fa-circle"></i></a>
                <?php endif ?>
              </td>
              <td class="text-center"> 
                <?php echo $user->telefono; ?>
              </td>
              <td class="text-center"> 

                <?php
                switch ($user->role) {
                  case 'admin';
                  case 'administrativo';
                    echo 'Admin';
                    break;
                  case 'teach';
                    echo 'Entrenador';
                    break;
                  case 'nutri';
                    echo 'Nutricionista';
                    break;
                  case 'fisio';
                    echo 'Fisioterapeuta';
                    break;
                }
                ?>
              </td>
              <td class="text-center">
                <?php
                $totalLiquidationByCoach = 0;
                if (isset($aLiqTotal[$user->id])):
                  $totalLiquidationByCoach = $aLiqTotal[$user->id];
                  $totalYear += $totalLiquidationByCoach;
                endif;
                ?>
                <?php echo mformat($totalLiquidationByCoach); ?>
              </td>
              <?php
              foreach ($months as $k => $v):
                $aux = isset($aLiq[$user->id]) ? $aLiq[$user->id] : null;
                $liq = ($aux && isset($aLiq[$user->id][$k])) ? $aLiq[$user->id][$k] : 0;
                $totalMonthCoach[$k] += $liq;
                ?>
                <td class="text-center">{{mformat($liq)}}</td>
                <?php
              endforeach;
              ?>
            </tr>
          <?php endforeach ?>
          <tr>
            <td class="tdbg" colspan="4">
              TOTAL ANUALES
            </td>
            <td class="text-center hidden-xs hidden-sm tdbg"> </td>
            <td class="tdbg">
              <b><?php echo mformat($totalYear); ?>€</b>
            </td>
            <?php for ($i = 1; $i <= 12; $i++) : ?>
              <td class="tdbg">
                <b><?php echo moneda($totalMonthCoach[$i]); ?></b>
              </td>
            <?php endfor; ?>

          </tr>
        </tbody>
      </table>
    </div>
  </div>
<div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
        </div>
        <iframe id="content"></iframe>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-newUser" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
        </div>
        <div class="row block-content" id="content-new-user">

        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal  fade in" id="modalHorarios" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button" class="reload"><i class="si si-close "> Cerrar y refrescar</i></button>
            </li>
          </ul>
        </div>
        <div><iframe id="ifrModal"></iframe></div>
      </div>
    </div>
  </div>
</div>

@endsection


@section('scripts')
<style type="text/css">
  #main-container{
    padding-top: 10px!important
  }
  .modal-dialog {
    width: 90%;
  }

  .table-vtop td{
    vertical-align: top;
  }
  .turnos small{
    margin-left: 1em;
    display: block;
    font-size: 12px;
  }
  .t-entrenadores td .liquidation{
    width: 99%;
    padding: 0px !important;
    text-align: center;
  }
  .t-entrenadores th.input,
  .t-entrenadores td.input {
    padding: 8px 0px !important;
    max-width: 70px !important;
    text-align: center;
  }
  iframe{
    width: 100%;
    overflow: hidden;
  }
  .tdbg{
    color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 16px;
    white-space: nowrap;
  }
.static {
    width: 160px !important;
    height: 42px;
    padding: 7px 6px !important;
}
th.static {
    height: 56px;
}
.table-responsive.t-entrenadores .first-col{
  padding-left: 143px !important;
}
@media(min-width:991px) {
  .table-responsive.t-entrenadores .first-col {
    padding: 0px !important;
    max-width: 0px !important;
    min-width: 0px !important;
  }
}
</style>
<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function () {

  $('#newUser').click(function (e) {
    e.preventDefault();
    $.get('/admin/usuarios/new', function (data) {
      $('#content-new-user').empty().html(data);
    });
  });

  $('.btn-user').click(function (e) {
    e.preventDefault();
    var id = $(this).attr('data-idUser');
    $('#content').attr('src', '/admin/actualizarEntrenador/' + id);
    // alert(id);
//    $.get('/admin/actualizarEntrenador/' + id, function (data) {
//      $('#content').empty().html(data);
//    });
  });

  $('#check-all').click(function () {
    if ($('#check-all').is(':checked')) {
      $('.user-checker').prop('checked', true); // Checks it
    } else {
      $('.user-checker').prop('checked', false); // Checks it
    }
  });

  $('#btn-sendEmail').click(function (event) {
    event.preventDefault();
    var totalChecked = 0;
    $(".user-checker").each(function (index) {
      var isChecked = $(this).is(':checked');
      if (isChecked) {
        totalChecked = totalChecked + 1;
      }
    });
    if (totalChecked > 0) {
      $(".user-checker").each(function (index) {
        if ($(this).is(':checked')) {
          var id = $(this).val();
          $.get('/admin/sendEmail/trainer/' + id).done(function (data) {
            alert(data);
          });

        }
      });

    } else {
      alert('Seleccione al menos un entrenador');
    }

  });

  $('.only-numbers').keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                            (e.keyCode >= 35 && e.keyCode <= 40)) {
              // let it happen, don't do anything
              return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
              e.preventDefault();
            }
          });

  $('.liquidation').change(function (event) {
    var id_liquidation = $(this).attr('data-idLiquidation');
    var id_coach = $(this).attr('data-idCoach');
    var date_liquidation = $(this).attr('data-dateLiquidation');
    var importe = $(this).val();
    $.get('/admin/entrenadores/liquidacion-entrenador/', {id_coach: id_coach, importe: importe, id_liquidation: id_liquidation, date_liquidation: date_liquidation}).done(function (data) {
      location.reload();
    });
  });

  $('#content').on('click', '.btn-horarios', function (e) {
    e.preventDefault();
    console.log('asdad');
    $('#content').empty().load('/admin/horariosEntrenador/' + $(this).data('id'));
  });
});
</script>
@endsection