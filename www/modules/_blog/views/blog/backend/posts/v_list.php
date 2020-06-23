<?php defined('SYSPATH') or die('No direct script access.');

if ($pagination->total_items > 0) {
	// Create table body
	$table_body = [];
	foreach ($obj as $o) {
		$table_body[] = [
			$o->fimage_post
				? HTML::if_image(UPLOAD_URL . $config_image['path_to_img'] . $config_image['thumbnails'][0]['prefix'] . $o->fimage_post,
				['width' => '42px'])
				: TB_Helpers::dotted(),
			TB_Helpers::anchor_edit(ADMIN . '/posts/edit/' . $o->id, $o->title)
			. '<br><small>'
			. TB_Helpers::anchor_blank(Route::url('blog_post', ['slug' => $o->slug]), $o->slug)
			. '</small>',
			$o->rubric_anchors(),
			TB_Helpers::btn_enabled($o->enabled, $o->id),
			TB_Helpers::btn_actions('posts', $o->id),
		];
	}

	// Top buttons
	echo
		TB_Form::inline_open('', ['id' => 'filter_by_rubric']) .
		TB_Helpers::btn_top('posts', null, ['add' => __('blog.post_add'), 'reset' => __('app.sort_reset')], false) .
		'<div class="pull-right">' .
		Form::label('id', __('blog.rubric_filter')) . ' ' .
		Form::select('id', $for_select, $selected) .
		'</div>' .
		TB_Form::close();

	// Create table
	echo TB_Table::open();
	echo TB_Table::colgroup([58, 1000, 0, 0, 0]);
	echo TB_Table::headers_sorter(
		[
			'_fimage' => ['none', '<i class="icon-picture icon-large"></i>'],
			'title'   => ['string', __('blog.post_title')],
			'_slug'   => ['none', __('blog.rubrics')],
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
		TB_Form::inline_open('', ['id' => 'filter_by_rubric']) .
		TB_Helpers::btn_top('posts', null, ['add' => __('blog.post_add')], false) .
		'<div class=" pull-right">' .
		Form::label('id', __('blog.rubric_filter')) . ' ' .
		Form::select('id', $for_select, $selected) .
		'</div>' .
		TB_Form::close();
}

echo TB_Badge::info(__('blog.post_total') . ': ' . $pagination->total_items);
