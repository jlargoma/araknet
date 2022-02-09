<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
<div class="row">
	<div class="col-md-4 col-xs-12">
	<div class="col-xs-12 bg-white">
		<div class="row">
			<div class="col-xs-12 push-20">
				<h2 class="text-center">Cita para <?php echo $type->name ?></h2>
			</div>
			<div class="col-xs-12" id="content-form-date">
				<form action="{{ url('/admin/citas/createAdvanced') }}" method="post" id="newDate">
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
					<div class="col-xs-12 form-group push-20">
					
						<div class="col-xs-12 col-md-6 push-20">
							<label for="id_type_rate">Servicio</label>
							<h3 class="text-center"> <?php echo $type->name ?></h3>
							<input type="hidden" name="id_type_rate" id="id_type_rate" class="form-control" value="<?php echo $type->id ?>">
						</div>
						<div class="col-xs-12 col-md-6 push-20">
							<label for="customer_id">Usuario</label>
							<h3 class="text-center"> <?php echo $customer->name ?></h3>
							<input type="hidden" name="customer_id" id="customer_id" class="form-control" value="<?php echo $customer->id ?>">
						</div>
					</div>
					<div class=" col-xs-12 form-group push-20">
						<div class="col-xs-12 col-md-6   push-20">
							<label for="date">Fecha</label>
	                        <input class="js-datepicker form-control" type="text" id="date" name="date" placeholder="Fecha..." style="cursor: pointer;" data-date-format="dd-mm-yyyy"/>
	                    </div>
	                    <div class="col-xs-12 col-md-6  not-padding  push-20">
	        				<label for="hour">hora</label>
	        				<select class="form-control" id="hour" name="hour" style="width: 100%;" placeholder="hora" required >
	        					<?php for ($i=8; $i <= 22; $i++) :?>
	        						<?php if($i < 10){ $hour = "0".$i; }else{ $hour = $i; } ?>
									<option value="<?php echo $hour ?>">
										<?php echo $hour ?>: 00
									</option>
	        					<?php endfor; ?>
	        	                
	        	            </select>
	                    </div>
	                    <div class="col-xs-12 col-md-6 push-20">
							<label for="customer_id">Coach</label>
							<select class=" form-control" id="user_id" name="user_id" style="width: 100%;" placeholder="Seleccione un coach" required >
								<option></option>
				                <?php if (Auth::user()->role == 'nutri'): ?>
				                	<?php foreach ($nutris as $nutri): ?>
				                		<option value="<?php echo $nutri->id; ?>">
				                			<?php echo $nutri->name; ?>
				                		</option>
				                	<?php endforeach ?>
				                <?php else: ?>
				                	<?php foreach ($coachs as $key => $coach): ?>
				                		<option value="<?php echo $coach->id; ?>">
				                			<?php echo $coach->name; ?>
				                		</option>
				                	<?php endforeach ?>

				                <?php endif ?>
				            </select>
						</div>
						<div class="col-xs-12 col-md-6 push-20">
							<label for="type">Accion</label>
							<select class=" form-control" id="type" name="type" style="width: 100%;" placeholder="Seleccione acción" required >
				                <option value="1">Reservar</option>
				                <?php if (Auth::user()->role == 'nutri'): ?>
				                
				                <?php else: ?>
				                	<?php if ( $hasBond ): ?>
				                		<option value="2">Cobrar BONO</option>
				                	<?php endif ?>
				                	<option value="3">Cobrar Efectivo</option>
				                	<option value="4">Cobrar Tarjeta</option>
				                	<option value="5">Invitado</option>
				                <?php endif ?>
				               
				            </select>
						</div>
						<div class="col-xs-12 col-md-6 push-20" style="display: none" id="cont-rate">
							<label for="rate_id">Tarifa</label>
							<select class=" form-control" id="rate_id" name="rate_id" style="width: 100%;" placeholder="Seleccione tarifa" >
				                <?php foreach (\App\Rates::where('type', $type->id)->get() as $key => $rate): ?>
				                	<option value="<?php echo $rate->id ?>" data-price="<?php echo $rate->price ?>">
				                		<?php echo $rate->name ?>
				                	</option>
				                <?php endforeach ?>
				            </select>
						</div>
						<div class="col-xs-12 col-md-6 push-20" style="display: none" id="content-price-rate">
							<h2 class="text-center" style="padding: 15px; font-size: 36px;" id="price-rate"></h2>
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
	
	</div>
	<div class="col-md-8 col-xs-12">
		<div class="row">
			<div class="col-md-12 col-xs-12 push-20">
				<div class="col-md-4 pull-right">
					<select id="week" class="form-control">
						<?php $weeks = $week->copy()->subMonths(4)->startOfWeek(); ?>
						<?php for ($i = 1; $i < 20 ; $i++): ?>
							<?php 
								if ( $selectedWeek == $weeks->copy()->format("W")) {
									$selected = 'selected';
								}else{
									$selected = '';
								}
								
							?>
							<option value="<?php echo $weeks->copy()->format('Y-m-d'); ?>" <?php echo $selected ?>>
								Del <?php echo $weeks->copy()->formatLocalized('%d'); ?> al <?php echo $weeks->copy()->endOfWeek()->formatLocalized('%d'); ?> de <?php echo ucfirst($weeks->copy()->formatLocalized('%B')); ?>  
							</option>
							<?php $weeks->addWeek() ?>
						<?php endfor ?>
					</select>
				</div>
			</div>
		</div>
		@include('admin.dates._dates')
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
    $(document).ready(function() {

    	$('#type').change(function(event) {
    		var tipo = $(this).val();
    		if (tipo == 3 || tipo == 4) {
    			$('#cont-rate').show();
    			$('#content-price-rate').show();
    		}else{
    			$('#cont-rate').hide();
    			$('#content-price-rate').hide();
    		}
    	});

    	$('#rate_id ').change(function(event) {

    		var price = $('#rate_id option:selected').attr('data-price');
    		$('#price-rate').text(price+" €");
    	});


    	// Attach a submit handler to the form
    	$( "#newDate" ).submit(function( event ) {
    	 
    	  	// Stop form from submitting normally
    	  	event.preventDefault();
    	 
    	  	// Get some values from elements on the page:
    	  	var $form = $( this ),
				_token       = $form.find( "input[name='_token']" ).val(),
				id_type_rate = $form.find( "input[name='id_type_rate']" ).val(),
				customer_id      = $form.find( "input[name='customer_id']" ).val(),
				date         = $form.find( "input[name='date']" ).val(),
				hour         = $form.find( "select[name='hour']" ).val(),
				user_id     = $form.find( "select[name='user_id']" ).val(),
				type     	 = $form.find( "select[name='type']" ).val(),
				url          = $form.attr( "action" );

    	 	if (type == 3 || type == 4) {
    	 		// Send the data using post
    	  		var posting = $.post( url, { 
    	  								_token: _token,
										id_type_rate: id_type_rate,
										customer_id: customer_id,
										date: date,
										hour: hour,
										user_id: user_id,
										type: type,
										rate_id: $("select[name='rate_id']").val(),
    	  						} );
    	 	}else{
    	 		// Send the data using post
    	  		var posting = $.post( url, { 
    	  								_token: _token,
										id_type_rate: id_type_rate,
										customer_id: customer_id,
										date: date,
										hour: hour,
										user_id: user_id,
										type: type,
    	  						} );
    	 	}
    	  	
    	 
    	  	// Put the results in a div
    	  	posting.done(function( data ) {
    	  		alert(data);
    	    	$('#table-dates').empty().load('/admin/citas/_dates');
    	    	$("input[name='date']").val('');
    	 	});
    	});

	   
    	
    });
</script>