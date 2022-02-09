<?php use \Carbon\Carbon; ?>
<?php $june = Carbon::createFromFormat('Y-m-d', '2016-06-01'); ?>
<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
	<body>
		<div class="container">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Usuario</th>
						<?php $date = $june->copy(); ?>
						<?php for ($i=1; $i <= 12; $i++): ?>
							<th><?php echo $date->copy()->formatLocalized('%B %y') ?></th>
							<?php $date->addMonths(1);  ?>
						<?php endfor;  ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($clientes as $key => $cliente): ?>
						<tr class="<?php if($cliente->status == 0){ echo 'danger'; }else{ echo 'active'; } ?>">
							<td><?php echo $cliente->name ?></td>
							<?php $date = $june->copy(); ?>
							<?php for ($i=1; $i <= 12; $i++): ?>
								<th>
									<?php 
											$charges = \App\Charges::where('customer_id', $cliente->id)
																	->whereYear('date_payment','=', $date->copy()->format('Y'))
																	->whereMonth('date_payment','=', $date->copy()->format('m'))
																	->get(); 
									$total = 0;
									foreach ($charges as $key => $charge): ?>
										<?php $total += $charge->import; ?>
									<?php endforeach ?>
									<?php echo $total; ?>€
								</th>
								<?php $date->addMonths(1);  ?>
							<?php endfor;  ?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</body>
</html>