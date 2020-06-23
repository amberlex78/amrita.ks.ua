<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class TB_Badge
 *
 * Badge for creating Twitter Bootstrap style Badges.
 *
 * @category   HTML/UI
 * @package    TB
 * @subpackage Twitter
 * @author     Alexey Abrosimov - <amberlex78@gmail.com>
 * @license    MIT License <http://www.opensource.org/licenses/mit>
 *
 * @see        http://twitter.github.io/bootstrap/components.html#labels-badges
 */
class TB_Badge extends TB
{
	/**
	 * Badge types (colors)
	 *
	 * @var constant
	 */
	const NORMAL    = '';
	const SUCCESS   = 'badge-success';
	const WARNING   = 'badge-warning';
	const IMPORTANT = 'badge-important';
	const INFO      = 'badge-info';
	const INVERSE   = 'badge-inverse';

	/**
	 * Create a new Badge.
	 *
	 * @param constant|string $type       Type of badge
	 * @param string          $message    Message in badge
	 * @param array           $attributes Attributes to apply the badge itself
	 *
	 * @return string Badge HTML
	 */
	protected static function show($type, $message, $attributes = array())
	{
		$attributes = TB::add_class($attributes, trim('badge '.$type));

		return '<span'.HTML::attributes($attributes).'>'.$message.'</span>';
	}

	/**
	 * Create a new Normal Badge.
	 *
	 * @param string $message    Message in badge
	 * @param array  $attributes Attributes to apply the badge itself
	 *
	 * @return string Badge HTML
	 */
	public static function normal($message, $attributes = array())
	{
		return static::show(TB_Badge::NORMAL, $message, $attributes);
	}

	/**
	 * Create a new Success Badge.
	 *
	 * @param string $message    Message in badge
	 * @param array  $attributes Attributes to apply the badge itself
	 *
	 * @return string Badge HTML
	 */
	public static function success($message, $attributes = array())
	{
		return static::show(TB_Badge::SUCCESS, $message, $attributes);
	}

	/**
	 * Create a new Warning Badge.
	 *
	 * @param string $message    Message in badge
	 * @param array  $attributes Attributes to apply the badge itself
	 *
	 * @return string Badge HTML
	 */
	public static function warning($message, $attributes = array())
	{
		return static::show(TB_Badge::WARNING, $message, $attributes);
	}

	/**
	 * Create a new Important Badge.
	 *
	 * @param string $message    Message in badge
	 * @param array  $attributes Attributes to apply the badge itself
	 *
	 * @return string Badge HTML
	 */
	public static function important($message, $attributes = array())
	{
		return static::show(TB_Badge::IMPORTANT, $message, $attributes);
	}

	/**
	 * Create a new Info Badge.
	 *
	 * @param string $message    Message in badge
	 * @param array  $attributes Attributes to apply the badge itself
	 *
	 * @return string Badge HTML
	 */
	public static function info($message, $attributes = array())
	{
		return static::show(TB_Badge::INFO, $message, $attributes);
	}

	/**
	 * Create a new Inverse Badge.
	 *
	 * @param string $message    Message in badge
	 * @param array  $attributes Attributes to apply the badge itself
	 *
	 * @return string Badge HTML
	 */
	public static function inverse($message, $attributes = array())
	{
		return static::show(TB_Badge::INVERSE, $message, $attributes);
	}

	/**
	 * Create a new custom Badge.
	 * This assumes you have created the appropriate css class for the label type.
	 *
	 * @param string $type       Type of badge
	 * @param string $message    Message in badge
	 * @param array  $attributes Attributes to apply the badge itself
	 *
	 * @return string Badge HTML
	 */
	public static function custom($type, $message, $attributes = array())
	{
		$type = 'badge-'.(string)$type;

		return static::show($type, $message, $attributes);
	}
}