<?php defined('SYSPATH') or die('No direct script access.');

if ($pg->total_items > 0) {

	// Create table body
	$table_body = [];
	foreach ($obj as $o) {
		$table_body[] = [
			HTML::anchor(
				Route::url('backend', ['controller' => 'customers', 'action' => 'edit', 'id' => $o->id]),
				$o->fio, [
					'rel' => 'tooltip',
					'data-original-title' => 'Редактировать профиль',
				]
			),
			Format::mobile($o->phone),
			$o->email,
			HTML::anchor(
				Route::url('backend', ['controller' => 'customers', 'action' => 'view', 'id' => $o->id]),
				'<span class="badge badge-info"> ' .
				$o->orders->count_all() . '&nbsp;&nbsp;' . '<i class="icon icon-eye-open"></i>' .
				'</span>', [
					'rel' => 'tooltip',
					'data-original-title' => 'Профиль и заказы клиента',
				]
			),
			Format::price(Model_Shop_Customer::total($o->id)),
			TB_Helpers::btn_actions('customers', $o->id),
		];
	}


	// Create table
	echo TB_Table::open();
	echo TB_Table::colgroup([0, 0, 0, 0, 0, 0]);
	echo TB_Table::headers_sorter(
		[
			'fio'     => ['string', __('Клиент')],
			'phone'   => ['string', __('Телефон')],
			'email'   => ['string', __('Email')],
			'_orders' => ['none', __('Заказы')],
			'_total'  => ['none', __('На сумму')],
			'_action' => ['none', __('app.actions')],
		]
	);
	echo TB_Table::body($table_body);
	echo TB_Table::close();

	echo '<span class="badge badge-info">Всего клиентов: ' . $pg->total_items . '</span>';

	echo $pg;
} else { ?>

	<div class="alert alert-block alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Клиентов нет.
	</div>

<?php } ?>