<div class="block-header bg-primary-dark">
	<ul class="block-options">
		<li>
			<button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
		</li>
	</ul>
	<h3 class="block-title">Desgloce <?php foreach ($clases as $key => $clase): ?><?php echo $clase->classes->name ?> <?php endforeach ?></h3>
</div>
<div class="block-content">
	<table class="table table-borderless table-striped table-vcenter">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-left">Concepto</th>
				<th class="text-center">fecha clase</th>
				<th class="text-right">Total</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($clases as $key => $clase): ?>
				<tr>
					<td class="text-center"><strong><?php echo $key+1 ?></strong></td>
					<td class="text-left font-s18">
						<?php echo strtoupper($clase->classes->name) ?>
					</td>
					<td class="text-center font-s18">
						<?php echo date('d-m-Y h:00 A', strtotime($clase->date)) ?>
					</td>
					<td class="text-right font-s20"><strong><?php echo $tax->ppc ?>€</strong></td>
				</tr>
			<?php endforeach ?>

		</tbody>
	</table>
</div>