<?php defined('SYSPATH') or die('No direct script access.');

if ($total_items > 0) {

	// Create table body
	$table_body = [];
	foreach ($obj as $o) {
		$table_body[] = [
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
			HTML::anchor(
				Route::url('backend', ['controller' => 'customers', 'action' => 'view', 'id' => $o->customer->id]),
				$o->customer->fio, [
					'rel' => 'tooltip',
					'data-original-title' => 'Профиль и заказы клиента',
				]
			),
			Format::mobile($o->customer->phone),
			TB_Helpers::btn_actions('orders', $o->id),
		];
	}


	// Create table
	echo TB_Table::open();
	echo TB_Table::colgroup([100, 0, 0, 0, 0, 0, 0]);
	echo TB_Table::headers_sorter(
		[
			'number'  => ['string', __('Номер')],
			'total'   => ['string', __('Сумма')],
			'created' => ['string', __('Дата заказа')],
			'_status' => ['none',   __('Статус')],
			'_fio'    => ['none',   __('Клиент')],
			'_phone'  => ['none',   __('Телефон')],
			'_action' => ['none',   __('app.actions')],
		]
	);
	echo TB_Table::body($table_body);
	echo TB_Table::close();

	echo '<span class="badge badge-info">Всего заказов: ' . $total_items . '</span>';

	if (isset($pg)) {
		echo $pg;
	}
} else { ?>

	<div class="alert alert-block alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Заказов нет.
	</div>

<?php } ?>