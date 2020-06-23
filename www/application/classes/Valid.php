<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class URL - extends Kohana class URL
 */
class Valid extends Kohana_Valid
{
	/**
	 * Check min value
	 *
	 * @param $value
	 * @param $min
	 *
	 * @return bool
	 */
	public static function min($value, $min)
	{
		return $value >= $min;
	}

	/**
	 * Check max value
	 *
	 * @param $value
	 * @param $max
	 *
	 * @return bool
	 */
	public static function max($value, $max)
	{
		return $value <= $max;
	}
}