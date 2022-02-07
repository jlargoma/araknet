<?php use \Carbon\Carbon; ?>

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
    	<div class="col-md-4" style="margin-right: 1px solid #e8e8e8;">
    		<div class="col-md-12">
    			<?php $rates = \App\Rates::all();  ?>
    			@include('admin.usuarios._form', ['rates' => $rates])
    		</div>
    	</div>
    	<div class="col-md-8" style="margin-left: 1px solid #e8e8e8;">
    		<div class="col-md-6">
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
                    <div class="col-sm-6 col-lg-6 not-padding" style="margin: 0 15px;">
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
                    </div>  
                    <?php $totalRatesUser += $userRate->rate->price; ?>
                <?php endforeach ?>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12 push-20">
                    <h3 class="text-left">INFORME GENERAL</h3>
                </div>                    
                <div class="col-sm-6 col-lg-12 not-padding" style="margin: 0 15px;">
                    <div class="block block-link-hover3 text-center ">
                        <div class="block-content block-content-full bg-gray-lighter">
                            <div class=" font-w700 push-10">
                                <?php foreach ($dates as $key => $date): ?>
                                    <?php $coach = Auth::id(); $fecha= Carbon::createFromFormat('Y-m-d H:i:s',$date->date);  ?>
                                    <a class="fecha" data-date="<?php echo $fecha->format('d-m-Y') ?>" style="cursor: pointer"><?php echo $fecha->format('d-m-Y') ?></a>
                                    <div id="<?php echo $fecha->format('d-m-Y') ?>" style="display: none">
                                        <?php $informes = \App\FisicCheck::where('id_user',$date->id_user)->where('id_date',$date->id)->get() ?>
                                        <?php if (count($informes) > 0): ?>
                                            <?php foreach ($informes as $informe): ?>
                                                <div class="col-md-5">
                                                    Edad:
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="age" id="age" class="form-control numeros  age-<?php echo $date->id ?>" value="<?php echo $informe->age ?>">
                                                </div>
                                                <div style="clear: both;"></div><br>
                                                <div class="col-md-5">
                                                    Altura /cm:
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="number" name="height" id="height" class="form-control numeros  height-<?php echo $date->id ?>" value="<?php echo $informe->height ?>">
                                                </div>
                                                <div style="clear: both;"></div><br>
                                                <div class="col-md-5">
                                                    Peso Actual/ Kg:
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="number" name="weight" id="weight" class="form-control numeros  weight-<?php echo $date->id ?>" value="<?php echo $informe->weight ?>">
                                                </div>
                                                <div style="clear: both;"></div><br>
                                                <div class="col-md-5">
                                                    Peso Ideal:
                                                </div>
                                                <div class="col-md-7">
                                                     <input type="number" name="objetive" id="objetive" class="form-control numeros  objetive-<?php echo $date->id ?>" value="<?php echo $informe->objetive ?>"> 
                                                </div>
                                                <div style="clear: both;"></div><br>
                                                <div class="col-md-5">
                                                    Basal:
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="basal" id="basal" class="form-control numeros  basal-<?php echo $date->id ?>" value="<?php echo $informe->basal ?>">
                                                </div>
                                                <div style="clear: both;"></div><br>                                        
                                                
                                                <textarea style="width: 100%" rows="4" class="comentario-<?php echo $date->id ?>"><?php echo $informe->comment ?></textarea>

                                                <input type="button" class="editable" value="guardar" data-id="<?php echo $date->id ?>" coach-id="<?php echo $coach ?>" user-id="<?php echo $date->id_user ?>"><br><br>
                                            <?php endforeach ?>
                                        <?php else: ?>
                                            <div class="col-md-5">
                                                    Edad:
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="age" id="age" class="form-control numeros  age-<?php echo $date->id ?>" value="<?php echo $age ?>">
                                                </div>
                                                <div style="clear: both;"></div><br>
                                                <div class="col-md-5">
                                                    Altura /cm:
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="number" name="height" id="height" class="form-control numeros  height-<?php echo $date->id ?>" value="<?php echo $height ?>">
                                                </div>
                                                <div style="clear: both;"></div><br>
                                                <div class="col-md-5">
                                                    Peso Actual/ Kg:
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="number" name="weight" id="weight" class="form-control numeros  weight-<?php echo $date->id ?>" >
                                                </div>
                                                <div style="clear: both;"></div><br>
                                                <div class="col-md-5">
                                                    Peso Ideal:
                                                </div>
                                                <div class="col-md-7">
                                                     <input type="number" name="objetive" id="objetive" class="form-control numeros  objetive-<?php echo $date->id ?>" value="<?php echo $objetive ?>"> 
                                                </div>
                                                <div style="clear: both;"></div><br>
                                                <div class="col-md-5">
                                                    Basal:
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="basal" id="basal" class="form-control numeros  basal-<?php echo $date->id ?>" >
                                                </div>
                                                <div style="clear: both;"></div><br>                                        
                                                
                                                <textarea style="width: 100%" rows="4" class=" comentario-<?php echo $date->id ?>"></textarea>

                                                <input type="button" class="editable" value="guardar" data-id="<?php echo $date->id ?>" coach-id="<?php echo $coach ?>" user-id="<?php echo $date->id_user ?>"><br><br>
                                        <?php endif ?>
                                    </div>
                                    <div style="clear: both;"></div>
                                <?php endforeach ?>
                                
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            <div style="clear: both;"></div>
            <div class="col-md-3 col-md-offset-6">
                <form class="form-horizontal" enctype="multipart/form-data" action="{{ url('/admin/nutricion/nutri/upload') }}" method="post">
                    <input class="form-control" type="hidden" name="nombre" value="<?php echo $user->name ?>">
                    <input class="form-control" type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input class="form-control" name="uploadedfile" type="file"  />
                    <input class="form-control" type="submit" value="Subir archivo" />
                </form>
            </div>
            <div class="col-md-3">
                <label>Archivos Subidos</label>
                <?php 
                    while ($archivo = $download->read())
                        {   
                            if ($archivo != '.' && $archivo != '..' ) {
                            // echo "<a href='/admin/Nutricion/".$user->name."/".$archivo."' download>".$archivo."</a>.<br>";
                            echo "<a href='/admin/nutricion/nutri/download/".$user->name."/".$archivo."' download>".$archivo."</a>.<br>";
                        }
                    } 
                ?>
            </div>
    	</div>
        
    </div>
    <div style="clear: both;"></div>
    
    <div style="clear: both;"></div>
    <div class="col-md-12" id="tabla">
    <?php $informes = \App\FisicCheck::where('id_user',$id)->get() ?>
        <?php if (count($informes) > 0): ?>
            <?php  $resumenes = \App\FisicCheck::where('id_user',$id)->orderBy('id','ASC')->first();
            
            if(count($resumenes)> 0){
                $age = $resumenes->age;
                $height = $resumenes->height;
                $objetive = $resumenes->objetive;
                $weight = $resumenes->weight;
            }else{
                $age = "";
                $height = ""; 
                $objetive = "";
                $weight = "";

            }
            $resumen2 = \App\FisicCheck::where('id_user',$id)->orderBy('id','DSEC')->first();
            if (count($resumen2) > 0) {
                $actualWeight = $resumen2['weight'];
            }else{
              $actualWeight = 0; 
            }

            $chequeos = \App\FisicCheck::where('id_user',$id)->get();
            $fechas = array();
            if (count($chequeos) > 0) {
               foreach ($chequeos as $key => $chequeo){
                    $fecha = Carbon::createFromFormat('Y-m-d H:i:s',$chequeo->cita->date);
                    $fechas[$key] = "'".$fecha->format('d-m-Y')."',";;
                }
            }
                ?>
            @include('admin.usuarios._informe-canvas',[
                                                            'objetive' => $objetive,
                                                            'weight' => $weight,
                                                            'actualWeight' => $actualWeight,
                                                            'fechas' => $fechas,
                                                            'chequeos' => \App\FisicCheck::where('id_user',$id)->get(),
                ]) 
        <?php else: ?>
        <?php endif ?>
        
    </div>
    
</div>
<script type="text/javascript">

    $(document).ready(function() {
        var pulsaciones = 0;
        $(".numeros").click(function(event) {
            pulsaciones = 0;
        });
        $(".numeros").keypress(function(e) {
                if ((e.which == 44 && pulsaciones == 1)||(e.which == 46 && pulsaciones == 1) ) {
                    return false;
                }else if((e.which == 44 && pulsaciones == 0)||(e.which == 46 && pulsaciones == 0)){
                    pulsaciones = 1;
                }                             
        });



        // funciones
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

            var hidePassBook = 1;
            
            $('.fecha').click(function(){
                var id = $(this).attr('data-date');

                if($('#'+id).is(":visible"))
                    {
                        hidePassBook = 0;
                        $('#'+id).hide();
                    }
                
                else
                    {
                        hidePassBook = 1;
                        $('#'+id).show(); 
                    }
           
            });

            $('.editable').click(function() {
                var id         = $(this).attr('data-id');
                var comentario = $('.comentario-'+id).val();

                var age        = $('.age-'+id).val();
                var height     = $('.height-'+id).val();
                var weight     = $('.weight-'+id).val();
                var objetive   = $('.objetive-'+id).val();
                var basal      = $('.basal-'+id).val();
                var coach = $(this).attr('coach-id');
                var user = $(this).attr('user-id');
                
                $.get('/admin/nutricion/nutri/newInforme', {  id: id,coach: coach, user: user, comentario: comentario ,age: age,height: height, weight: weight, objetive: objetive, basal: basal}, function(data) {
                    alert(data);
                    $('#tabla').empty().load('/admin/nutricion/nutri/canvas/'+user);
                });
            });


             
             
            


    });



</script>