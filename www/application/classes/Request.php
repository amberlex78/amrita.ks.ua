<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Request - extends Kohana class request
 */
class Request extends Kohana_Request
{
	private static $_cache_current = array();
	private static $_cache_initial = array();

	private static $_detect = NULL;

	/**
	 * Singlton
	 *
	 * @return Mobile|null
	 */
	private static function _mobile_detect()
	{
		if (self::$_detect === NULL)
			self::$_detect = new Mobile();

		return self::$_detect;
	}

	/**
	 * Is mobile device
	 *
	 * @return bool
	 */
	public function is_mobile()
	{
		return Request::_mobile_detect()->isMobile();
	}

	/**
	 * Is tablet device
	 *
	 * @return bool
	 */
	public function is_tablet()
	{
		return Request::_mobile_detect()->isTablet();
	}

	/**
	 * Returns whether this request is GET
	 *
	 * @return  boolean
	 */
	public function is_get()
	{
		return ($this->method() === Request::GET);
	}

	/**
	 * Returns whether this request is POST
	 *
	 * @return  boolean
	 */
	public function is_post()
	{
		return ($this->method() === Request::POST);
	}

	/**
	 * Returns whether this request is PUT
	 *
	 * @return  boolean
	 */
	public function is_put()
	{
		return ($this->method() === Request::PUT);
	}

	/**
	 * Returns whether this request is DELETE
	 *
	 * @return  boolean
	 */
	public function is_delete()
	{
		return ($this->method() === Request::DELETE);
	}

	/**
	 * Get current request params with `slug` and `id`
	 * All values are in to lowercase
	 *
	 * @param null $key
	 *
	 * @return array
	 */
	public static function get($key = NULL)
	{
		if (empty(self::$_cache_current))
		{
			self::$_cache_current = array_map('strtolower', array(
				'route'      => Route::name(Request::current()->route()),
				'directory'  => Request::current()->directory(),
				'controller' => Request::current()->controller(),
				'action'     => Request::current()->action(),
				'slug'       => Request::current()->param('slug'),
				'id'         => Request::current()->param('id')
			));
		}

		return $key ? Arr::get(self::$_cache_current, $key) : self::$_cache_current;
	}
}