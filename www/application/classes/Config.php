<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class Config
 */
class Config extends Kohana_Config
{
	/**
	 * Load a configuration group.
	 *
	 * @param  string $name     Configuration name or name & group
	 * @param  bool   $as_array Return as array?
	 *
	 * @return mixed(array|Config)
	 */
	public static function get($name, $as_array = FALSE)
	{
		$config = Kohana::$config->load($name);

		if ($as_array)
			return is_object($config) ? $config->as_array() : (array) $config;
		else
			return $config;
	}
}