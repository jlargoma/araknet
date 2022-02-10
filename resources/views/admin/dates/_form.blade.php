<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">

<div class="col-xs-12 bg-white">
	<div class="row">
		<div class="col-xs-12 push-20">
			<h2 class="text-center">Nueva Cita </h2>
		</div>
		<div class="col-xs-12">
			<form action="{{ url('/admin/citas/create') }}" method="post">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				<div class="col-xs-12 form-group push-20">
				 
					<div class="col-xs-12 col-md-6 push-20">
						<label for="id_type_rate">Servicio</label>
						<select class="js-select2 form-control" id="id_type_rate" name="id_type_rate" style="width: 100%;" data-placeholder="Seleccione un servicio" required >
			                <option></option>
			                <?php if (Auth::user()->role == 'nutri'): ?> 
			                	<option value="5">Nutricion</option>
			                <?php elseif(Auth::user()->role == 'fisio'): ?>
								<option value="6">Fisioterapia</option>
			                <?php else: ?>
			                	<?php foreach ($services as $key => $service): ?>
			                		<option value="<?php echo $service->id; ?>">
			                			<?php echo $service->name; ?>
			                		</option>
			                	<?php endforeach ?>
			                <?php endif ?>
			                
			            </select>
					</div>
					<div class="col-xs-12 col-md-6 push-20">
						<label for="customer_id">Usuario</label>
						<!-- <select class="js-select2 form-control" id="customer_id" name="customer_id" style="width: 100%;" data-placeholder="Seleccione un usuario" required >
			                <option></option>
			                <?php foreach ($customers as $key => $customer): ?>
			                	<option value="<?php echo $customer->id; ?>">
			                		<?php echo $customer->name; ?>
			                	</option>
			                <?php endforeach ?>
			            </select> -->
						<select class="js-select2 form-control" id="customer_id" name="customer_id" style="width: 100%; cursor: pointer" data-placeholder="Seleccione usuario.." multiple >
							<option></option>
								<?php foreach ($customers as $key => $customer): ?>
									<option value="<?php echo $customer->id; ?>">
									<?php echo $customer->name; ?>
									</option>
								<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class=" col-xs-12 form-group push-20">
					<div class="col-xs-12 col-md-4  push-20">
						<label for="date">Fecha</label>
                        <input class="js-datepicker form-control" type="text" id="date" name="date" placeholder="Fecha y hora..." style="cursor: pointer;" data-date-format="dd-mm-yyyy"/>
                    </div>
                    <div class="col-xs-12 col-md-2 not-padding  push-20">
        				<label for="customer_id">hora</label>
        				<select class="form-control" id="hour" name="hour" style="width: 100%;" data-placeholder="hora" required >
        					<?php for ($i=8; $i <= 22; $i++) :?>
        						<?php if($i < 10){ $hour = "0".$i; }else{ $hour = $i; } ?>
								<option value="<?php echo $hour ?>">
									<?php echo $hour ?>: 00
								</option>
        					<?php endfor; ?>
        	                
        	            </select>
                    </div>
                    <div class="col-xs-12 col-md-6 push-20">
						<label for="user_id">Coach</label>

						<select class="js-select2 form-control" id="user_id" name="user_id" style="width: 100%; cursor: pointer" data-placeholder="Seleccione coach.." multiple >
							<option></option>
								<?php if (Auth::user()->role == 'nutri'): ?>
									<?php foreach ($nutris as $nutri): ?>
										<option value="<?php echo $nutri->id; ?>">
											<?php echo $nutri->name; ?>
										</option>
									<?php endforeach ?>
								<?php elseif (Auth::user()->role == 'fisio'): ?>
									<?php foreach ($fisios as $fisio): ?>
										<option value="<?php echo $fisio->id; ?>">
											<?php echo $fisio->name; ?>
										</option>
									<?php endforeach ?>
								<?php else: ?>
									<?php foreach ($users as $key => $coach): ?>
										<option value="<?php echo $coach->id; ?>">
											<?php echo $coach->name; ?>
										</option>
									<?php endforeach ?>

								<?php endif ?>
						</select>

						
						<!-- <select class="js-select2 form-control" id="user_id" name="user_id" style="width: 100%;" data-placeholder="Seleccione un coach" required >
			                <option></option>
			                <?php if (Auth::user()->role == 'nutri'): ?>
			                	<?php foreach ($nutris as $nutri): ?>
			                		<option value="<?php echo $nutri->id; ?>">
			                			<?php echo $nutri->name; ?>
			                		</option>
			                	<?php endforeach ?>
			                <?php else: ?>
			                	<?php foreach ($users as $key => $coach): ?>
			                		<option value="<?php echo $coach->id; ?>">
			                			<?php echo $coach->name; ?>
			                		</option>
			                	<?php endforeach ?>

			                <?php endif ?>
			                
			            </select> -->
					</div>
				</div>
				<div class=" col-xs-12 form-group push-20">
					<div class="col-xs-12 text-center">
						<button class="btn btn-lg btn-success" type="submit">
							Guardar
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/moment.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js')}}"></script>
<script type="text/javascript">
	jQuery(function () {
        App.initHelpers(['datepicker', 'select2', 'datetimepicker']);
    });

</script>