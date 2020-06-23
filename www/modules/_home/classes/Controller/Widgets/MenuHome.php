<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Widgets_MenuHome
 */
class Controller_Widgets_MenuHome extends Controller_Widgets
{
	public function action_index()
	{
		$menu = array(
			'heading' => 'home.page',
			'icon'    => 'home',
			'menu'    => array(
				array(
					'title'      => 'home.page_edit',
					'controller' => 'home',
					'action'     => 'edit',
					'icon'       => 'pencil',
				),
				/*
				array(
					'title'      => 'app.i18n_edit_iface',
					'controller' => 'home',
					'action'     => 'i18n',
					'icon'       => 'flag',
				),
				*/
			)
		);

		$this->response->body(json_encode($menu));
	}
}