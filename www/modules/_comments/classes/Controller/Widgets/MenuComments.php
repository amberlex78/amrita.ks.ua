<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Widgets_MenuComments
 */
class Controller_Widgets_MenuComments extends Controller_Widgets
{
	public function action_index()
	{
		$menu = array(
			'heading' => 'comments.comments',
			'icon'    => 'comments',
			'menu'    => array(
				array(
					'title'      => 'comments.comments',
					'controller' => 'comments',
					'action'     => 'list',
					'icon'       => 'list',
				),
				array(
					'title'      => 'app.settings_module',
					'controller' => 'comments',
					'action'     => 'settings',
					'icon'       => 'wrench',
				),
				array(
					'title'      => 'app.i18n_edit_iface',
					'controller' => 'comments',
					'action'     => 'i18n',
					'icon'       => 'flag',
				),
				array('title'    => 'hr'),
				array(
					'title'      => 'comments.vk',
					'controller' => 'comments',
					'action'     => 'vk',
					'icon'       => 'vk',
				),
				array(
					'title'      => 'comments.fb',
					'controller' => 'comments',
					'action'     => 'fb',
					'icon'       => 'facebook',
				),
			)
		);

		$this->response->body(json_encode($menu));
	}
}