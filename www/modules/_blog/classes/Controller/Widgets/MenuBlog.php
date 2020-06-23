<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Widgets_MenuBlog
 */
class Controller_Widgets_MenuBlog extends Controller_Widgets
{
	public function action_index()
	{
		$menu = array(
			'heading' => 'blog.blog',
			'icon'    => 'book',
			'menu'    => array(
				array(
					'title'      => 'blog.rubric_manager',
					'controller' => 'rubrics',
					'action'     => 'list',
					'icon'       => 'folder-open',
				),
				array(
					'title'      => 'blog.rubric_add',
					'controller' => 'rubrics',
					'action'     => 'add',
					'icon'       => 'plus',
				),
				array(
					'title'      => 'blog.post_manager',
					'controller' => 'posts',
					'action'     => 'list',
					'icon'       => 'copy',
				),
				array(
					'title'      => 'blog.post_add',
					'controller' => 'posts',
					'action'     => 'add',
					'icon'       => 'plus',
				),
				/*
				array(
					'title'      => 'app.settings_module',
					'controller' => 'blog',
					'action'     => 'settings',
					'icon'       => 'wrench',
				),
				array(
					'title'      => 'app.i18n_edit_iface',
					'controller' => 'blog',
					'action'     => 'i18n',
					'icon'       => 'flag',
				),
				*/
			)
		);

		$this->response->body(json_encode($menu));
	}
}