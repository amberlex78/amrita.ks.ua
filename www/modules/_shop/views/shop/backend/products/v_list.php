<?php defined('SYSPATH') or die('No direct script access.');

if ($pagination->total_items > 0) {
	// Create table body
	$table_body = [];
	foreach ($obj as $o) {
		$table_body[] = [
			$o->fimage_product
				? HTML::if_image(UPLOAD_URL . $config_image['path_to_img'] . $config_image['thumbnails'][0]['prefix'] . $o->fimage_product, ['width' => '42px'])
				: TB_Helpers::dotted(),
			TB_Helpers::anchor_edit(ADMIN . '/products/edit/' . $o->id, $o->title)
			. '<br><small>'
			. TB_Helpers::anchor_blank(Route::url('shop_product', ['slug' => $o->slug]), $o->slug)
			. '</small>',
			Format::price($o->price),
			$o->category_anchors(),
			TB_Helpers::btn_enabled($o->enabled, $o->id),
			TB_Helpers::btn_actions('products', $o->id),
		];
	}

	// Top buttons
	echo
		TB_Form::inline_open('', ['id' => 'filter_by_category']) .
		TB_Helpers::btn_top('products', null, ['add' => __('shop.product_add'), 'reset' => __('app.sort_reset')],
			false) .
		'<div class="pull-right">' .
		Form::label('id', __('shop.category_filter')) . ' ' .
		Form::select('id', $for_select, $selected) .
		'</div>' .
		TB_Form::close();

	// Create table
	echo TB_Table::open();
	echo TB_Table::colgroup([58, 600, 0, 0, 0, 0]);
	echo TB_Table::headers_sorter(
		[
			'_fimage' => ['none', '<i class="icon-picture icon-large"></i>'],
			'title'   => ['string', __('shop.product_title')],
			'price'   => ['string', __('shop.product_price')],
			'_slug'   => ['none', __('shop.categories')],
			'enabled' => ['select', __('app.status')],
			'_action' => ['none', __('app.actions')],
		]
	);
	echo TB_Table::body($table_body);
	echo TB_Table::close();

	echo $pagination;
} else {
	// Top buttons
	echo
		TB_Form::inline_open('', ['id' => 'filter_by_category']) .
		TB_Helpers::btn_top('products', null, ['add' => __('shop.product_add')], false) .
		'<div class=" pull-right">' .
		Form::label('id', __('shop.category_filter')) . ' ' .
		Form::select('id', $for_select, $selected) .
		'</div>' .
		TB_Form::close();
}

echo TB_Badge::info(__('shop.product_total') . ': ' . $pagination->total_items);
