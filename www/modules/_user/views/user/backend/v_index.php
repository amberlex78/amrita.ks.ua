<?php defined('SYSPATH') or die('No direct script access.');

if ($pagination->total_items > 0)
{
	// Create table body
	$table_body = array();
	foreach ($obj as $o)
	{
		$table_body[] = array(
			$o->id,
			TB_Helpers::anchor_edit(ADMIN.'/user/edit/'.$o->id, $o->username),
			$o->first_name.' '.$o->last_name,
			$o->email,
			TB_Helpers::btn_actions('user', $o->id)
		);
	}

	echo TB_Helpers::btn_top('user', NULL, array('add' => __('user.user_add'), 'reset' => __('Reset sort')));

	// Create table
	echo TB_Table::open();
	echo TB_Table::colgroup(array(50, 0, 0, 0, 0));
	echo TB_Table::headers_sorter(
		array(
			'id'         => array('number', '#id'),
			'username'   => array('string', __('user.login')),
			'first_name' => array('string', __('user.name')),
			'email'      => array('string', __('user.email')),
			'_action'    => array('none'  , __('app.actions'))
		)
	);
	echo TB_Table::body($table_body);
	echo TB_Table::close();

	echo $pagination;
}
else
	echo TB_Helpers::btn_top('user', NULL, array('add' => __('user.user_add')));

echo TB_Badge::info(__('user.total_users').': '.$pagination->total_items);
