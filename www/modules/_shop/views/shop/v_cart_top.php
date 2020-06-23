<?php defined('SYSPATH') or die('No direct script access.')
/**
 * @var $cart Cart - Shopping Cart Class
 */
?>

<a href="<?= Route::url('shop_cart') ?>" class="btn btn-default">
	<div style="float: left;">
		<i class="fa fa-shopping-cart fa-2x ch"></i>
	</div>
	<div style="float: right; padding-left: 10px;" class="text-left">
		<span class="text-success">Показать корзину</span>
		<br>
		<span class="text-muted">
			<small>
				<?php if ($cart->total_items() > 0): ?>
					<?= $cart->total_items() ?>
					&nbsp;<?= Inflector::num_2_word($cart->total_items(), $cart->words) ?>
					&nbsp;&ndash;
					&nbsp;<?= Format::price($cart->total()) ?>
				<?php else: ?>
					товаров нет
				<?php endif ?>
			</small>
		</span>
	</div>
</a>
