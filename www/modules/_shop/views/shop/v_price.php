<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1>Прайс</h1>

<table class="table table-bordered">
	<tr>
		<th>Название</th>
		<th>Цена</th>
	</tr>
	<?php foreach ($products as $product): ?>
		<tr>
			<td>
				<i class="fa fa-check-square-o ch"></i>
				<?= HTML::anchor(Route::url('shop_product', array('slug' => $product->slug)), $product->title) ?>
			</td>
			<td><?= Format::price($product->price) ?></td>
		</tr>
	<?php endforeach ?>
</table>
