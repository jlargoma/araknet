
<div class="row ">
    <div class="col-xs-12">
        <div class="col-xs-12 col-md-12 text-left">
            <h1 class="text-center">
            	<?php echo $user->name; ?>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 push-10 bg-white" >
    	<div class="col-md-6" style="margin-right: 1px solid #e8e8e8;">
    		<div class="col-md-12">
    			<?php $rates = \App\Rates::orderBy('status', 'desc')->orderBy('name', 'asc')->get();  ?>
    			@include('admin.usuarios._form', ['rates' => $rates])
    		</div>
    	</div>
    	<div class="col-md-6" style="margin-left: 1px solid #e8e8e8;">
    		<div class="col-md-12">
    			<div class="col-xs-12 push-20">
    				<h3 class="text-left">SERVICIOS ASOCIADOS</h3>
    			</div>
                <?php $totalRatesUser = 0; ?>
                <?php $userRates = \App\UserRates::where('id_user', $user->id)
                                                    ->whereMonth('created_at', "=", date('m'))
                                                    ->whereYear('created_at', "=", date('Y'))
                                                    ->get() 
                ?>
                <?php foreach ($userRates as $key => $userRate): ?>
                    <div class="col-sm-6 col-lg-3 not-padding" style="margin: 0 15px;">
                        <div class="block block-link-hover3 text-center  rates-inform" data-idRate="<?php echo $userRate->id_rate ?>" data-idUser="<?php echo $userRate->id_user ?>" style="cursor: pointer;">
                            <div class="block-header">
                                <h3 class="block-title">
                                    <?php echo substr($userRate->rate->name, 0, 10); ?>...
                                </h3>
                            </div>
                            <div class="block-content block-content-full bg-gray-lighter">
                                <div class="h1 font-w700 push-10">
                                    <?php echo $userRate->rate->price ?>€
                                </div>
                                <div class="h5 font-w300 text-muted">Por mes</div>
                            </div>
                        </div>
                        <div class="tooltip-informe-cliente rate-<?php echo $userRate->id_rate ?>"  style="display: none;"></div>
                    </div>  
                    <?php $totalRatesUser += $userRate->rate->price; ?>
                <?php endforeach ?>
            </div> 
    	</div>
    </div>

    <div class="col-md-12 push-30 bg-white" style="padding: 0 20px;">
    	<div class="col-xs-12 hidden">
    		<div class="col-md-4 col-xs-4 text-center pull-left">
    			<a href="{{url('/admin/usuarios/informe')}}/<?php echo $year-1; ?>/<?php echo $user->id; ?>" class="btn btn-primary push-5-r push-10">
    				<?php echo $year-1; ?>
    			</a>
    			
    		</div>
    		<div class="col-md-4 col-xs-4 text-center ">
    			<?php if ($year == date('Y') || $year > date('Y')) :?>
					<button disabled="" class="btn btn-primary push-5-r push-10">
						<?php echo $year; ?>
					</button>
    			<?php else: ?>
					<a href="{{url('/admin/usuarios/informe')}}/<?php echo $year; ?>/<?php echo $user->id; ?>" class="btn btn-primary push-5-r push-10">
    				<?php echo $year; ?>
    			</a>
    			<?php endif; ?>
    			
    		</div>
    		<div class="col-md-4 col-xs-4 text-center pull-right">
    			<?php if ($year == date('Y') || $year > date('Y')) :?>
					<button disabled="" class="btn btn-primary push-5-r push-10">
						<?php echo $year+1; ?>
					</button>
    			<?php else: ?>
					<a href="{{url('/admin/usuarios/informe')}}/<?php echo $year+1; ?>/<?php echo $user->id; ?>" class="btn btn-primary push-5-r push-10">
    				<?php echo $year+1; ?>
    			</a>
    			<?php endif; ?>
    		</div>
    	</div>
    	<div class="row table-responsive">
			<table class="table table-bordered table-striped table-header-bg">
				<thead>
				<tr>
					<th class="text-center"> </th>
				    <?php foreach ($months as $month): ?>
					<th class="text-center"><?php echo $month; ?></th>
				    <?php endforeach ?>
					<th class="text-center">Total ANUAL</th>
				</tr>
				</thead>
				<tbody>
				<!-- MENSUALIDADES -->

			    <?php $totalUser = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0,6 => 0,7 => 0,8 => 0,9 => 0,10 => 0,11 => 0,12 => 0) ?>
			    <?php $totalAnualUser = 0; ?>
			    <?php foreach ($services as $service): ?>

			    <?php $totalServiceUser = 0; ?>
				<tr>
					<td class="text-center">
						<b><?php echo $service->name ?></b>
					</td>
				    <?php foreach ($months as $key => $month): ?>
					<td class="text-center">
					    <?php
					    $cobroUser = \App\Charges::distinct(['date_payment'])
					                             ->where('id_user', $user->id)
					                             ->where('type_rate', $service->id)
					                             ->whereMonth('date_payment' ,'=', $key)
					                             ->whereYear('date_payment' ,'=', $year)
					                             ->get();
					    ?>
					    <?php if ( count($cobroUser) > 0 ): ?>
					    <?php $montly = 0; ?>
					    <?php foreach ($cobroUser as $charge): ?>
                                        <?php $montly += $charge->import; ?>
                                        <?php $totalServiceUser += $charge->import; ?>

                                    <?php endforeach ?>
						<b><?php echo $montly; ?>€</b>
					    <?php $totalUser[$key] += $montly; ?>
					    <?php else: ?>
						---
					    <?php endif ?>
					</td>
				    <?php endforeach ?>
					<td class="text-center">
						<b><?php echo $totalServiceUser ?>€</b>
					    <?php $totalAnualUser += $totalServiceUser; ?>
					</td>
				</tr>
			    <?php endforeach ?>

				<tr>
					<td class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 18px;">
						<b>TOTALES</b>
					</td>
				    <?php for ($i=1; $i <= 12; $i++)  : ?>
					<td class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 18px;">
					    <?php echo $totalUser[$i] ?>€
					</td>
				    <?php endfor; ?>
					<td class="text-center" style="color: #fff; background-color: #5c90d2; border-bottom-color: #5c90d2; font-size: 18px;">
						<h2 class="text-center">
						    <?php echo $totalAnualUser //+ $totAnualBonoUser + $totAnualBonoEspUser;?>€
						</h2>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $( ".rates-inform" ).mouseenter(function() {
            var idRate = $(this).attr('data-idrate');
            var idUser = $(this).attr('data-iduser');
            $.get( '/admin/desgloce/tarifa/usuario/', { idRate: idRate, idUser: idUser } ).done(function( data ) {
                $(".rate-"+idRate).empty();
                $(".rate-"+idRate).append(data);
                $(".rate-"+idRate).show('fast');
            });
        }).mouseleave(function() {
            var idRate = $(this).attr('data-idrate');
            var idUser = $(this).attr('data-iduser');
            $(".rate-"+idRate).empty();
            $(".rate-"+idRate).hide('fast');
        });
    });
</script>