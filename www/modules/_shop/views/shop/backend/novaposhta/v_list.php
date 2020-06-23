<?php defined('SYSPATH') or die('No direct script access.');

if ($total_items > 0) {

	$table_body = [];
	foreach ($obj as $o) {
		$table_body[] = [
			HTML::anchor('http://novaposhta.ua/office/list?city=' . $o->DescriptionRu, $o->DescriptionRu, [
				'target'              => '_blank',
				'rel'                 => 'tooltip',
				'data-original-title' => 'Отделения в населенном пункте',
			]),
		];
	}

	echo '<div class="btns-top">';
	echo TB_Button::link(ADMIN . '/novaposhta/npupdate', ' Обновить базу для Новой Почты', ['class' => 'btn-small'])->with_icon('refresh');
	echo '</div>';

	// Create table
	echo TB_Table::open();
	echo TB_Table::headers_sorter([
		'title' => ['string', __('Населенный пункт')],
	]);
	echo TB_Table::body($table_body);
	echo TB_Table::close();

	echo '<span class="badge badge-info">Всего населенных пунктов: ' . $total_items . '</span>';

	if (isset($pg)) {
		echo $pg;
	}
}