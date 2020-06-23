<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Class Filter
 */
class Filter
{
	public static function phone_only_numbers($phone)
	{
		$phone = str_replace('(', '', $phone);
		$phone = str_replace(')', '', $phone);
		$phone = str_replace('-', '', $phone);
		$phone = str_replace(' ', '', $phone);
		return $phone;
	}
}
