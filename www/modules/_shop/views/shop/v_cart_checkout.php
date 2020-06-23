<?php defined('SYSPATH') or die('No direct script access.')
/**
 * @var $cart      Cart - Shopping Cart Class
 * @var $customer  Model_Shop_Customer
 * @var $errors    array
 */
?>

<div class="row">
	<div class="col-md-5">

		<h2>Оформление заказа</h2>

		<?= Form::open(Route::url('shop_cart', ['action' => 'checkout']), ['class' => 'form-horizontal']) ?>

			<fieldset>

				<legend>
					<i class="fa fa-user text-muted"></i>
					Персональные данные
				</legend>

				<div class="form-group">
					<label for="fio" class="col-sm-2 control-label">Ф.И.О.</label>
					<div class="col-sm-10">
						<?= Form::input('fio', $obj_customer->fio, ['class' => 'form-control', 'placeholder' => 'Фамилия Имя Отчество', 'required']) ?>
						<?= Form::error('fio', $errors) ?>
					</div>
				</div>

				<div class="form-group">
					<label for="phone" class="col-sm-2 control-label">Телефон</label>
					<div class="col-sm-10">
						<?= Form::input('phone', $obj_customer->phone,
							['class' => 'form-control', 'placeholder' => 'Номер телефона', 'required']
						) ?>
						<?= Form::error('phone', $errors) ?>
					</div>
				</div>

				<div class="form-group">
					<label for="email" class="col-sm-2 control-label">Email</label>
					<div class="col-sm-10">
						<?= Form::input('email', $obj_customer->email,
							['class' => 'form-control', 'placeholder' => 'Email', 'type' => 'email', 'required']
						) ?>
						<?= Form::error('email', $errors) ?>
					</div>
				</div>

			</fieldset>
			<fieldset>

				<legend>
					<i class="fa fa-truck fa-flip-horizontal text-muted"></i>
					Адрес доставки
				</legend>

				<div class="form-group">
					<label for="delivery_id" class="col-sm-2 control-label">Доставка</label>
					<div class="col-sm-10">
						<?= Form::select('delivery_id', $sel_deliveries,
							$obj_order->delivery_id ? $obj_order->delivery_id : 1,
							['class' => 'form-control', 'id' => 'delivery_id', 'required']
						) ?>
						<?= Form::error('delivery_id', $errors) ?>
					</div>
				</div>

				<div class="delivery_address">
					<?= $v_delivery_address ?>
				</div>

			</fieldset>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success">
						Оформить заказ
					</button>
				</div>
			</div>

		<?= Form::close() ?>

	</div>
	<div class="col-md-7">

		<h2>Мой заказ</h2>
		<fieldset>

			<legend>
				<i class="fa fa-shopping-cart text-muted"></i>
				В корзине
			</legend>

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
				<?php foreach ($cart->contents() as $item): ?>
					<tr>
						<td>
							<i class="fa fa-check-square-o ch"></i>
							<?= HTML::anchor(Route::url('shop_product', ['slug' => $item['slug']]), $item['name']) ?>
						</td>
						<td class="text-right"><?= $item['qty'] ?></td>
						<td class="text-right"><?= Format::price($item['price']) ?></td>
						<td class="text-right"><?= Format::price($item['subtotal']) ?></td>
					</tr>
					<?php $i++ ?>
				<?php endforeach ?>
				<tr>
					<td colspan="3" class="text-right"><h4>Итого</h4></td>
					<td class="text-right"><h4><?= Format::price($cart->total()) ?></h4></td>
				</tr>
			</table>

			<?= HTML::anchor(
				Route::url('shop_cart'),
				'<i class="fa fa-shopping-cart fa-fw"></i> Показать корзину', ['class' => 'btn btn-default']
			) ?>

		</fieldset>

		<h2>Доставка и оплата</h2>
		<p><strong>Доставка может осуществляться:</strong></p>
		<ul>
			<li>Компанией «Новая Почта»<br>
				Посылка доставляется на склад «Новой почты», откуда ее можно будет забрать самовывозом.
			</li>
			<li>Предприятием почтовой связи «Укрпочта»<br>
				Посылка доставляются в указанное отделение, с которого ее можно будет забрать.
			</li>
		</ul>
		<p><strong>Оплата наложенным платежом</strong></p>
		<p>Оплата осуществляется наложенным платежом, т.е. при получении посылки.</p>
		<hr>

	</div>
</div>
