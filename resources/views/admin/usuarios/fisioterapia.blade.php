<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
@extends('layouts.admin-master')

@section('title') Clientes - Evolutio HTS @endsection

@section('externalScripts')
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
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

		td {
			padding: 5px!important;
		}
		th.text-center,.date-nutri{
			background-color: #70b9eb!important;
		}
	</style>
@endsection

@section('headerButtoms')

@endsection


@section('content')
<?php 
		$url = Request::url(); 
        $domain = substr (strrchr ($url, "/"), 1 ); 
        $today = $date->copy();
?>
<div class="content content-full bg-gray-lighter">
	<div class="row ">
		<div class="col-md-2 col-xs-12 push-20">
			<div class="row text-center">
				<div class="col-xs-12 text-muted addDate" style="cursor: pointer;" data-toggle="modal" data-target="#modal-add-date" type="button">
					<i class="fa fa-plus fa-3x">Cita nueva</i>
				</div>
			</div>
		</div>

    	<div class="col-xs-12">
    		<h2 class="text-center font-s36 font-w300">
    			<?php echo strtoupper('Listado de Citas Fisioterapia') ?>
    			
    		</h2>
    	</div>
    	<div style="clear:both;"></div><br>
        <div class="col-xs-12 push-20">
		    <div class="row">
		        <div class="col-md-12">
	                <table class="table table-striped js-dataTable-full-clients table-header-bg dataTable no-footer">
	                    <thead>
	                        <tr>
	                            <th class="text-center">Nombre<br></th>
	                            <th class="text-center sorting_disabled">Tel<span class="hidden-xs hidden-sm">éfono</span><br></th>
	                            <th class="text-center">Cita</th>

								<?php setlocale(LC_TIME, "ES"); ?>
								<?php setlocale(LC_TIME, "es_ES"); ?>
	                            <?php $month = $date->copy()->startOfYear(); ?>
								<?php for ($i=1; $i < 13; $i++):?>
									<th class="text-center hidden-xs hidden-sm sorting_disabled">
							    		<?php echo $month->formatLocalized('%B'); ?>
							    	</th>
							    	<?php $month->addMonth(); ?>
								<?php endfor ;?>

	                        </tr> 
	                    </thead>
	                    <tbody>
							<?php foreach ($visitas as $visita): ?>
								<tr> 
									<td class="text-center"><b><?php echo $visita->user->name; ?></b></td>
									<td class="text-center"><?php echo $visita->user->telefono ?></td>
									<td class="text-center">
										<div class="btn btn-md btn-primary date-nutri" type="button" data-toggle="modal" data-target="#modal-date" style="margin-right: 20px;float: right;" data-title="FISIO" data-idUser="<?php echo $visita->user->id; ?>" >
											<i class="fa fa-plus-circle" aria-hidden="true"></i>
									
										</div>
									</td>
									<?php for ($i=1; $i < 13; $i++):?>
									<td><?php $citas = \App\Dates::where('id_user' , $visita->user->id)->where('id_type_rate',6)->whereMonth('date','=' , $i)->orderBy('date','ASC')->get(); ?>
										<?php if (count($citas) > 0): ?>
											<?php foreach ($citas as $cita): ?>
												<?php 
													$fecha = Carbon::createFromFormat('Y-m-d H:i:s',$cita->date);
												echo $fecha->format('d / H:i')."<br>" ?>
											<?php endforeach ?>
										<?php else: ?>	
											----------
										<?php endif ?>
									</td>
								<?php endfor; ?>
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

<div class="modal fade in" id="modal-newCheck" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
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
				<div class="row block-content" id="content-new-check">

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

<div class="modal fade" id="modal-add-date" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-popin">
		<div class="modal-content">
			<div class="block block-themed block-transparent remove-margin-b">
				<div class="block-header bg-primary-dark">
					<ul class="block-options">
						<li>
							<button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
						</li>
					</ul>
				</div>
				<div class="row block-content" id="content-add-date">

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
		$(document).ready(function() {

			$('.btn-cobro').click(function(e){
				e.preventDefault();

				var dateCobro = $(this).attr('data-dateCobro');
				var id_user   = $(this).attr('data-idUser');
				var importe   = $(this).attr('data-import');
				var rate      = $(this).attr('data-rate');

				$('#content').load('/admin/generar/cobro?dateCobro='+dateCobro+'&id_user='+id_user+'&importe='+importe+'&rate='+rate);


			});

			$('#newUser').click(function(e){
				e.preventDefault();
				$('#content-new-user').load('/admin/usuarios/new');
			});

			$('.newCheck').click(function(e){
				e.preventDefault();
				var id   = $(this).attr('data-id');
				console.log(id);
				$('#content-new-check').load('/admin/usuarios/newInforme/'+id);
			});

			$('.btn-user').click(function(e){
				e.preventDefault();
				var id   = $(this).attr('data-idUser');
				$('#content-inform').load('/admin/usuarios/informe-nutricion/'+<?php echo date('Y'); ?>+'/'+id);

			});


			$('.btn-rate-charge').click(function(e){
				e.preventDefault();
				var id_user   = $(this).attr('data-idUser');
				$('#content-rate-charge').load('/admin/usuarios/cobrar/tarifa?id_user='+id_user);
			});
			

			
			$('#date').change(function(event) {
				
				var month = $(this).val();
				window.location = '/admin/nutricion/'+month;
			});

			$('.switchStatus').change(function(event) {
				var id   = $(this).attr('data-id');

				if ( $(this).is(':checked') ) {
					$.get('/admin/usuarios/activate/'+id, function(data) {
					});
				}else{
					$.get('/admin/usuarios/disable/'+id, function(data) {
					});
				}
			});

			$('.date-nutri').click(function(event) {
				event.preventDefault();
				var id_user   = $(this).attr('data-iduser');
				var consulta   = $(this).attr('data-title');
				console.log(id_user);
				// $.get(', {id_user: id_user}, function(data) {
				$('#content-date').load('/admin/citas/form/inform/create/'+id_user+'/'+consulta);
				// });
			});

			$('.addDate').click(function(event){
				event.preventDefault();

				$('#content-add-date').load('/admin/citas/crear/nuevo');

			});
		});
	</script>
@endsection