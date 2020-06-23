<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Widgets
 */
class Controller_Widgets extends Controller_Security
{
	/**
	 * Before action
	 */
	public function before()
	{
		parent::before();

		if (Request::$initial === Request::$current)
			throw new HTTP_Exception_404();
	}
}
