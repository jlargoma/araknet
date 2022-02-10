@extends('layouts.admin-master')

@section('title') Citas {{$title}} Araknet HTS @endsection
@section('headerTitle') Citas {{$title}} @endsection
@section('headerButtoms')
<button type="button" class="btn btn-success addDate" data-date="{{time()}}" data-time="8">
    <i class="fa fa-plus-circle"></i></button>
    <a href="/admin/citas/{{$type}}/" class="btn btn-success" style="float: right; margin-left: 3px;">Calendario</a>
    <a href="/admin/citas-week/{{$type}}" class="btn btn-success" style="float: right; margin-left: 3px;">Semana</a>

@endsection
@section('externalScripts')
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
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
		th.text-center{
			background-color: #46c37b!important;
		}
	</style>
@endsection
@section('content')

<div class="content content-full bg-white">
	<div class="row">
            <div class="col-md-12">
                <input type="hidden" id="usersFilter" value="{{$userID}}">
                <input type="hidden" id="selectMonth" value="{{$month}}">
                
                <div class="row">
                <div class="col-xs-10">
                <ul class="usersFilter">
                   <li data-val="0" class="select_0 <?php echo ($userID == 0) ? 'active' : ''?>">
                       TODOS
                    </li>
                @foreach($users as $item)
                <li data-val="{{$item->id}}" class="select_<?php echo $item->id ?> <?php echo ($userID == $item->id) ? 'active' : ''?>">
                    {{$item->name}}
                </li>
                @endforeach
                </ul>
                </div>
                <div class="col-xs-2 mx-1em">
                    <select id="servSelect" class="form-control">
                        <option value="0">Servicio</option>
                        <?php
                        if ($types){
                            foreach ($types as $k=>$v){
                                $selected = ($rate == $k) ? 'selected' : '';
                                echo '<option value="'.$k.'" '.$selected.'>'.$v.'</optiono>';
                            }
                        }
                        ?>
                    </select>
                    
                </div>
                </div>
                 @include('citas.tabla')
            </div>
	</div>
</div>
    @include('citas.modals')
@endsection

@section('scripts')
<link rel="stylesheet" href="{{ assetV('css/calendars.css') }}">

<style>

   @foreach($tColors as $k=>$v)
    ul.usersFilter li.select_{{$k}} {
            background-color: {{$v}};
            color: #FFF;
        }
    .eventType_{{$k}} {background-color: {{$v}};}
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
<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>
<script type="text/javascript">
    var dateForm = null;
    var timeForm = null;
$('.addDate').click(function(event){
    event.preventDefault();
    dateForm = $(this).data('date');
    timeForm = $(this).data('time');
    $('#ifrModal').attr('src','/admin/citas/create/'+dateForm+'/'+timeForm);
    $('#modalIfrm').modal();
});

$('.usersFilter').on('click','li',function(event){
    event.preventDefault();
    var coach = $(this).data('val');
    var type = $('#servSelect').val();
    location.assign("/admin/citas-listado/{{$type}}/"+coach+"/"+type);
});
$('#servSelect').on('change',function(event){
    event.preventDefault();
    var type = $('#servSelect').val();
    var coach = $('#usersFilter').val();
    location.assign("/admin/citas-listado/{{$type}}/"+coach+"/"+type);
});

$('#modal_newUser').on('submit','#form-new',function(event){
    event.preventDefault();
   // Get some values from elements on the page:
    var $form = $( this );
    var url       = $form.attr( "action" );
    // Send the data using post
    var posting = $.post( url, $form.serialize() ).done(function( data ) {
        if (data == 'OK'){
            $('#content-add-date').load('/admin/citas/{{$type}}/create/'+dateForm+'/'+timeForm);
            $('#modal_newUser').modal('hide');
            $('#modal-add-date').modal();
        } else {
            alert(data);
        }
    });
});

$('.js-dataTable-citas').on('click','.showInform',function(event){
    event.preventDefault();
    var id = $(this).data('id');
    $('#ifrCliente').attr('src','/admin/cliente/informe/' + id);
    $('#modalCliente').modal();
});

</script>
@endsection