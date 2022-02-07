<?php
use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') Clientes - Evolutio HTS @endsection

@section('externalScripts')
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<style type="text/css">
  #DataTables_Table_0_wrapper .row > .col-sm-6:first-child{
    display: none;
  }
  #DataTables_Table_0_wrapper .row > .col-sm-6 #DataTables_Table_0_filter{
    text-align: left!important;
  }
  input[type="search"], ::-webkit-input-placeholder, :-moz-placeholder, :-ms-input-placeholder{
    color: black;
  }
  .header-navbar-fixed #main-container{
    padding-top: 0; 
  }

</style>
@endsection

@section('headerButtoms')
<li class="text-center">
  <button id="newUser" class="btn btn-sm btn-success font-s16 font-w300" data-toggle="modal" data-target="#modal-newUser" style="padding: 10px 15px;">
    <i class="fa fa-plus"></i> Cliente
  </button>
</li>
@endsection


@section('content')
<?php
$url = Request::url();
$domain = substr(strrchr($url, "/"), 1);
$today = $date->copy();
?>
<div class="content content-full bg-gray-lighter">
  <div class="row ">
    <div class="col-md-5 col-xs-12 push-20">
      <div class="col-md-2 col-xs-4">
        <a href="{{url('/admin/clientes')}}?status=all">
          <button class="btn btn-md 
                  @if($status == 'all') btn-success @else btn-primary @endif
                  " style="width: 100%;">
            Todos
          </button>
        </a>
      </div>
      <div class="col-md-2 col-xs-4">
        <a href="{{url('/admin/clientes')}}?status=1">
          <button class="btn btn-md @if($status == 1) btn-success @else btn-primary @endif" style="width: 100%;">
            Activos
          </button>
        </a>
      </div>
      <div class="col-md-2 col-xs-4">
        <a href="{{url('/admin/clientes')}}?status=0">
          <button class="btn btn-md @if($status != 1) btn-success @else btn-primary @endif" style="width: 100%;">
            Inactivos
          </button>
        </a>
      </div>
      <div class="col-md-3 col-xs-4">
        <a href="{{url('/admin/clientes-export')}}">
          <button class="btn btn-md" style="width: 100%;">
            EXPORT EXCEL
          </button>
        </a>
      </div>
    </div> 
    <div class="col-xs-12 col-md-1 text-center center">
      <button id="addIngreso" class="btn btn-success btn-mobile-lg" data-toggle="modal" data-target="#modal-ingreso"
              style="padding: 7px 20px;">
        TPV
      </button>
    </div>
    <div class="col-md-2 col-xs-12 hidden-sm hidden-xs">
      <a href="{{url('/admin/citas')}}">
        <button class="btn btn-md btn-success font-s16 font-w300">
          CITAS FISIO/NUTRI
        </button>
      </a>
    </div>
    <div class="col-md-2 col-xs-12 push-20">
      <div class="col-xs-12 col-md-12 pull-right">
        <select id="date" class="form-control">
<?php 
$yearActive = getYearActive();
$fecha = $date->copy()->startOfYear(); 
?>
<?php for ($i = 1; $i <= 12; $i++): ?>
  <?php if ($date->copy()->format('n') == $i) {
    $selected = "selected";
  } else {
    $selected = "";
  } ?>
            <option value="<?php echo $fecha->format('m'); ?>" <?php echo $selected ?>>
  <?php echo ucfirst($fecha->formatLocalized('%B')) . ' ' . $yearActive; ?>
            </option>
  <?php $fecha->addMonth(); ?>
<?php endfor; ?>
        </select>
      </div>
    </div>
    <div class="col-xs-12">
      <h2 class="text-center font-s36 font-w300">
<?php echo strtoupper('Listado de Clientes') ?>
      </h2>
    </div>
    <div class="col-xs-12">
      <div class="col-md-8 col-md-offset-2 col-xs-12">
        @if (session('status'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <strong>{{ session('status') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif
      </div>
      <div class="col-md-2 col-xs-12">
        @if ($total_pending > 0)
        <a href="#" style="float: right; margin: 5px 10px 5px 0; ">
          <button id="cuotas-pendientes" class="btn btn-danger right">
            Cuotas Pendientes {{ $total_pending }} €
          </button>
        </a>
        @endif
      </div>

    </div>

    <div class="col-xs-12 push-20">
      <div class="row">
        <div class="loading text-center" style="padding: 150px 0;">
          <i class="fa fa-5x fa-circle-o-notch fa-spin"></i><br><span class="font-s36">CARGANDO</span>
        </div>
        <div class="col-md-12" id="containerTableResult" style="display: none;">
          <table class="table table-striped js-dataTable-full-clients table-header-bg">
            <thead>
              <tr>
                <th class="text-center hidden-xs hidden-sm sorting_disabled"></th>
                <th class="text-center">Nombre<br></th>
                <th class="text-center sorting_disabled">Tel<span class="hidden-xs hidden-sm">éfono</span><br></th>
                <th class="text-center hidden-xs hidden-sm sorting_disabled">Tarifas<br></th>

<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<?php $month = $date->copy()->startOfMonth()->subMonth(); ?>

                <th class="text-center hidden-xs hidden-sm sorting_disabled">
<?php echo $month->formatLocalized('%B'); ?>(<?php echo \App\User::getUserActiveByMonth($month->copy()->format('Y-m-d')) ?>)<br>
                  <label class="text-danger">
                    (<?php echo $payments[0] ?>)
                  </label>
                </th>
                    <?php $month->addMonth(); ?>
                <th class="text-center hidden-xs hidden-sm sorting_disabled">
<?php echo $month->formatLocalized('%B'); ?>(<?php echo \App\User::getUserActiveByMonth($month->copy()->format('Y-m-d')) ?>)<br>
                  <label class="text-danger">(<?php echo $payments[1] ?>)</label>
                </th>
<?php $month->addMonth(); ?>
                <th class="text-center hidden-xs hidden-sm sorting_disabled">
                  <?php echo $month->formatLocalized('%B'); ?>(<?php echo \App\User::getUserActiveByMonth($month->copy()->format('Y-m-d')) ?>)<br>
                  <label class="text-danger">(<?php echo $payments[2] ?>)</label>
                </th>
                <th class="text-center sorting_desc" id="estado-payment">Estado</th>
                <th class="text-center sorting_disabled">Acciones</th>
              </tr>
            </thead>
            <tbody>
                  <?php foreach ($users as $key => $user): ?>
                <tr>
                  <td class="text-center hidden-xs hidden-sm" style="width: 60px!important">
                    <label class="css-input switch switch-sm switch-success">
                    <?php $checked = ($user->status == 1) ? 'checked' : ''; ?>
                      <input type="checkbox" class="switchStatus" data-id="<?php echo $user->id ?>" <?php echo $checked ?>><span></span>
                    </label>
                  </td>
                  <td class="text-justify"> 
                    <a  class="btn-user" data-toggle="modal" data-target="#modal-popout-inform" data-idUser="<?php echo $user->id; ?>" type="button" data-toggle="tooltip" title="" data-type="user" data-original-title="Editar user"  style="cursor: pointer"><b><?php echo $user->name; ?></b></a>
  <?php $dateUser = $user->getDatesByUser(); ?>
                    <?php if (isset($dateUser['FISIOTERAPIA']) && count($dateUser['FISIOTERAPIA']['dates']) > 0): ?>
                      <?php if ($dateUser['FISIOTERAPIA']['chargued'] > 0): ?>
                                                                <span class="label label-primary font-w600" style="border-radius: 35px;margin-top: 5px;top: -20px; right:15px;position: absolute;padding: 5px 7px; border: 1px solid white;">
                        <?php echo $dateUser['FISIOTERAPIA']['chargued']; ?>
                                                                </span>
                      <?php endif ?>
                      <?php if ($dateUser['FISIOTERAPIA']['unchargued'] > 0): ?>
                                                                <span class="label label-danger font-w600" style="border-radius: 35px;margin-top: 5px;top: -20px;position: absolute;padding: 5px 7px; border: 1px solid white;">
                        <?php echo $dateUser['FISIOTERAPIA']['unchargued']; ?>
                                                                </span>
    <?php endif ?>
  <?php endif ?>
                                    </div>
                                    
                                    <div id="date-nutri" class="btn btn-md btn-success" type="button" data-toggle="modal" data-target="#modal-date" style="margin-right: 20px" data-title="NUTRI" data-idUser="<?php echo $user->id; ?>">
                                            <i class="fa fa-apple" aria-hidden="true"></i>
  <?php if (isset($dateUser['NUTRICION']) && count($dateUser['NUTRICION']['dates']) > 0): ?>
    <?php if ($dateUser['NUTRICION']['chargued'] > 0): ?>
                                                                <span class="label label-success font-w600" style="border-radius: 35px;margin-top: 5px;top: -20px; right:15px;position: absolute;padding: 5px 7px; border: 1px solid white;">
      <?php echo $dateUser['NUTRICION']['chargued']; ?>
                                                                </span>
    <?php endif ?>
    <?php if ($dateUser['NUTRICION']['unchargued'] > 0): ?>
                                                                <span class="label label-danger font-w600" style="border-radius: 35px;margin-top: 5px;top: -20px;position: absolute;padding: 5px 7px; border: 1px solid white;">
                        <?php echo $dateUser['NUTRICION']['chargued']; ?>
                                                                </span>
                    <?php endif ?>
                  <?php endif ?>
                                    </div>
                            </div>
                    </div>
                    -->
                  </td>
                  <td class="text-center">
                    <span class="hidden-xs hidden-sm"><?php echo $user->telefono; ?></span>
                    <span class="hidden-lg hidden-md">
                      <a href="tel:<?php echo $user->telefono; ?>">
                        <i class="fa fa-phone"></i>
                      </a>
                    </span>
                  </td>
                  <td class="text-center hidden-xs hidden-sm">
                    <?php echo $user->getRatesByMonth($date->copy()->format('Y'), $date->copy()->format('m'), $user->id); ?>               			
                  </td>
                    <?php $pendiente = 0; ?>	
                    <?php $month = $date->copy()->startOfMonth()->subMonth(); ?>
                    <?php for ($i = 1; $i <= 3; $i++) : ?>
                      <?php if ($i == 2) {
                        $actual = "selected-month";
                      } else {
                        $actual = "";
                      } ?>
                    <td class="text-center hidden-xs hidden-sm <?php echo $actual ?>" style="line-height: 3"> 

                      <?php
                      $arrayRates = DB::table('users_rates')
                              ->distinct('id_rate')
                              ->select()
                              ->where('id_user', $user->id)
                              ->whereMonth('created_at', '=', $month->copy()->format('m'))
                              ->whereYear('created_at', '=', $month->copy()->format('Y'))
                              ->orderBy('created_at')
                              ->groupBy('id_rate')
                              ->get();
                      ?>
                        <?php foreach ($arrayRates as $key => $rate): ?>
                        <?php $rateUser = \App\UserRates::find($rate->id) ?>
                        <?php
                        $charges = \App\Charges::where('id_user', $user->id)
                                ->where('id_rate', $rateUser->id_rate)
                                ->whereMonth('date_payment', '=', $month->format('m'))
                                ->whereYear('date_payment', '=', $month->format('Y'))
                                ->orderBy('date_payment', 'DESC')
                                ->get();
                        ?>
                        <?php if (count($charges) > 0): ?>
                          <?php $importeTotal = 0; ?>
                          <?php foreach ($charges as $charge): ?>
          <?php $importeTotal += $charge->import; ?>
                          <?php endforeach; ?>
                          <span class="label label-success btn-edit-cobro <?php if ($rateUser->rate && $rateUser->rate->mode > 1) {
                            echo 'mode-trim';
                          } ?>" data-toggle="modal" data-target="#modal-editar-cobro" data-rate="<?php echo $rateUser->id_rate ?>" data-charge="{{ $charge->id }}" style="cursor: pointer">
        <?php echo $importeTotal; ?> €/Mes
                          </span><br>
                        <?php else: ?>
                          <?php $pendiente = 1 ?>
        <?php if ($user->status == 1): ?>
                            <span class="label label-danger btn-cobro" data-toggle="modal" data-target="#modal-popout" data-idUser="<?php echo $user->id; ?>" data-dateCobro="<?php echo $month->copy()->format('Y-m-d') ?>" data-import="<?php echo $rateUser->rate->price ?>" data-rate="<?php echo $rateUser->id_rate ?>" style="cursor: pointer">
                            <?php echo $rateUser->rate->price; ?>€ /Mes 
                            </span><br>	
        <?php else: ?>
                            <?php if ($user->status == 1): ?>
                              <span class="text-primary btn-cobro" data-toggle="modal" data-target="#modal-popout" data-idUser="<?php echo $user->id; ?>" data-dateCobro="<?php echo $month->copy()->format('Y-m-d') ?>" data-import="<?php echo $rateUser->rate->price ?>" data-rate="<?php echo $rateUser->id_rate ?>" style="cursor: pointer">
                                <i class="fa fa-plus"></i>
                              </span><br>
                            <?php else: ?>
                              Inactivo
          <?php endif ?>
          <?php break; ?>
        <?php endif ?>
      <?php endif ?>


                  <?php endforeach ?>
                  <?php $month->addMonth(); ?>
  <?php endfor; ?>


                  <td class="text-center">
  <?php if ($pendiente == 0): ?>
                      <i class="fa fa-circle text-success" aria-hidden="true"></i>
                      <span class="hidden">R. Corriente</span>
  <?php elseif ($user->status == 0): ?>
                      <i class="fa fa-circle text-warning" aria-hidden="true"></i>
                      <!--  -->

  <?php else: ?>
                      <i class="fa fa-circle text-danger" aria-hidden="true"></i>
                      <span class="hidden">Pendiente</span>

  <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <button class="btn btn-default btn-rate-charge" data-toggle="modal" data-target="#modalRateCobro" data-idUser="<?php echo $user->id; ?>">
                      <i class="fa fa-usd" aria-hidden="true"></i>
                    </button>
                  </td>
                </tr>
<?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div> 
    </div>
  </div>
</div>
<div class="modal fade in" id="modal-newUser" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
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
<div class="modal fade in" id="modalRateCobro" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
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
        <div class="row block-content" id="content-rate-charge">

        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade in" id="modal-popout" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
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
        <div class="row block-content" id="content">

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade in" id="modal-popout-inform" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg" style="width: 70%;">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
        </div>
        <div class="row block-content" id="content-inform">

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade in" id="modal-date" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg" style="width: 90%;">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
        </div>
        <div class="row block-content" id="content-date">

        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-ingreso" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-dialog-popout">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
        </div>
        <div class="row block-content" id="contentListIngresos">

        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-editar-cobro" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-dialog-popout">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
        </div>
        <div class="row block-content" id="content-edit-cobro">

        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@section('scripts')
<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>
<script type="text/javascript">
$(document).ready(function () {
  $('#cuotas-pendientes').click(function () {
    $('#estado-payment').click();
  })

  $('#addIngreso').click(function () {
    $.get('/admin/nuevo/ingreso', function (data) {
      $('#contentListIngresos').empty().append(data);
    });
  });

  $('.btn-cobro').click(function (e) {
    e.preventDefault();

    var dateCobro = $(this).attr('data-dateCobro');
    var id_user = $(this).attr('data-idUser');
    var importe = $(this).attr('data-import');
    var rate = $(this).attr('data-rate');

    $('#content').empty().load('/admin/generar/cobro?dateCobro=' + dateCobro + '&id_user=' + id_user + '&importe=' + importe + '&rate=' + rate);
  });



  $('.btn-edit-cobro').click(function (e) {
    e.preventDefault();
    var charge_id = $(this).attr('data-charge');
    var rate_id = $(this).attr('data-rate');
    $('#content-edit-cobro').empty().load('/admin/update/cobro/' + charge_id + '/' + rate_id);
  });

  $('#newUser').click(function (e) {
    e.preventDefault();
    $('#content-new-user').empty().load('/admin/usuarios/new');
  });

  $('.btn-user').click(function (e) {
    e.preventDefault();
    var id = $(this).attr('data-idUser');
    $('#content-inform').empty().load('/admin/usuarios/informe/' + id);

  });

  $('.btn-rate-charge').click(function (e) {
    e.preventDefault();
    var id_user = $(this).attr('data-idUser');
    $('#content-rate-charge').empty().load('/admin/usuarios/cobrar/tarifa?id_user=' + id_user);
  });



  $('#date').change(function (event) {

    var month = $(this).val();
    window.location = '/admin/clientes/' + month;
  });

  $('.switchStatus').change(function (event) {
    var id = $(this).attr('data-id');

    if ($(this).is(':checked')) {
      $.get('/admin/usuarios/activate/' + id, function (data) {
      });
    } else {
      $.get('/admin/usuarios/disable/' + id, function (data) {
      });
    }
  });

  $('#date-nutri, #date-fisio').click(function (event) {
    event.preventDefault();
    var id_user = $(this).attr('data-idUser');
    var consulta = $(this).attr('data-title');
    // $.get(', {id_user: id_user}, function(data) {
    $('#content-date').empty().load('/admin/citas/form/inform/create/' + id_user + '/' + consulta);
    // });
  });


});
</script>
@endsection