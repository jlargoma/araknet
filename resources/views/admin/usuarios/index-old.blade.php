@extends('layouts.admin-master')

@section('title') Usuarios - Araknet HTS @endsection

@section('externalScripts')
	<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection


@section('headerButtoms')
	<li class="text-center">
		<button id="newUser" class="btn btn-sm btn-success font-s16 font-w300" data-target="#modal-newUser" data-toggle="modal" style="padding: 10px 15px;">
	        <i class="fa fa-plus"></i> Usuario
	    </button>
	</li>
@endsection

@section('content')
<?php 
	$url = Request::url(); 
    $domain = substr (strrchr ($url, "/"), 1 ); 
?>
<div class="content content-full bg-gray-lighter">
	<div class="row">
	    <div class="col-md-12 push-30 push-t-30">
	    	<div class="col-xs-12">
	    		<h3 class="text-center">
	    			Listado de Usuarios
	    		</h3>
	    	</div>
			        <div class="col-md-12 table-responsive">
		                <table class="table table-bordered table-striped js-dataTable-full table-header-bg">
		                    <thead>
		                        <tr>
		                            <th class="text-center">id</th>
		                            <th class="text-center">Nombre</th>
		                            <th class="text-center">Email</th>
		                            <th class="text-center">Tel<span class="hidden-xs hidden-sm">éfono</span></th>
		                            <th class="text-center">Role</th>
		                            <th class="text-center">Creada</th>
		                            <th class="text-center">Acciones</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    <?php foreach ($customers as $key => $customer): ?>
		                       <tr>
		                       		<td class="text-center">
		                       			<?php if ($customer->status == 1): ?>
											<a href="{{ url('/admin/usuarios/disable')}}/<?php echo $customer->id ?>" class="btn btn-xs btn-success" type="button" data-toggle="tooltip" title="" data-original-title="Desactivar usuario"><i class="fa fa-circle"></i></a>
										<?php else: ?>
											<a href="{{ url('/admin/usuarios/activate')}}/<?php echo $customer->id ?>" class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Activar usuario"><i class="fa fa-circle"></i></a>
										<?php endif ?>
		                       		</td>
		                       		<td class="text-center">
		                       			<b><?php echo $customer->name; ?></b>
		                       		</td>
		                       		<td class="text-center"> 
		                       			<b><?php echo $customer->email; ?></b>
		                       		</td>
		                       		<td class="text-center"> 
		                       			<?php echo $customer->phone; ?>
		                       		</td>
		                       		
		                       		
		                       		<td class="text-center"> 
                            <?php 
                              switch ($customer->role):
                                case 'admin':
                                  ?><span class="label label-success">Administrador</span><?php
                                  break;
                                case 'teach':
                                case 'teacher':
                                  ?><span class="label label-primary">Entrenador</span><?php
                                  break;
                                case 'administrativo':
                                  ?><span class="label label-success">Administrativo</span><?php
                                  break;
                                case 'user':
                                  ?><span class="label label-warning">Usuario</span><?php
                                  break;
                              endswitch;
                            ?>
		                       		</td>
		                       		<td class="text-center"> 
		                       			<?php if ($customer->created_at == NULL): ?>
		                       			Creada directamente en BD
		                       		<?php else: ?>
		                       			<?php echo $customer->created_at; ?>
		                       		<?php endif ?>
		                       		</td>
		                       		<td class="text-center">
										<button id="updateuser" data-target="#modal-updateuser" class="btn-user btn btn-xs btn-success" type="button" data-idUser="{{ $customer->id }}" data-toggle="modal" title="" data-original-title="Actualizar Usuario"><i class="fa fa-edit"></i></button>
								
										<a onclick="return confirm('Seguro que desea eliminar a {{$customer->name}}');" href="{{ url('/admin/usuarios/delete')}}/<?php echo $customer->id ?>" class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title="" data-original-title="Eliminar usuario"><i class="fa fa-trash"></i></a>
		                       		</td>
		                       		
		                       </tr>
		                    <?php endforeach ?>
		                    </tbody>
		                </table>
			        </div>
		
	    </div>
	</div>
</div>

<div class="modal fade" id="modal-updateuser" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
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
				<div class="row block-content" id="content">

				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-newUser" role="dialog" aria-hidden="true">
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
@endsection


@section('scripts')
	<script src="{{ asset('admin-css/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
	<script src="{{ asset('admin-css/assets/js/pages/base_tables_datatables.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function() {			
			$('.btn-user').click(function(e){
				e.preventDefault();
				var id   = $(this).attr('data-idUser');
				// alert(id);
				$.get('/admin/usuarios/actualizarUsuario/'+id, function(data) {
					$('#content').empty().html(data);
				});
			});
			$('#newUser').click(function(e){
				e.preventDefault();
				$.get('/admin/usuarios/new', function(data) {
					$('#content-new-user').empty().html(data);
				});
			});
		});
	</script>
@endsection