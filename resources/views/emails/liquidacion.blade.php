<?php setlocale(LC_TIME, "ES"); ?>
<?php setlocale(LC_TIME, "es_ES"); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<title>Liquidación de <?php echo strtoupper($date->copy()->formatLocalized('%B')) ?> de {{ $user->name }}</title>
		<!-- Latest compiled and minified CSS & JS -->
		<link rel="stylesheet" href="{{ asset('/admin-css/assets/css/bootstrap.min.css') }}">
		<link rel="stylesheet" id="css-main" href="{{ asset('/admin-css/assets/css/oneui.css') }}">
		<style type="text/css">
			.col-xs-12 {
			    width: 100%;
			}
			.col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
			    float: left;
			}
			.table-borderless {
			    border: none;
			}
			.table {
			    width: 100%;
			    max-width: 100%;
			    margin-bottom: 20px;
			}
			table {
			    background-color: transparent;
			}
			table {
			    border-spacing: 0;
			    border-collapse: collapse;
			}

			table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
			    border-top: 0;
			}
			
			.table-vcenter > thead > tr > th, .table-vcenter > tbody > tr > th, .table-vcenter > tfoot > tr > th, .table-vcenter > thead > tr > td, .table-vcenter > tbody > tr > td, .table-vcenter > tfoot > tr > td {
			    vertical-align: middle;
			}
			
			.table-borderless > thead > tr > th, .table-borderless > thead > tr > td {
			    border-bottom: 1px solid #ddd;
			}
			
			.table-borderless > thead > tr > th, .table-borderless > tbody > tr > th, .table-borderless > tfoot > tr > th, .table-borderless > thead > tr > td, .table-borderless > tbody > tr > td, .table-borderless > tfoot > tr > td {
			    border: none;
			}
			
			.table > thead > tr > th {
			    border-bottom: 1px solid #ddd;
			}
			
			.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th {
			    padding: 16px 10px 12px;
			    font-family: "Source Sans Pro", "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
			    font-size: 15px;
			    font-weight: 600;
			    text-transform: uppercase;
			}
			
			.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
			    padding: 12px 10px;
			    border-top: 1px solid #f0f0f0;
			}
			
			.table>thead>tr>th {
			    vertical-align: bottom;
			    border-bottom: 2px solid #ddd;
			}
			
			.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
			    padding: 8px;
			    line-height: 1.42857143;
			    vertical-align: top;
			    border-top: 1px solid #ddd;
			}
			
			.text-center {
			    text-align: center;
			}
			
			th {
			    text-align: left;
			}
			
			td, th {
			    padding: 0;
			}
			
			* {
			    -webkit-box-sizing: border-box;
			    -moz-box-sizing: border-box;
			    box-sizing: border-box;
			}
		</style>
	</head>
	<body>
		<div id="page-container">
			<main id="main-container">
				<div class="content content-boxed bg-white">
					<div class="row">
					   	<div class="col-xs-12">
					   		<div class="col-xs-12 push-20" style="margin-bottom: 100px;">
					   			<h2 class="text-center font-w300">
					   				Liquidación de <span class="text-green font-w600"><?php echo strtoupper($date->copy()->formatLocalized('%B')) ?> </span> de <?php echo strtoupper( $user->name) ?>
					   			</h2>
					   		</div><br><br><br>
					   		<div class="col-xs-12">
					   			<table class="table table-borderless table-striped table-vcenter">
					   				<thead>
					   					<tr>
					   						<th class="text-center">#</th>
					   						<th class="text-left">Concepto</th>
					   						<th class="text-right">Total</th>
					   					</tr>
					   				</thead>
					   				<tbody>
					   					<?php 
					   						$i = 1; 
					   						$total = 0;
					   					?>
					   					<tr>
					   						<td class="text-center"><strong><?php echo $i ?></strong></td>
					   						<td class="text-left font-s20" style="text-transform: uppercase;">Salario Base</td>
					   						<td class="text-right font-s20"><strong><?php echo $taxCoach[0]->salary; ?>€</strong></td>
					   					</tr>
										<?php foreach ($pagosClase as $key => $pago): ?>
					   						<?php $i++ ?>
					   						<tr>
					   							<td class="text-center"><strong><?php echo $i ?></strong></td>
					   							<td class="text-left font-s18">
					   								<span class="text-primary">
					   									<?php echo strtoupper(str_replace('-', ' ', $key)) ?>
					   								</span><br>
					   								<?php 
						   								$clases = \App\CoachClasses::where('id_user', $user->id)
						   								                            ->where('id_class', $pago[0]->id_class)
						   								                            ->get();
					   								?>
			   										<?php foreach ($clases as $key1 => $clase): ?>
		   												<div class="col-xs-12">
		   														<?php echo ($clase->classes) ? strtoupper($clase->classes->name):'--' ?>
		   														 ----- 
		   														<?php echo date('d-m-Y h:00 A', strtotime($clase->date)) ?>
		   														 ----- 
		   														<?php echo $taxCoach[0]->ppc ?>€
		   												</div><br>
		   											<?php endforeach ?>
					   							</td>
					   							<td class="text-right font-s20"><strong><?php echo $totalClase[$key] ?>€</strong></td>
					   						</tr>
					   						<?php $total += $totalClase[$key]; ?>
					   					<?php endforeach ?>
					   						<?php $total += $taxCoach[0]->salary; ?>
					   					
					   					<tr class="success">
					   						<td colspan="2" class="text-right text-uppercase"><strong>Total:</strong></td>
					   						<td class="text-right font-s24"><strong><?php echo $total ?>€</strong></td>
					   					</tr>
					   				</tbody>
					   			</table>
					   		</div>
					   	</div>
					</div>
				</div>
			</main>
		</div>
		<script src="{{ asset('/admin-css/assets/js/core/jquery.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.scrollLock.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.appear.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.countTo.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/jquery.placeholder.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/core/js.cookie.min.js') }}"></script>
        <script src="{{ asset('/admin-css/assets/js/app.js') }}"></script>
        <script src="{{ assetV('/admin-css/assets/js/custom.js') }}"></script>
	</body>
</html>
