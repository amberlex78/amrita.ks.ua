<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Extend the Kohana Date helper
 *
 * Форматирование вывода системной даты/времени
 * http://www.php.net/manual/ru/function.date.php
 */
class Date extends Kohana_Date
{
	// Переменная для кеширования
	protected static $_month = NULL;

	const FULL = 'd.m.Y, H:i';
	const DATE = 'd.m.Y';
	const TIME = 'H:i';
	const RSS  = 'D, d M Y H:i:s O';

	/**
	 * Returned date formatted by:
	 *
	 *     // Sample:
	 *     Date::format('2013-02-16 07:33:10', Date::DT_DATE);
	 *     Date::format('2013-02-16 07:33:10', 'H:i:s');
	 *
	 * @param string  $date_string  string of date datetime type
	 * @param string  $format       output format date
	 *
	 * @return string
	 */
	public static function format($date_string, $format = Date::DATE)
	{
		$date = new DateTime($date_string);

		return $date->format($format);
	}

	/**
	 * Возвращаем название месяца на русском соответствующее номеру месяца
	 *
	 * @param      $month  Номер месяца от 01 до 12
	 * @param bool $sufix
	 *
	 * @return mixed
	 */
	public static function get_month_full($month, $sufix = true)
	{
		if (self::$_month === null)
		{
			self::$_month = array(
				'01' => 'Январ'   . ($sufix ? 'я' : 'ь'),
				'02' => 'Феврал'  . ($sufix ? 'я' : 'ь'),
				'03' => 'Март'    . ($sufix ? 'а' : '' ),
				'04' => 'Апрел'   . ($sufix ? 'я' : '' ),
				'05' => 'Ма'      . ($sufix ? 'я' : 'й'),
				'06' => 'Июн'     . ($sufix ? 'я' : 'ь'),
				'07' => 'Июл'     . ($sufix ? 'я' : 'ь'),
				'08' => 'Август'  . ($sufix ? 'а' : '' ),
				'09' => 'Сентябр' . ($sufix ? 'я' : 'ь'),
				'10' => 'Октябр'  . ($sufix ? 'я' : 'ь'),
				'11' => 'Ноябр'   . ($sufix ? 'я' : 'ь'),
				'12' => 'Декабр'  . ($sufix ? 'я' : 'ь'),
			);
		}

		return self::$_month[$month];
	}

	/**
	 * Возвращаем дату в формате: `06 Января 2012`
	 * (PHP 5 >= 5.2.0)
	 *
	 * @param $date_string  Строка вида `YYYY-MM-DD HH:MM:SS`
	 *
	 * @return string
	 */
	public static function d_m_Y($date_string)
	{
		$date = new DateTime($date_string);

		return $date->format('d').' '.Date::get_month_full($date->format('m')).' '.$date->format('Y');
	}
}