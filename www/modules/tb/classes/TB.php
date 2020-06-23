<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class TB
 *
 * Core class
 *
 * @category   HTML/UI
 * @package    TB
 * @subpackage Twitter
 * @author     Alexey Abrosimov - <amberlex78@gmail.com>
 * @license    MIT License <http://www.opensource.org/licenses/mit>
 *
 * @see        http://twitter.github.io/bootstrap/
 */
class TB
{
	// TODO: че за хрень
	protected static $anchor_name = '';

	/**
	 * Function adds the given value to an array. If the key already
	 * exists the value is concatenated to the end of the string.
	 * Mainly used for adding classes.
	 *
	 * @param array  $array Array object to be added to
	 * @param string $value String value
	 * @param string $key   Array key to use
	 *
	 * @return array
	 */
	protected static function add_class($array, $value, $key = 'class')
	{
		$array[$key] = isset($array[$key]) ? $array[$key].' '.$value : $value;

		return $array;
	}

	/**
	 * Function used to prime the attributes array for dynamic calls.
	 *
	 * @param string $exclude      String to exclude from array
	 * @param array  $class_array  Class array
	 * @param array  $params       Parameters array
	 * @param int    $index        Index of the parameters array to use
	 * @param string $extra        Prefix to the class
	 * @param string $extra_unless Value to exclude the prefix from
	 *
	 * @return array
	 */
	protected static function set_multi_class_attributes($exclude, $class_array, $params, $index, $extra = '', $extra_unless = NULL)
	{
		// Make sure the class attribute exists
		if ( ! isset($params[$index]))
			$params[$index] = array();

		if ( ! isset($params[$index]['class']))
			$params[$index]['class'] = '';

		foreach ($class_array as $s)
			if ($s != $exclude)
			{
				$class = ' '.$extra.$s;

				if (isset($extra_unless) && strpos($s, $extra_unless) !== FALSE)
					$class = ' '.$s;

				$params[$index]['class'] .= $class;
			}

		$params[$index]['class'] = trim($params[$index]['class']);

		return $params;
	}

	/**
	 * Determine if a given string contains a given sub-string.
	 *
	 * @param  string        $haystack
	 * @param  string|array  $needle
	 * @return bool
	 */
	protected static function str_contains($haystack, $needle)
	{
		foreach ((array) $needle as $n)
		{
			if (strpos($haystack, $n) !== FALSE)
				return TRUE;
		}

		return FALSE;
	}

	/**
	 * Determine if a given string begins with a given value.
	 *
	 * @param  string  $haystack
	 * @param  string  $needle
	 * @return bool
	 */
	protected static function starts_with($haystack, $needle)
	{
		return strpos($haystack, $needle) === 0;
	}
}
