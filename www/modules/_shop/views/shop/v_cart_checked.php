<?php defined('SYSPATH') or die('No direct script access.')
/**
 * @var $cart                Cart - Shopping Cart Class
 * @var $obj_order           Model_Shop_Order
 * @var $obj_order_products  Model_Shop_Order_Product
 */
?>

<div class="row">
	<div class="col-md-5">
		<h2>Заказ №&nbsp;<?= $obj_order->number ?></h2>
		<table class="table">
			<colgroup>
				<col>
				<col width="100%">
			</colgroup>
			<tr>
				<td><strong>Имя:</strong></td>
				<td><?= $obj_order->customer->fio ?></td>
			</tr>
			<tr>
				<td><strong>Телефон:</strong></td>
				<td><?= Format::mobile($obj_order->customer->phone) ?></td>
			</tr>
			<?php if ($obj_order->customer->email): ?>
				<tr>
					<td><strong>Email:</strong></td>
					<td><?= $obj_order->customer->email ?></td>
				</tr>
			<?php endif ?>
			<tr>
				<td><strong>Дата&nbsp;заказа:</strong></td>
				<td><?= Date::format($obj_order->created, Date::FULL) ?></td>
			</tr>
		</table>
	</div>
	<div class="col-md-7">
		<h2>Мой заказ</h2>
		<table class="table table-bordered">
			<colgroup>
				<col width="100%">
				<col>
				<col>
				<col>
			</colgroup>
			<tr>
				<th>Название товара</th>
				<th class="text-right">Количество</th>
				<th class="text-right">Цена</th>
				<th class="text-right">Всего</th>
			</tr>
			<?php $i = 1 ?>
			<?php foreach ($obj_order_products as $item): ?>
				<tr>
					<td>
						<i class="fa fa-check-square-o ch"></i>
						<?= HTML::anchor(Route::url('shop_product', ['slug' => $item->slug]), $item->title) ?>
					</td>
					<td class="text-right"><?= $item->qty ?></td>
					<td class="text-right"><?= Format::price($item->price) ?></td>
					<td class="text-right"><?= Format::price($item->subtotal) ?></td>
				</tr>
				<?php $i++ ?>
			<?php endforeach ?>
			<tr>
				<td colspan="3" class="text-right"><h4>Итого:</h4></td>
				<td class="text-right"><h4><?php echo Format::price($obj_order->total) ?></h4></td>
			</tr>
		</table>
	</div>
</div>
