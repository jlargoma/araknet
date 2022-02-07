<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>

<div class="col-md-6">

<canvas id="chartjs-0" class="chartjs" width="770" height="385" style="display: block; width: 770px; height: 385px;"></canvas>
    
</div>
<div class="col-md-6">
    <div class="col-md-4">
        <div class="block-content block-content-full bg-info">
            <div class="h1 font-w700 text-white"> <?php echo $weight ?><span class="h2 text-white-op">  Kg</span></div>
            <div class="h5 text-white-op text-uppercase push-5-t">Peso Inicial</div>
        </div> 
    </div>

    <div class="col-md-8">
        <div class="block-content block-content-full bg-info" style="background-color: orange">
            <div class="h1 font-w700 text-white"> <?php echo $actualWeight ?><span class="h2 text-white-op"> Kg</span></div>
            <div class="h5 text-white-op text-uppercase push-5-t">Peso Actual</div>
        </div> 
    </div> 
    <div style="clear: both;"></div><br>
    <div class="col-md-4">
        <div class="block-content block-content-full bg-info" style="background-color: green">
            <div class="h1 font-w700 text-white"> <?php echo $objetive ?><span class="h2 text-white-op"> Kg</span></div>
            <div class="h5 text-white-op text-uppercase push-5-t">Peso Ideal</div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="block-content block-content-full bg-info">
        <?php if ($weight-$actualWeight > 0): ?>
            <div class="h1 font-w700 text-white"> <?php echo ($weight-$actualWeight) ?><span class="h2 text-white-op"> Kg </style><b style="color: green;font-size: 39px"> &#8681; </b></span></div>
            <div class="h5 text-white-op text-uppercase push-5-t">Peso Perdido</div>
        <?php elseif ($weight-$actualWeight < 0): ?>
            <div class="h1 font-w700 text-white"> <?php echo ($weight-$actualWeight) ?><span class="h2 text-white-op"> Kg </style><b style="color: red;font-size: 39px">   &#8679;   </b></span></div>
            <div class="h5 text-white-op text-uppercase push-5-t">Peso Ganado</div>
        <?php else: ?>
            <div class="h1 font-w700 text-white"> <?php echo ($weight-$actualWeight) ?><span class="h2 text-white-op"> Kg </style></span></div>
            <div class="h5 text-white-op text-uppercase push-5-t">Peso Ganado</div>
        <?php endif ?>
        </div> 
    </div>
        <div class="col-md-4">
            <div class="block-content block-content-full bg-info">
                <div class="h1 font-w700 text-white" style="border-radius: 50px">
                <?php if ($actualWeight > 0 && $objetive > 0): ?>
                    <?php if ($actualWeight > $objetive): ?>
                        <?php echo number_format((($weight - $actualWeight)/($weight-$objetive))*100) ?><span class="h2 text-white-op"> %</span></div>
                    <?php else: ?>
                        100<span class="h2 text-white-op"> %</span></div>
                    <?php endif ?>
                <?php else: ?>
                    0%
                <?php endif ?>
                    
                <div class="h5 text-white-op text-uppercase push-5-t">De cumplimiento</div>
            </div> 

            
        </div>

</div>


<script type="text/javascript">
    $(document).ready(function() {


    	Chart.pluginService.register({
    	    afterDraw: function(chart) {
    	        if (typeof chart.config.options.lineAt != 'undefined') {
    	            var lineAt = chart.config.options.lineAt;
    	            var ctxPlugin = chart.chart.ctx;
    	            var xAxe = chart.scales[chart.config.options.scales.xAxes[0].id];
    	            var yAxe = chart.scales[chart.config.options.scales.yAxes[0].id];
    	            
    	            // I'm not good at maths
    	            // So I couldn't find a way to make it work ...
    	            // ... without having the `min` property set to 0
    	            if(yAxe.min != 0) return;
    	            
    	            ctxPlugin.strokeStyle = "green";
    	            ctxPlugin.beginPath();
    	            lineAt = (lineAt - yAxe.min) * (100 / yAxe.max);
    	            lineAt = (100 - lineAt) / 100 * (yAxe.height) + yAxe.top;
    	            ctxPlugin.moveTo(xAxe.left, lineAt);
    	            ctxPlugin.lineTo(xAxe.right, lineAt);
    	            ctxPlugin.stroke();
    	        }
    	    }
    	});

    	new Chart(document.getElementById("chartjs-0"),
    	    {   
    	        type: 'bar',
    	           data: {
    	               labels: 
    	                    [
    	                        <?php foreach ($fechas as $fecha): ?>
                                    <?php echo $fecha ?>
    	                        <?php endforeach ?>
    	                    ],

    	               datasets: [{
    	                   label: 'Peso',
    	                   data: 
    	                    [
    	                        <?php foreach ($chequeos as $chequeo): ?>
    	                            <?php 
    	                                echo "'";
    	                                print_r(number_format($chequeo->weight,2));
    	                                echo "',";
    	                             ?>
    	                        <?php endforeach ?>
    	                    ],
                        backgroundColor: [
                                            'rgba(112, 185, 253, 0.5)',

                                           <?php for ($i=0; $i <= count($chequeos)-3; $i++):?>
                                                'rgba(255,165,0,0.5)',
                                            <?php endfor ?>
                                            'rgba(255,165,0,1)',
                                        ],
    	               }]
    	           },
    	           options: {
    	               lineAt: <?php echo $objetive ?>,
    	               scales: {
    	                   yAxes: [{
    	                       ticks: {
    	                           min: 0,
                                   max:140,
    	                       }
    	                   }]
    	               }
    	           }
    	    }
    	);

    });



</script>