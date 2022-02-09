<?php if ($summary == 1): ?>
	<div class="heading-block center">
		<h2>Contrato</h2>
		<span>Revisa todo el contrato y acepta los terminos.</span>
	</div>

	<div class="col-xs-12 push-20" style="max-height: 300px; overflow-y: auto;">
<?php endif; ?>
	<p class="text-justify">
		<b>1.-ACCESO A LAS INSTALACIONES:</b> El titular del contrato tendrá derecho al acceso a las instalaciones dentro del horario de apertura y siempre según las condiciones de su tarifa seleccionada. Este contrato y sus condiciones son personales e intransferibles, no pudiendo aplicarse a ninguna persona externa excepto cuando se tratase de tarifas familiares, y se hubiesen aportado debidamente sus datos correspondientes. <br>
		Las sesiones de entrenamiento perdidas dentro de las diferentes cuotas podrán ser recuperadas dentro del mismo mes y durante el horario en que el centro permanezca abierto, siempre comunicándolo a un miembro del personal a la entrada. Dichas sesiones no podrán recuperarse en los meses siguientes. Es la obligación del usuario apuntarse en el sistema de control de acceso cada vez que vaya a iniciar una sesión.<br><br>
		<b>2.-MATRICULA:</b> Es un pago único con coste de 30€ al inicio de la inscripción. Dicha matricula da derecho al usuario a estar inscrito en las instalaciones, utilizar las toallas a su disposición, y recibir una valoración inicial.
		En caso de baja del contrato, el importe de la matrícula se guardará por un máximo de TRES MESES desde la fecha de suspensión. Si pasado ese plazo se desea realizar una nueva inscripción, será necesario abonar de nuevo la matrícula. <br><br>
		<b>3.-DURACIÓN DEL CONTRATO:</b> Ha sido libremente pactado entre las partes. En las tarifas trimestrales dicho plazo debe cumplirse salvo que existan causas legalmente justificadas o médicas, en cuyo caso la matrícula y la cuota podrán ser guardadas para futuras incorporaciones al centro. En el resto de escenarios, se consideraría incumplimiento del contrato y se perdería el derecho a reembolso o a la reserva de matrícula. <br><br>
		<b>4.-MODIFICACIONES DE TARIFAS O CONTRATO:</b> Se debe comunicar la finalización o renovación del contrato antes del día 20 de cada mes. En el caso de modificarse la tarifa por alguna otra de inferior o superior cuantía, deberá comunicarse en dicho plazo. No hacerlo llevará a continuar con el pago del mes habitual, hasta el próximo mes a la comunicación.
		Las domiciliaciones SEPA tendrán el mismo plazo de previo aviso, junto con las domiciliaciones de tarjeta bancaria.<br><br>
		<b>5.-REVISION DE LA CUOTA:</b> El importe de la cuota únicamente se revisará con carácter anual transcurrido un año desde su formalización, sea cual sea su duración, y con el único fin de aplicar al importe mensual que se venga abonando, la variación del IPC anual producida en los 12 meses anteriores.<br><br>
		<b>6.-BONOS DE PT Y FISIOTERAPIA:</b> Los bonos de PT y fisioterapia son independientes a las tarifas y cuotas mensuales del club, dichos bonos tendrán una duración máxima de 3 meses desde la compra de dicho bono, para los bonos de PT, las citas con el entrenador podrán ser anuladas con un plazo de 24 horas de antelación, o en su defecto para sesiones de tarde con un plazo de 6 horas mínimo, si dichos plazos se incumplen, dicha sesión se contará como dada y por lo tanto descontada de dicho bono. Para los bonos de fisioterapia se aplicarán los mismos plazos de anulación. Para las sesiones y bonos de fisioterapia, solo se aceptará metálico como único método de pago.<br><br>
		<b>7.-CITAS NUTRICIONISTA:</b> En las cuotas o tarifas que tengan incluidas una sesión mensual de nutricionista, la cita de dicha sesión deberá pactarse antes del día 20 de cada mes. De concluir este plazo sin haberse concretado, la cita se dará por cumplida y no podrá recuperarse en los siguientes meses.<br><br>
		<b>8.-PAGO CUOTAS:</b> Todas las tarifas deberán ser abonadas del 1 al 5 de cada mes, el retraso en dicho pago llevara a no poder acceder a las instalaciones, en el caso de los pagos SEPA si se diera el caso de devoluciones de recibos o retrasos en el mismo, el socio será penalizado con un importe de 3€, que se le aplicaría en el pago de la cuota del mes correspondiente.
		En el caso de los pagos domiciliados por tarjeta bancaria, se aplicará las mismas condiciones que en el resto de los métodos de pago, como también en los casos de retraso de pagos en el método de pago en metalico.<br><br>
		<b>10.-USO DEL MATERIAL:</b> El material se podrá utilizar siempre y cuando el entrenador lo autorice y esté bajo su supervisión. Si ocurriese cualquier tipo de accidente relacionado con dicho material y el entrenador no hubiese dado autorización, la responsabilidad legal será única y exclusivamente del usuario.<br><br>
		En caso de desperfecto, pérdida o incluso mal uso del material por parte del usuario, será responsabilidad del mismo su reposición o pago.<br>
	</p>
<?php if ($summary == 1): ?>
	</div>
<?php endif ?>
<div class="col-xs-12 push-20">
	<div class="col-md-6 col-xs-6">Nombre: </div>
	<div class="col-md-6 col-xs-6">{{ $customer->name }}</div>
</div>
<div class="col-xs-12 push-20">
	<div class="col-md-6 col-xs-6">Dirección</div>
	<div class="col-md-6 col-xs-6">
		@if( $customer->direccion != '')
			{{ $customer->direccion }}
		@else
			-------------
		@endif
	</div>
</div>
<div class="col-xs-12 push-20">
	<div class="col-md-6 col-xs-6">Teléfono</div>
	<div class="col-md-6 col-xs-6">
		@if( $customer->phone != '')
			{{ $customer->phone }}
		@else
			-------------
		@endif
	</div>
</div>
<div class="col-xs-12 push-20">
	<div class="col-md-6 col-xs-6">Email</div>
	<div class="col-md-6 col-xs-6">{{ $customer->email }}</div>
</div>
<div class="col-xs-12 push-20">
	<div class="col-md-6 col-xs-6">Tarifa contratada</div>
	<div class="col-md-6 col-xs-6">{{ $rate->name }}</div>
</div>
<div class="col-xs-12 push-20">
	<div class="col-md-6 col-xs-6">Tipo de cuota</div>
	<div class="col-md-6 col-xs-6">
		<?php 
			$typeRate = "";
			switch ($rate->mode) {
				case 1:
					$typeRate = "Mensual";
					break;
				case 3:
					$typeRate = "Trimestral";
					break;
				case 6:
					$typeRate = "Semestral";
					break;
				case 12:
					$typeRate = "Anual";
					break;
			}

			echo $typeRate;
		?>
	</div>
</div>
<div class="col-xs-12 push-20">
	<div class="col-md-6 col-xs-6">Método de pago</div>
	<div class="col-md-6 col-xs-6">
		Mediante tarjeta
	</div>
</div>
<?php if ($customer->contractAccepted == 0): ?>

	<div class="col-xs-12">
		<p class="text-justify font-w800" style="letter-spacing: -1px;">
			AUTORIZO A LA DOMICIALICION DE RECIBO MENSUAL O TRIMESTRAL DEL PAGO MEDIANTE TARJETA BANCARIA: 
		</p>
	</div>

	<div class="col-xs-12 text-center">
		<div class="col-md-12 text-center">
			@include('users._partials._formSuscription')
		</div>
	</div>
	
<?php endif ?>

