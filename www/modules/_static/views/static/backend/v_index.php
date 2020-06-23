<?php defined('SYSPATH') or die('No direct script access.');

if ($pagination->total_items > 0)
{
	// Create table body
	$table_body = array();
	foreach ($obj as $o)
	{
		$table_body[] = array(
			$o->id,
			$o->fimage_static
				? HTML::if_image(UPLOAD_URL.$config_image['path_to_img'].$config_image['thumbnails'][0]['prefix'].$o->fimage_static, array('width' => '20px'))
				: TB_Helpers::dotted(),
			TB_Helpers::anchor_edit(ADMIN.'/static/edit/'.$o->id, $o->title),
			TB_Helpers::anchor_blank($o->slug, $o->slug),
			TB_Helpers::btn_enabled($o->enabled, $o->id),
			TB_Helpers::btn_actions('static', $o->id)
		);
	}

	echo TB_Helpers::btn_top('static', NULL,
		array(
			'add'   => __('static.page_add'),
			'reset' => __('app.sort_reset')
		));

	// Create table
	echo TB_Table::open();
	echo TB_Table::colgroup(array(50, 31, 0, 0, 0, 0));
	echo TB_Table::headers_sorter(
		array(
			'id'      => array('number', '#id'),
			'_fimage' => array('none'  , '<i class="icon-picture icon-large"></i>'),
			'title'   => array('string', __('app.title')),
			'slug'    => array('string', __('app.link')),
			'enabled' => array('select', __('app.status')),
			'_action' => array('none'  , __('app.actions'))
		)
	);
	echo TB_Table::body($table_body);
	echo TB_Table::close();

	echo $pagination;
}
else
	echo TB_Helpers::btn_top('static', NULL, array('add' => __('static.page_add')));

echo TB_Badge::info(__('static.total_pages').': '.$pagination->total_items);
