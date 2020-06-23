<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Widgets_MenuUser
 */
class Controller_Widgets_MenuUser extends Controller_Widgets
{
	public function action_index()
	{
		$menu = array(
			'heading' => 'user.users',
			'icon'    => 'group',
			'menu'    => array(
				array(
					'title'      => 'user.user_manager',
					'controller' => 'user',
					'action'     => 'index',
					'icon'       => 'list',
				),
				array(
					'title'      => 'user.user_add',
					'controller' => 'user',
					'action'     => 'add',
					'icon'       => 'plus',
				),
				/*
				array(
					'title'      => 'app.i18n_edit_iface',
					'controller' => 'user',
					'action'     => 'i18n',
					'icon'       => 'flag',
				),
				*/
			)
		);

		$this->response->body(json_encode($menu));
	}
}