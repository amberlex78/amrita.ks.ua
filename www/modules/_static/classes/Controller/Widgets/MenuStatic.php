<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Widgets_MenuStatic
 */
class Controller_Widgets_MenuStatic extends Controller_Widgets
{
	public function action_index()
	{
		$menu = array(
			'heading' => 'static.pages',
			'icon'    => 'file',
			'menu'    => array(
				array(
					'title'      => 'static.page_manager',
					'controller' => 'static',
					'action'     => 'index',
					'icon'       => 'file-text',
				),
				array(
					'title'      => 'static.page_add',
					'controller' => 'static',
					'action'     => 'add',
					'icon'       => 'plus',
				),
				array(
					'title'      => 'app.i18n_edit_iface',
					'controller' => 'static',
					'action'     => 'i18n',
					'icon'       => 'flag',
				),
			)
		);

		$this->response->body(json_encode($menu));
	}
}