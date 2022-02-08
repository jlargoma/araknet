<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') INFORME DE CAJA - Araknet HTS @endsection

@section('externalScripts')
    <style>
        .bg-complete {
            color: #fff !important;
            background-color: #5c90d2 !important;
            border-bottom-color: #5c90d2 !important;
            font-weight: 800;
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <div class="content content-boxed bg-gray-lighter">
        <div class="row ">
            <div class="col-xs-12 push-20">
                <div class="row">
                    <div class="col-md-12 col-xs-12 push-20">
                        <h2 class="text-center">INGRESOS POR CAJA / MES</h2>
                    </div>
                    <div class="col-md-12 col-xs-12 push-20">
                        <div class="col-md-4 col-xs-12">
                            <input type="text" id="searchInform" class="form-control" placeholder="Buscar"
                                   style="margin-top: 24px;"/>
                            <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="col-md-4 col-xs-12">
                                <label>Mes</label>
                                <select id="month" class="form-control">
                                    <?php 
                                    foreach ($months as $k=>$v):
                                        $s = ($k == $month)? 'selected' : '';
                                        echo '<option value="'.$k.'" '.$s.'>'.$v.'</option>';
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label>Dia</label>
						
                                <select id="day" class="form-control">
                                    <option value="all">Todos</option>
                                    <?php for ($i = 1; $i <= $endDay; $i++):
                                        $s = ($i == $day)? 'selected' : '';
                                        echo '<option value="'.$i.'" '.$s.'>'.$i.'</option>';
                                    endfor; ?>
                                </select>
                            </div>
                        </div>
                      
                      <div class="col-md-4 col-xs-12">
                          <h2 class="text-center font-w300">
                              <?php echo convertDateToShow_text($date,true); ?>:
                              <span class="font-w800" style="font-weight: 800"><?php echo $totalCash; ?> €</span>
                          </h2>
                      </div>
                    </div>
                    <div class="row" id="content-table-inform">
                      
                      <div class="col-md-12 col-xs-12 push-20">
                        <table class="table table-striped table-header-bg">
                            <tbody>
                            <tr>
                                <td class="text-center bg-complete font-w800" rowspan="2">RESUMEN</td>
                                <td class="text-center bg-complete font-w800">Nº Clientes</td>
                                <td class="text-center bg-complete font-w800">Total</td>
                            </tr>
                            <tr>
                                <td class="text-center bg-complete"><?php echo count($aUsers); ?></td>
                                <td class="text-center bg-complete"><?php echo $cash; ?> €</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                      
                        @include('admin.informes._table_informes')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
      $('#date, #month, #day').change(function (event) {

        var year = $('#date').val();
        var month = $('#month').val();
        var day = $('#day').val();
        window.location = '/admin/informes/caja/' + month + '/' + day;
      });
    </script>
@endsection