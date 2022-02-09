<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">

<?php if ($date->service->name == 'NUTRICION'): ?>
    <div class="col-xs-12 bg-white push-20" style="padding: 15px; border: 3px solid #46c37b">
<?php elseif ($date->service->name == 'FISIOTERAPIA'): ?>
    <div class="col-xs-12 bg-white push-20" style="padding: 15px; border: 3px solid #70b9eb">
<?php endif ?>

	<div class="row">
		<div class="col-xs-12 push-20">
			<h2 class="text-center">Cobrar Cita </h2>
		</div>
		<div class="col-xs-12">
			<form action="{{ url('/admin/citas/chargeAdvanced') }}" method="post" id="chargeDate">
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				<input type="hidden" name="idDate" value="<?php echo $date->id ?>">
				<div class="col-xs-12 form-group push-20">
				
					<div class="col-xs-12 col-md-6 push-20">
						<label for="id_type_rate">Servicio</label>
						<h3 class="text-center"> <?php echo $date->service->name ?></h3>
						<input type="hidden" name="id_type_rate" id="id_type_rate" class="form-control" value="<?php echo $date->service->id ?>">
					</div>
					<div class="col-xs-12 col-md-6 push-20">
						<label for="customer_id">Usuario</label>
						<h3 class="text-center"> <?php echo $date->user->name ?></h3>
						<input type="hidden" name="customer_id" id="customer_id" class="form-control" value="<?php echo $date->user->id ?>">
					</div>
				</div>
				<div class=" col-xs-12 form-group push-20">
					<div class="col-xs-12 col-md-6   push-20">
						<label for="date">Fecha</label>
                        <input class="js-datepicker form-control" type="text" id="date" name="date" placeholder="Fecha..." style="cursor: pointer;" data-date-format="dd-mm-yyyy" value="<?php echo date('Y-m-d', strtotime($date->date)) ?>" />
                    </div>
                    <div class="col-xs-12 col-md-6  not-padding  push-20">
                    	<?php $hourDate =  date('G', strtotime($date->date)) ?>
        				<label for="hour">hora</label>
        				<select class="form-control" id="hour" name="hour" style="width: 100%;" placeholder="hora" required >
        					<?php for ($i=8; $i <= 22; $i++) :?>
        						<?php if($i < 10){ $hour = "0".$i; }else{ $hour = $i; } ?>
								<option value="<?php echo $hour ?>" <?php if($hourDate == $i){ echo "selected"; } ?>>
									<?php echo $hour ?>: 00
								</option>
        					<?php endfor; ?>
        	                
        	            </select>
                    </div>
                    <div class="col-xs-12 col-md-6 push-20">
						<label for="customer_id">Coach</label>
			            <h3 class="text-center"> <?php echo $date->coach->name ?></h3>
						<input type="hidden" name="user_id" id="user_id" class="form-control" value="<?php echo $date->coach->id ?>">
					</div>
					<div class="col-xs-12 col-md-6 push-20">
						<label for="type">Accion</label>.
                        <?php echo $hasBond ?>
						<select class=" form-control" id="type" name="type" style="width: 100%;" placeholder="Seleccione acción" required >

			                <?php if ( $hasBond ): ?>
			                	<option value="2">Cobrar BONO</option>
			                <?php endif ?>
			                <option value="3">Cobrar Efectivo</option>
			                <option value="4">Cobrar Tarjeta</option>
			                <option value="5">Invitado</option>
			            </select>
					</div>
					<div class="col-xs-12 col-md-6 push-20" style="display: none" id="cont-rate">
						<label for="rate_id">Tarifa</label>
						<select class=" form-control" id="rate_id" name="rate_id" style="width: 100%;" placeholder="Seleccione tarifa" >
			                <?php foreach (\App\Rates::where('type', $date->service->id)->get() as $key => $rate): ?>
			                	<option value="<?php echo $rate->id ?>" data-price="<?php echo $rate->price ?>">
			                		<?php echo $rate->name ?>
			                	</option>
			                <?php endforeach ?>
			            </select>
					</div>
					<div class="col-xs-12 col-md-6 push-20" style="display: none" id="content-price-rate">
                        <label for="">Precio</label>
                        <input type="number" class="only-numbers control-form" id="price-rate" name="importe" >

					</div>
				</div>
				<div class=" col-xs-12 form-group push-20">

					<div class="col-xs-12 text-center">
                        <?php if ($date->service->name == 'NUTRICION'): ?>
                            <button class="btn btn-lg btn-success" type="submit">
                                Cobrar
                            </button>
                        <?php elseif ($date->service->name == 'FISIOTERAPIA'): ?>
                            <button class="btn btn-lg btn-primary" type="submit">
                                Cobrar
                            </button>
                        <?php endif ?>
					 <button class="btn btn-lg btn-success" type="submit">
                                Cobrar
                            </button>	
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/moment.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js')}}"></script>
<script type="text/javascript">
	jQuery(function () {
        App.initHelpers(['datepicker']);
    });
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
    	$('#price-rate').val(price);
    });

        $( "#chargeDate" ).submit(function( event ) {
             
                // Stop form from submitting normally
                event.preventDefault();
             
                // Get some values from elements on the page:
                var $form = $( this ),
                    _token       = $form.find( "input[name='_token']" ).val(),
                    importe      = $form.find( "input[name='importe']").val(),
                    idDate       = $form.find( "input[name='idDate']" ).val(),
                    id_type_rate = $form.find( "input[name='id_type_rate']" ).val(),
                    customer_id      = $form.find( "input[name='customer_id']" ).val(),
                    date         = $form.find( "input[name='date']" ).val(),
                    hour         = $form.find( "select[name='hour']" ).val(),
                    user_id     = $form.find( "select[name='user_id']" ).val(),
                    type         = $form.find( "select[name='type']" ).val(),
                    url          = $form.attr( "action" );

                if (type == 3 || type == 4) {
                    // Send the data using post
                    var posting = $.post( url, { 
                                            _token: _token,
                                            idDate: idDate,
                                            id_type_rate: id_type_rate,
                                            customer_id: customer_id,
                                            date: date,
                                            hour: hour,
                                            user_id: user_id,
                                            type: type,
                                            importe: importe,
                                            rate_id: $("select[name='rate_id']").val(),
                                    } );
                }else{
                    // Send the data using post
                    var posting = $.post( url, { 
                                            _token: _token,
                                            idDate: idDate,
                                            id_type_rate: id_type_rate,
                                            customer_id: customer_id,
                                            date: date,
                                            hour: hour,
                                            user_id: user_id,
                                            type: type,
                                            importe: importe,
                                    } );
                }
                
             
                // Put the results in a div
                posting.done(function( data ) {
                    alert(data);
                    $('#table-dates').empty().load('/admin/citas/_dates');
                });
        });
</script>