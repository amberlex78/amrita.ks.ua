<?php defined('SYSPATH') or die('No direct script access.')
/**
 * @var $cart Cart - Shopping Cart Class
 */
?>

<h1>Моя корзина</h1>

<?php if ($cart->total_items() > 0): ?>

	<?= Form::open(Route::url('shop_cart', ['action' => 'update']), ['class' => 'form']) ?>

		<table class="table">
			<colgroup>
				<col>
				<col width="130">
				<col>
				<col>
				<col>
			</colgroup>
			<tr>
				<th>Название товара</th>
				<th>Количество</th>
				<th class="text-right">Цена за единицу</th>
				<th class="text-right">Всего</th>
				<th class="text-right"></th>
			</tr>
			<?php $i = 1 ?>
			<?php foreach ($cart->contents() as $item): ?>
				<tr>
					<td>
						<i class="fa fa-check-square-o ch"></i>
						<?= HTML::anchor(Route::url('shop_product', ['slug' => $item['slug']]), $item['name']) ?>
					</td>
					<td>
						<?= Form::hidden($i . '[rowid]', $item['rowid']) ?>
						<?= Form::input($i . '[qty]', $item['qty'], ['class' => 'form-control']) ?>
					</td>
					<td class="text-right"><?= Format::price($item['price']) ?></td>
					<td class="text-right"><?= Format::price($item['subtotal']) ?></td>
					<td class="text-right">
						<a href="<?= Route::url('shop_cart', ['action' => 'delete', 'rowid' => $item['rowid']]) ?>" class="text-danger">
							<i class="fa fa-minus-circle"></i>
						</a>
					</td>
				</tr>
				<?php $i++ ?>
			<?php endforeach ?>
			<tr>
				<td> </td>
				<td>
					<button type="submit" class="btn btn-default">
						<i class="fa fa-refresh fa-fw"></i>
						Пересчитать
					</button>
				</td>
				<td class="text-right"><h4>Итого:</h4></td>
				<td class="text-right"><h4><?php echo Format::price($cart->total()) ?></h4></td>
				<td> </td>
			</tr>
			<tr>
				<td class="text-right" colspan="5">
					<a href="<?= Route::url('shop_cart', ['action' => 'checkout']) ?>" class="btn btn-success btn-lg">
						Оформление заказа
					</a>
				</td>
			</tr>
		</table>

	<?= Form::close() ?>

<?php else: ?>

	<div class="alert alert-success">
		Товаров нет
	</div>

<?php endif ?>

