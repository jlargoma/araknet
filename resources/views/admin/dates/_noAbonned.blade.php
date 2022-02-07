
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-css/assets/js/plugins/select2/select2-bootstrap.min.css') }}">
<div class="col-xs-6">
	<div class="col-xs-12">

		<form class="form-horizontal" method="post" action="{{ url('/admin/citas/charged/charge') }}">
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	    	<input type="hidden" name="type" value="2">
    		<input type="hidden" name="idDate" value="<?php echo $date->id; ?>">
    		<div class="col-xs-12 push-20">
				<select class="js-select2 form-control" id="id_rate" name="id_rate" style="width: 100%;" data-placeholder="Seleccione una tarifa" required >
	                <option></option>
	                <?php foreach (\App\Rates::where('type', $date->service->id)->get() as $key => $rate): ?>
	                	<option value="<?php echo $rate->id; ?>">
	                		<?php echo $rate->name; ?> <b><?php echo $rate->price; ?>€</b>
	                	</option>
	                <?php endforeach ?>
	            </select>
			</div>
			<div class="col-xs-12 push-20">
				<select class="form-control" id="type_pay" name="type_pay" style="width: 100%;" data-placeholder="Tipo de pago" required >
	                <option value="cash">Metalico</option>
	                <option value="banco">Tarjeta</option>
	            </select>
			</div>
    		<div class="col-md-12 col-xs-12 push-20 text-center">
				<button class="btn btn-success btn-lg font-w300" type="submit">
					<i class="fa fa-money fa-3x" aria-hidden="true"></i><br>PAGAR
				</button>
			</div>
		</form>
	</div>
</div>
<script src="{{asset('/admin-css/assets/js/plugins/select2/select2.full.min.js')}}"></script>
<script type="text/javascript">
	jQuery(function () {
        App.initHelpers(['select2']);
    });
</script>