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
			<form action="{{ url('/admin/cashbox/create') }}" method="post">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				<div class="col-xs-12 form-group push-20">
					<div class="col-xs-12 col-md-6 push-20">
						<label for="date">Fecha</label>
						<input type="text" class="js-datepicker form-control" name="date" data-date-format="dd-mm-yyyy" placeholder="Fecha" required  style="cursor: pointer;" />
					</div>
					<div class="col-xs-12 form-group col-md-12 push-20">
						<label for="concept">Concepto</label>
						<textarea class="form-control" name="concept"></textarea>
						
					</div>
				</div>
				<div class=" col-xs-12 form-group push-20">
					<div class="col-xs-12 col-md-12 push-20">
						<label for="type">Tipo de Movimiento</label>

						<select class="js-select2 form-control" id="type_payment" name="type_payment" style="width: 100%;" data-placeholder="Seleccione un tipo" >
			                <option></option>
			                <optgroup label="TRASPASO">
								<option value="TRASPASO" >TRASPASO</option>
							</optgroup>
			                <optgroup label="GASTO">
				                <option value="MOBILIARIO">MOBILIARIO</option>
				                <option  value="SERVICIOS PROFESIONALES INDEPENDIENTES">SERVICIOS PROFESIONALES INDEPENDIENTES</option>
				                <option  value="VARIOS">VARIOS</option>
				                <option  value="EQUIPAMIENTO DEPORTIVO">EQUIPAMIENTO DEPORTIVO</option>
				                <option value="IMPUESTOS">IMPUESTOS</option>
				                <option  value="SUMINISTROS">SUMINISTROS</option>
				                <option value="GASTOS BANCARIOS">GASTOS BANCARIOS</option>
				                <option  value="PUBLICIDAD">PUBLICIDAD</option>
				                <option  value="REPARACION Y CONSERVACION">REPARACION Y CONSERVACION</option>
				                <option  value="ALQUILER NAVE">ALQUILER NAVE</option>
				                <option value="SEGUROS SOCIALES">SEGUROS SOCIALES</option>
				                <option  value="NOMINAS">NOMINAS</option>
				                <option  value="TARJETA VISA">TARJETA VISA</option>
				                <option  value="MATERIAL OFICINA">MATERIAL OFICINA</option>
				                <option  value="MENSAJERIA">MENSAJERIA</option>
				                <option  value="PRODUCTOS VENDING">PRODUCTOS VENDING</option>
				                <option value="LIMPIEZA">LIMPIEZA</option>
				                <option  value="INTERNET">INTERNET</option>
				                <option  value="RENTING EQUIPAMIENTO DEPORTIVO">RENTING EQUIPAMIENTO DEPORTIVO</option>
				                <option  value="COMISONES COMERCIALES">COMISONES COMERCIALES</option>
				            </optgroup>
				            <optgroup label="ARQUEO">
								<option value="ARQUEO" >ARQUEO</option>
							</optgroup>
			            </select>
					</div>
				</div>
				<div class=" col-xs-12 form-group push-20">
					
					<div class="col-xs-12 col-md-3 push-20">
						<label for="import">Importe</label>
						<input  type="text" name="import" id="import" class="form-control" required />

					</div>
					<div class=" col-xs-12 col-md-9 form-group push-20">
						<div class="col-xs-12 col-md-12 push-20">
							<label for="concept">Comentario</label>
							<textarea class="form-control" name="comment"></textarea>
							
						</div>
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