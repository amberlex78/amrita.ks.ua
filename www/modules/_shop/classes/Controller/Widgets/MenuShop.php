<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Widgets_MenuShop
 */
class Controller_Widgets_MenuShop extends Controller_Widgets
{
	public function action_index()
	{
		$menu = array(
			'heading' => 'shop.shop',
			'icon'    => 'th-large',
			'menu'    => array(
				array(
					'title'      => 'Заказы',
					'controller' => 'orders',
					'action'     => 'list',
					'icon'       => 'credit-card',
				),
				array(
					'title'      => 'Клиенты',
					'controller' => 'customers',
					'action'     => 'list',
					'icon'       => 'user',
				),
				array(
					'title' => 'hr',
				),
				array(
					'title'      => 'shop.category_manager',
					'controller' => 'categories',
					'action'     => 'list',
					'icon'       => 'folder-open',
				),
				array(
					'title'      => 'shop.category_add',
					'controller' => 'categories',
					'action'     => 'add',
					'icon'       => 'plus',
				),
				array(
					'title'      => 'shop.product_manager',
					'controller' => 'products',
					'action'     => 'list',
					'icon'       => 'copy',
				),
				array(
					'title'      => 'shop.product_add',
					'controller' => 'products',
					'action'     => 'add',
					'icon'       => 'plus',
				),
				array(
					'title' => 'hr',
				),
				array(
					'title'      => 'Новая Почта',
					'controller' => 'novaposhta',
					'action'     => 'list',
					'icon'       => 'move',
				),
				/*
				array(
					'title'      => 'app.settings_module',
					'controller' => 'shop',
					'action'     => 'settings',
					'icon'       => 'wrench',
				),
				array(
					'title'      => 'app.i18n_edit_iface',
					'controller' => 'shop',
					'action'     => 'i18n',
					'icon'       => 'flag',
				),
				*/
			)
		);

		$this->response->body(json_encode($menu));
	}
}