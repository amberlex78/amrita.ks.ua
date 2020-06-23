<?php defined('SYSPATH') or die('No direct script access.');

$count = count($obj);

if ($count > 0)
{
	// Create table body
	$table_body = array();
	foreach ($obj as $o)
	{
		$table_body[] = array(
			$o->id == 2 ? $o->title : TB_Helpers::anchor_level($o->lvl, ADMIN.'/rubrics/edit/'.$o->id, $o->title),
			$o->id == 2 ? $o->slug  : TB_Helpers::anchor_blank(Route::url('blog_rubric', array('slug' => $o->slug)), $o->slug),
			TB_Helpers::btn_actions('rubrics', $o->id, array('up', 'down', 'edit', 'delete'))
		);
	}

	echo TB_Helpers::btn_top('rubrics', NULL, array('add' => __('blog.rubric_add')));

	// Create table
	echo TB_Table::open();
	echo TB_Table::colgroup(array(0, 0, 0));
	echo TB_Table::headers(
		array(
			__('app.name'),
			__('app.seo_slug'),
			__('app.actions')
		)
	);
	echo TB_Table::body($table_body);
	echo TB_Table::close();
}
else
	echo TB_Helpers::btn_top('rubric', NULL, array('add' => __('blog.rubric_add')));

if ($count)
	echo '<div class="alert alert-info"><small>'.__('blog.rubric_note_for_delete').'</small></div>';

echo TB_Badge::info(__('blog.rubric_total').': '.$count) ?>
