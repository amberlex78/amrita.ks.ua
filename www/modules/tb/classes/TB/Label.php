<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class TB_Label
 *
 * Label for creating Twitter Bootstrap style Labels.
 *
 * @category   HTML/UI
 * @package    TB
 * @subpackage Twitter
 * @author     Alexey Abrosimov - <amberlex78@gmail.com>
 * @license    MIT License <http://www.opensource.org/licenses/mit>
 *
 * @see        http://twitter.github.io/bootstrap/components.html#labels-badges
 */
class TB_Label extends TB
{
	/**
	 * Label types (colors)
	 *
	 * @var constant
	 */
	const NORMAL    = '';
	const SUCCESS   = 'label-success';
	const WARNING   = 'label-warning';
	const IMPORTANT = 'label-important';
	const INFO      = 'label-info';
	const INVERSE   = 'label-inverse';

	/**
	 * Create a new Label
	 *
	 * @param constant|string $type        Type of label
	 * @param string          $message     Label text
	 * @param array           $attributes  Attributes to apply the label itself
	 *
	 * @return string
	 */
	protected static function show($type, $message, $attributes = array())
	{
		$attributes = TB::add_class($attributes, trim('label '.$type));

		return '<span'.HTML::attributes($attributes).'>'.$message.'</span>';
	}

	/**
	 * Create a new Normal Label
	 *
	 * @param string $message    Label text
	 * @param array  $attributes Attributes to apply the label itself
	 *
	 * @return string Label HTML
	 */
	public static function normal($message, $attributes = array())
	{
		return static::show(TB_Label::NORMAL, $message, $attributes);
	}

	/**
	 * Create a new Success Label
	 *
	 * @param string $message    Label text
	 * @param array  $attributes Attributes to apply the label itself
	 *
	 * @return string Label HTML
	 */
	public static function success($message, $attributes = array())
	{
		return static::show(TB_Label::SUCCESS, $message, $attributes);
	}

	/**
	 * Create a new Warning Label
	 *
	 * @param string $message    Label text
	 * @param array  $attributes Attributes to apply the label itself
	 *
	 * @return string Label HTML
	 */
	public static function warning($message, $attributes = array())
	{
		return static::show(TB_Label::WARNING, $message, $attributes);
	}

	/**
	 * Create a new Important Label
	 *
	 * @param string $message    Label text
	 * @param array  $attributes Attributes to apply the label itself
	 *
	 * @return string Label HTML
	 */
	public static function important($message, $attributes = array())
	{
		return static::show(TB_Label::IMPORTANT, $message, $attributes);
	}

	/**
	 * Create a new Info Label instance
	 *
	 * @param string $message    Label text
	 * @param array  $attributes Attributes to apply the label itself
	 *
	 * @return string Label HTML
	 */
	public static function info($message, $attributes = array())
	{
		return static::show(TB_Label::INFO, $message, $attributes);
	}

	/**
	 * Create a new Inverse Label
	 *
	 * @param string $message    Label text
	 * @param array  $attributes Attributes to apply the label itself
	 *
	 * @return string Label HTML
	 */
	public static function inverse($message, $attributes = array())
	{
		return static::show(TB_Label::INVERSE, $message, $attributes);
	}

	/**
	 * Create a new custom Label
	 * This assumes you have created the appropriate css class for the label type.
	 *
	 * @param string $type       Label type
	 * @param string $message    Label text
	 * @param array  $attributes Attributes to apply the label itself
	 *
	 * @return string Label HTML
	 */
	public static function custom($type, $message, $attributes = array())
	{
		$type = 'label-'.(string)$type;

		return static::show($type, $message, $attributes);
	}
}
