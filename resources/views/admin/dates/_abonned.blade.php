<?php use \Carbon\Carbon; ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<div class="col-xs-6">
	<div class="col-xs-12">
		<form class="form-horizontal" method="post" action="{{ url('/admin/citas/charged/charge') }}">
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    		<input type="hidden" name="idDate" value="<?php echo $date->id; ?>">
    		<input type="hidden" name="type" value="1">
    		<div class="col-md-12 col-xs-12 push-20 text-center">
				<button class="btn btn-success btn-lg font-w300" type="submit">
					<i class="fa fa-tag fa-3x" aria-hidden="true"></i><br> POR BONO
				</button>
			</div>
		</form>
	</div>
</div>
