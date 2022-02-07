@extends('layouts.admin-master')

@section('title') Citas Instalador Evolutio HTS @endsection
@section('headerTitle') Citas Instalador @endsection
@section('headerButtoms')
<button type="button" class="btn btn-success addDate" data-date="{{time()}}" data-time="8">
    <i class="fa fa-plus-circle"></i></button>
    <a href="/admin/citas-fisioterapia/listado/" class="btn btn-success" style="float: right; margin-left: 3px;">Listado</a>
    <a href="/admin/citas-fisioterapia-week/" class="btn btn-success" style="float: right; margin-left: 3px;">Semana</a>

@endsection
@section('content')
<div class="content content-full bg-white">
	<div class="row">
            <div class="col-md-12">
                <input type="hidden" id="coachsFilter" value="{{$coach}}">
                <input type="hidden" id="selectMonth" value="{{$month}}">
                <div class="row">
                    <div class="col-md-10">
                      <div class="mbl-tabs">
                    <ul class="coachsFilter">
                       <li data-val="0" class="select_0 <?php echo ($coach == 0) ? 'active' : ''?>">
                           TODOS
                        </li>
                    @foreach($coachs as $item)
                    <li data-val="{{$item->id}}" class="select_<?php echo $item->id ?> <?php echo ($coach == $item->id) ? 'active' : ''?>">
                      {{$item->name}} <span class="counter">0</span>
                    </li>
                    @endforeach
                    </ul>
                    </div>
                    </div>
                    <div class="col-md-2 mx-1em">
                        <button class="btn btn-horarios" data-toggle="modal" data-target="#modalIfrm">Horarios</button>
                        <button class="btn btn-bloqueo" data-toggle="modal" data-target="#modalIfrm">Bloqueos</button>
                    </div>
               </div>
                <div class="row">  
                <div class="col-md-2 col-xs-12  mx-1em">
                  <input type="search" id="search_cust" class="form-control" placeholder="Buscar clientes">
                </div>
                <div class="col-md-8 col-xs-12">
                  <div class="mbl-tabs">
                 <!--<button class="btn btn-success btnAvails" type="button">DISPONIBLES</button>-->
                <ul class="selectDate">
                @foreach($aMonths as $k=>$v)
                <li data-val="{{$k}}" class="<?php echo ($month == $k) ? 'active' : ''?>">
                    {{$v}}
                </li>
                @endforeach
                </ul>
                </div>
                </div>
                <div class="col-md-2 col-xs-12 mx-1em">
                    <select id="servSelect" class="form-control">
                        <option value="0">Servicio</option>
                        <?php
                        if ($servLst){
                            foreach ($servLst as $k=>$v){
                                $selected = ($serv == $k) ? 'selected' : '';
                                echo '<option value="'.$k.'" '.$selected.'>'.$v.'</optiono>';
                            }
                        }
                        ?>
                    </select>
                    
                </div>
                </div>
                
                @include('calendars.calendar')
            </div>
	</div>
</div>
    @include('fisioterapia.modals')
@endsection

@section('scripts')
<link rel="stylesheet" href="{{ assetV('css/calendars.css?v1') }}">

<style>

    @foreach($tColors as $k=>$v)
    ul.coachsFilter li.select_{{$k}} {
            background-color: {{$v}};
            color: #FFF;
        }
    .eventType_{{$k}} cust {background-color: {{$v}};}
    .eventType_{{$k}}.blocked span {background-color: {{$v}};}
    .coach_{{$k}} {background-color: {{$v}};}
    
    @endforeach
    .time.not{
        background-color: #ddd;
        border-color: #c1c1c1 !important;
    }
</style>

<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/moment.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js')}}"></script>
<script type="text/javascript">
  @if($detail)
    var details = {!!$detail!!};
  @endif
  var typeCalend = 'month';

  var countByCoah = <?php echo json_encode($countByCoah) ?>;
  
</script>
<script src="{{assetv('/js/calendar/fisio.js')}}"></script>
<script src="{{assetv('/admin-css/assets/js/toltip.js')}}"></script>
@endsection