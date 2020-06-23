<?php defined('SYSPATH') or die('No direct script access.') ?>

<div class="row-fluid">
	<div class="span5">

		<h3>
			Заказ №<?= $obj->number ?>,
			<small><?= Date::d_m_Y($obj->created) ?>, <?= Date::format($obj->created, DATE::TIME)?></small>
		</h3>
		<?= Form::open('') ?>
			<table class="table table-bordered">
				<colgroup>
					<col width="130">
					<col>
				</colgroup>
				<tr>
					<td><b>Ф.И.О.</b></td>
					<td><?= $obj->customer->fio ?></td>
				</tr>
				<tr>
					<td><b>Телефон</b></td>
					<td><?= Format::mobile($obj->customer->phone) ?></td>
				</tr>
				<?php if ($obj->customer->email): ?>
					<tr>
						<td><b>Email</b></td>
						<td><?= $obj->customer->email ?></td>
					</tr>
				<?php endif ?>

				<?php if ($obj->delivery_id == 1):      // Новая почта ?>
					<tr>
						<td><b>Доставка</b></td>
						<td>«Новая Почта»</td>
					</tr>
					<tr>
						<td><b>Город</b></td>
						<td><?= $obj->city ?></td>
					</tr>
					<tr>
						<td><b>Адрес</b></td>
						<td><?= $obj->address ?></td>
					</tr>
				<?php elseif ($obj->delivery_id == 2):  // Укрпочта ?>
					<tr>
						<td><b>Доставка</b></td>
						<td>«Укрпочта»</td>
					</tr>
					<tr>
						<td><b>Область</b></td>
						<td><?= $obj->oblast ?></td>
					</tr>
					<tr>
						<td><b>Район</b></td>
						<td><?= $obj->region ?></td>
					</tr>
					<tr>
						<td><b>Город</b></td>
						<td><?= $obj->city ?></td>
					</tr>
					<tr>
						<td><b>Адрес</b></td>
						<td><?= $obj->address ?></td>
					</tr>
					<tr>
						<td><b>Индекс</b></td>
						<td><?= $obj->postcode ?></td>
					</tr>
				<?php endif ?>

				<tr>
					<td><b>Статус</b></td>
					<td>
						<?= Form::select('status_id',
							ORM::factory('Shop_Order_Status')->find_for_select('id', 'title'),
							$obj->status->id
						) ?>
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td>
						<button type="submit" class="btn btn-primary">
							<i class="icon-save icon-white"></i>
							Сохранить
						</button>
					</td>
				</tr>

			</table>
		<?= Form::close() ?>

	</div>
	<div class="span7">

		<h3>Заказанные товары</h3>
		<table class="table table-bordered">
			<tr>
				<th>Название</th>
				<th>Количество</th>
				<th>Цена</th>
				<th>Всего</th>
			</tr>
			<?php foreach ($products as $product): ?>
				<tr>
					<td>
						<?= HTML::anchor(
							Route::url('shop_product', ['slug' => $product->slug]),
							$product->title . ' <i class="icon-share-alt"></i>',
							['target' => '_blank']
						) ?>
					</td>
					<td><?= $product->qty ?></td>
					<td><?= Format::price($product->price) ?></td>
					<td><?= Format::price($product->subtotal) ?></td>
				</tr>
			<?php endforeach ?>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td style="white-space: nowrap;"><h4>Сумма заказа</h4></td>
				<td><h4><?= Format::price($obj->total) ?></h4></td>
			</tr>
		</table>

	</div>
</div>
