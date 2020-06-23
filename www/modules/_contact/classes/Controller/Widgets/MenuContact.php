<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Widgets_MenuContacts
 */
class Controller_Widgets_MenuContact extends Controller_Widgets
{
	public function action_index()
	{
		$menu = array(
			'heading' => 'contact.contact',
			'icon'    => 'edit-sign',
			'menu'    => array(
				array(
					'title'      => 'contact.page_edit',
					'controller' => 'contact',
					'action'     => 'edit',
					'icon'       => 'pencil',
				),
				/*
				array(
					'title'      => 'app.i18n_edit_iface',
					'controller' => 'contact',
					'action'     => 'i18n',
					'icon'       => 'flag',
				),
				*/
			)
		);

		$this->response->body(json_encode($menu));
	}
}