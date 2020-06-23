<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Class Menu Helper
 */
class Menu
{
	/**
	 * Echo simple <ul> (unordered list)
	 *
	 * @param $menus
	 * @param $route_name
	 * @param $field
	 * @param $sub
	 */
	public static function v_menu_ul($menus, $route_name, $field, $sub = FALSE)
	{
		if ($sub)
			echo '<ul>';
		else
			echo '<ul class="nav-accordeon">';


		foreach ($menus as $menu)
		{
			if ($menu['lvl'] == 3)
				$title = $menu[$field];
			else
				$title = '<i class="fa fa-angle-right" style="color: #EE4498"></i> '.$menu[$field];


			echo '<li'.(Request::get('slug') == $menu['slug'] ? ' class="active"' : '').'>'.
				HTML::anchor(Route::url($route_name, array('slug' => $menu['slug'])), $title);

			if (isset($menu['sub']))
				echo Menu::v_menu_ul($menu['sub'], $route_name, $field, TRUE);

			echo '</li>';
		}

		echo '</ul>';
	}

	/**
	 * Echo twitter bootstrap dropdown-menu
	 *
	 * @param $menus
	 * @param $route_name
	 * @param $field
	 * @param $sub
	 */
	public static function v_menu_dropdowns($menus, $route_name, $field, $sub = FALSE)
	{
		if ($sub)
			echo '<ul class="dropdown-menu">';
		else
			echo '<ul class="dropdown-menu" style="display: block; position: static;">';

		foreach ($menus as $menu)
		{
			$sub = isset($menu['sub']);

			if ($sub)
			{
				echo '<li class="dropdown-submenu">';
				echo HTML::anchor(Route::url($route_name, array('slug' => $menu['slug'])), $menu[$field], array('tabindex' => '-1'));
				echo Menu::v_menu_dropdowns($menu['sub'], $route_name, $field, $sub);
			}
			else
			{
				echo '<li>';
				echo HTML::anchor(Route::url($route_name, array('slug' => $menu['slug'])), $menu[$field], array('tabindex' => '-1'));
			}

			echo '</li>';
		}

		echo '</ul>';
	}
}
