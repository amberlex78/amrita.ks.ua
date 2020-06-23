<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Widgets_MenuTags
 */
class Controller_Widgets_MenuTags extends Controller_Widgets
{
	public function action_index()
	{
		$menu = array(
			'heading' => 'tags.tags',
			'icon'    => 'tags',
			'menu'    => array(
				array(
					'title'      => 'tags.tag_manager',
					'controller' => 'tags',
					'action'     => 'index',
					'icon'       => 'list',
				),
				array(
					'title'      => 'tags.tag_add',
					'controller' => 'tags',
					'action'     => 'add',
					'icon'       => 'plus',
				),
				array(
					'title'      => 'app.i18n_edit_iface',
					'controller' => 'tags',
					'action'     => 'i18n',
					'icon'       => 'flag',
				),
			)
		);

		$this->response->body(json_encode($menu));
	}
}