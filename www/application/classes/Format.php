<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Class Format
 */
class Format
{
	public static function price($price, $currency = 'грн.')
	{
		return number_format($price, 2, '.', ',') . '&nbsp;' . $currency;
	}

	public static function mobile($number, $nbsp = true)
	{
		$part1 = substr($number, 0, 3);
		$part2 = substr($number, -7, 3);
		$part3 = substr($number, -4);

		return $nbsp
			? '+38&nbsp(' . $part1 . ')&nbsp' . $part2 . '-' . $part3
			: '+38 (' . $part1 . ') ' . $part2 . '-' . $part3;
	}
}