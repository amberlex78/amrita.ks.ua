<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Extend the Kohana Core
 */
class Kohana extends Kohana_Core
{
	/**
	 * Generates a version string based on the variables defined above.
	 *
	 * @param bool $as_anchor
	 *
	 * @return string
	 */
	public static function version($as_anchor = FALSE)
	{
		$version = ' v'.Kohana::VERSION.' ('.Kohana::CODENAME.')';

		if ($as_anchor)
			return HTML::anchor('http://kohanaframework.org/', 'Kohana', array('target' => '_blank', 'rel' => 'nofollow')).$version;

		return $version;
	}

	/**
	 * Get time generation and memory usage by app
	 * @return string
	 */
	public static function get_usage_time_and_memory()
	{
		return
			__('app.time_generation') . ': ' . number_format((microtime(TRUE) - KOHANA_START_TIME), 4) . __('app.time_generation_v') . ' | ' .
			__('app.memory_usage')    . ': ' . number_format((memory_get_usage() - KOHANA_START_MEMORY) / 1024 / 1024  , 1) . __('app.memory_usage_v');
	}

	/**
	 * Get IT-Gears site url
	 *
	 * @return string
	 */
	public static function get_company_url()
	{
		return HTML::anchor('https://www.linkedin.com/in/alexey-abrosimov', 'Alexey Abrosimov', array('target' => '_blank', 'rel' => 'nofollow'));
	}
}