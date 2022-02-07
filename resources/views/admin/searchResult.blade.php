<ul class="list-result">
	<li class="li-header">Usuarios</li>
	<?php if (count($usersSearched) > 0): ?>
		<?php foreach ($usersSearched as $key => $userSearched): ?>
			<li>
				<a href="<?php echo url('/admin/usuarios/actualizar') ?>/<?php echo $userSearched->id ?>">
					<i class="fa fa-user"></i> <?php echo $userSearched->name ?>
				</a>
			</li>
		<?php endforeach ?>
	<?php else: ?>
		<li>
			<h5 class="text-muted" style="padding: 15px 0;">
				No hay usuarios que coincidan
			</h5>
		</li>
	<?php endif ?>
	<li class="li-header">Clases</li>
	<?php if (count($clasesSearched) > 0): ?>
		<?php foreach ($clasesSearched as $key => $claseSearched): ?>
			<li>
				<a href="<?php echo url('/admin/clases/actualizar') ?>/<?php echo $claseSearched->id ?>">
					<i class="fa fa-clock-o"></i> <?php echo $claseSearched->name ?>
				</a>
			</li>
		<?php endforeach ?>
	<?php else: ?>
		<li>
			<h5 class="text-muted" style="padding: 15px 0;">
				No hay clases que coincidan
			</h5>
		</li>
	<?php endif ?>
	<li class="li-header">Tarifas</li>
	<?php if (count($taxesSearched) > 0): ?>
		<?php foreach ($taxesSearched as $key => $taxeSearched): ?>
			<li>
				<a href="<?php echo url('/admin/tarifas/actualizar') ?>/<?php echo $taxeSearched->id ?>">
					<i class="fa fa-money"></i> <?php echo $taxeSearched->nombre ?>
				</a>
			</li>
		<?php endforeach ?>
	<?php else: ?>
		<li>
			<h5 class="text-muted" style="padding: 15px 0;">
				No hay tarifas que coincidan
			</h5>
		</li>
	<?php endif ?>
</ul>
