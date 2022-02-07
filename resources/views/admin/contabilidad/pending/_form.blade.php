<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
<div class="col-xs-12 bg-white">
	<div class="row">
		<div class="col-xs-12 push-20">
			<h2 class="text-center">Nuevo Pendiente </h2>
		</div>
		<div class="col-xs-12">
			<form action="{{ url('/admin/ingresos/create') }}" method="post">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				<div class=" col-xs-12 form-group push-20">
					<div class="col-xs-12 col-md-6 push-20">
						<label for="id_empresa">Empresa</label>
						<select class="js-select2 form-control" id="id_empresa" name="id_empresa" style="width: 100%;" data-placeholder="Seleccione una empresa" required>
			                <option></option>
			                <?php foreach ($empresas as $empresa): ?>
			                	<option value="<?php echo $empresa->id ?>" selected>
			                		<?php echo $empresa->name; ?>
			                	</option>
			                <?php endforeach ?>
			            </select>

					</div>
				
					<div class="col-xs-12 col-md-6 push-20">
						<label for="type">Tipo de ingreso</label>
						<select class="js-select2 form-control" id="type" name="type" style="width: 100%;" data-placeholder="Seleccione un tipo" required >
			                <option></option>
			                <option value="Cursos de Formación">
			                	Cursos de Formación
			                </option>
			                <option value="Eventos especiales Empresas">
			                	Eventos especiales Empresas
			                </option>
			                <option value="Venta Material Deportivo">
			                	Venta Material Deportivo
			                </option>
			                <option value="Vending">
			                	Vending
			                </option>
			            </select>
					</div>
				</div>
				<div class=" col-xs-12 form-group push-20">
					
					<div class="col-xs-12 col-md-6 push-20">
						<label for="import">Tipo de ingreso</label>
						<input  type="text" name="import" id="import" class="form-control" />

					</div>
					
					<div class="col-xs-12 col-md-6 push-20">
						<label for="date">fecha</label>
						<input type="text" class="js-datepicker form-control" name="date" data-date-format="dd-mm-yyyy" placeholder="Fecha" />
						
					</div>

				</div>
				<div class=" col-xs-12 form-group push-20">
					<div class="col-xs-12 col-md-12 push-20">
						<label for="concept">Concepto</label>
						<textarea class="form-control" name="concept"></textarea>
						
					</div>
				</div>
				<div class=" col-xs-12 form-group push-20">
					<div class="col-xs-12 text-center">
						<button class="btn btn-lg btn-success" type="submit">
							Añadir
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
	jQuery(function () {
        App.initHelpers(['datepicker', 'select2']);
    });
    $(document).ready(function() {
    
    });
</script>