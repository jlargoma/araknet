@extends('layouts.admin-master')

@section('title') INFORME DE CIERRES DIARIOS - Evolutio HTS @endsection

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
                    <h2 class="text-center">INFORME DE CIERRES DIARIOS</h2>
                </div>
                <div class="col-md-12 col-xs-12 push-20">
                    <div class="col-md-4 col-xs-1 text-right">
                        <a href="/admin/informes/cierre-diario/{{$yesterday}}"><i class="fa fa-arrow-left"></i></a>
                    </div>
                    <div class="col-md-4 col-xs-10">
                        <div class="col-md-4 col-xs-12">
                            <label>Año</label>
                            <input value="{{$year}}" disabled=""  class="form-control">
                        </div>
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
                                <?php 
                                for ($i = 1; $i <= $endDay; $i++):
                                        $s = ($i == $day)? 'selected' : '';
                                        echo '<option value="'.$i.'" '.$s.'>'.$i.'</option>';
                                endfor; 
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-1">
                        <a href="/admin/informes/cierre-diario/{{$tomorrow}}"><i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-12 col-xs-12 push-20">
                    <table class="table table-striped table-header-bg">
                        <tbody>
                            <tr>
                                <td class="text-center bg-complete font-w800" rowspan="2">RESUMEN</td>
                                <td class="text-center bg-complete font-w800">Nº CLIENTES</td>
                                <td class="text-center bg-complete font-w800">CAJA</td>
                                <td class="text-center bg-complete font-w800">BANCO</td>
                                <td class="text-center bg-complete font-w800">TOTAL</td>
                            </tr>
                            <tr>
                                <td class="text-center  bg-complete"><?php echo count($clients); ?></td>
                                <td class="text-center  bg-complete">{{moneda($totalCash)}}</td>
                                <td class="text-center  bg-complete">{{moneda($totalBank)}}</td>
                                <td class="text-center  bg-complete">{{moneda($total)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-xs-12">
                    <table class="table table-striped table-header-bg">
                        <thead>
                            <tr>
                                <th class="text-center sorting_disabled"></th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Cobrado Caja</th>
                                <th class="text-center">Cobrado Banco</th>
                                <th class="text-center">Total Cobrado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $nameMonth = isset($months[$month]) ? $months[$month] : '';
                        foreach ($arrayDays as $key => $charge): ?>
                            <tr>
                                <td class="text-center sorting_disabled"></td>
                                <td class="text-center">
                                    <?php echo $key.' '.$nameMonth ?>
                                </td>
                                <td class="text-center">
                                    {{moneda($charge['cash'])}}
                                </td>
                                <td class="text-center">
                                    {{moneda($charge['bank'])}}
                                </td>
                                <td class="text-center">
                                    <b>{{moneda($charge['cash'] + $charge['bank'])}}</b>
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
@endsection
@section('scripts')
<script type="text/javascript">
    $('#date, #month, #day').change(function (event) {

        var year = $('#date').val();
        var month = $('#month').val();
        var day = $('#day').val();
        window.location = '/admin/informes/cierre-diario/' + month + '/' + day;
    });

</script>
@endsection