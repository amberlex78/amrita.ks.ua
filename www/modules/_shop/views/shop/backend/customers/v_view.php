<?php defined('SYSPATH') or die('No direct script access.') ?>

<div class="row-fluid">

	<div class="span4">
		<h3>
			<i class="icon icon-user"></i>
			Клиент
		</h3>
		<table class="table table-bordered">
			<tr>
				<th>Ф.И.О.</th>
				<td><?= $obj->fio ?></td>
			</tr>
			<tr>
				<th>Телефон</th>
				<td><?= Format::mobile($obj->phone) ?></td>
			</tr>
			<?php if ($obj->email): ?>
				<tr>
					<th>Email</th>
					<td><?= $obj->email ?></td>
				</tr>
			<?php endif ?>
			<!--tr>
				<th> </th>
				<td>
					<?= HTML::anchor(
						Route::url('backend', ['controller' => 'customers', 'action' => 'edit', 'id' => $obj->id]),
						'Редактировать профиль'
					) ?>
				</td>
			</tr-->
		</table>

	</div>
	<div class="span8">

		<h3>Заказы клиента</h3>
		<?php
		// Create table body
		$table_body = [];
		foreach ($orders as $o) {
			$table_body[] = [
				$o->id,
				HTML::anchor(
					Route::url('backend', ['controller' => 'orders', 'action' => 'edit', 'id' => $o->id]),
					'№' . $o->number, [
						'rel' => 'tooltip',
						'data-original-title' => 'Редактировать заказ',
					]
				),
				Format::price($o->total),
				Date::format($o->created, Date::FULL),
				$o->status->get_title(),
				TB_Helpers::btn_actions('orders', $o->id),
			];
		}

		// Create table
		echo TB_Table::open();
		echo TB_Table::colgroup([50, 100, 0, 0, 0]);
		echo TB_Table::headers_sorter(
			[
				'id'      => ['string', __('#ID')],
				'number'  => ['string', __('Номер')],
				'total'   => ['string', __('Сумма')],
				'created' => ['string', __('Дата заказа')],
				'_status' => ['none',   __('Статус')],
				'_action' => ['none',   __('app.actions')],
			]
		);
		echo TB_Table::body($table_body);
		echo TB_Table::close();
		?>

		<span class="badge badge-info">
			Заказов: <?= $total_items ?>
		</span>
		<br>
		<span class="badge badge-info">
			Cумма заказов: <?= Format::price($total_sum) ?>
		</span>

	</div>
</div>
