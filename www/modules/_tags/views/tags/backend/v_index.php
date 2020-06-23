<?php defined('SYSPATH') or die('No direct script access.');

if ($pagination->total_items > 0)
{
	// Create table body
	$table_body = array();
	foreach ($obj as $o)
	{
		$table_body[] = array(
			$o->id,
			TB_Helpers::anchor_edit(ADMIN.'/tags/edit/'.$o->id, $o->name),
			TB_Helpers::anchor_blank(Route::url('tags', array('slug' => $o->slug)), $o->slug),
			TB_Helpers::btn_actions('tags', $o->id)
		);
	}

	echo TB_Helpers::btn_top('tags', NULL, array('add' => __('tags.tag_add'), 'reset' => __('app.sort_reset')));

	// Create table
	echo TB_Table::open();
	echo TB_Table::colgroup(array(50, 0, 0, 0, 0));
	echo TB_Table::headers_sorter(
		array(
			'id'      => array('number', '#id'),
			'name'    => array('string', __('tags.tag_name')),
			'slug'    => array('string', __('app.seo_slug')),
			'_action' => array('none'  , __('app.actions'))
		)
	);
	echo TB_Table::body($table_body);
	echo TB_Table::close();

	echo $pagination;
}
else
	echo TB_Helpers::btn_top('tags', NULL, array('add' => __('tags.tag_add')));

echo TB_Badge::info(__('tags.total_tags').': '.$pagination->total_items);
