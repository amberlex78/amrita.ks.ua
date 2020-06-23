<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class TB_ButtonGroup
 *
 * ButtonGroup for creating Twitter Bootstrap style Buttons groups.
 *
 * @category   HTML/UI
 * @package    TB
 * @subpackage Twitter
 * @author     Alexey Abrosimov - <amberlex78@gmail.com>
 * @license    MIT License <http://www.opensource.org/licenses/mit>
 *
 * @see        http://twitter.github.io/bootstrap/components.html#buttonGroups
 */
class TB_ButtonGroup extends TB
{
	/**
	 * Puts the ButtonGroup in a checkbox mode.
	 *
	 * @var string
	 */
	const TOGGLE_CHECKBOX = 'checkbox';

	/**
	 * Puts the ButtonGroup in a radio button mode. Allowing only
	 * one button to be selected at a time.
	 *
	 * @var string
	 */
	const TOGGLE_RADIO = 'radio';

	/**
	 * Opens a vertical button group
	 *
	 * @param boolean $toggle Whether the button group should be togglable
	 * @param array $attributes An array of attributes
	 *
	 * @return string An opening <div> tag
	 */
	public static function vertical_open($toggle = NULL, $attributes = array())
	{
		$attributes = TB::add_class($attributes, 'btn-group-vertical');

		return static::open($toggle, $attributes);
	}

	/**
	 * Alias for open so both horizontal_open and open can be used.
	 *
	 * @param boolean $toggle Whether the button group should be togglable
	 * @param array $attributes An array of attributes
	 *
	 * @return string An opening <div> tag
	 */
	public static function horizontal_open($toggle = NULL, $attributes = array())
	{
		return static::open($toggle, $attributes);
	}

	/**
	 * Opens a new ButtonGroup section.
	 *
	 * @param string $toggle Whether the button group should be togglable
	 * @param array $attributes An array of attributes
	 *
	 * @return string An opening <div> tag
	 */
	public static function open($toggle = NULL, $attributes = array())
	{
		$validToggles = array(TB_ButtonGroup::TOGGLE_CHECKBOX, TB_ButtonGroup::TOGGLE_RADIO);

		if (isset($toggle) && in_array($toggle, $validToggles))
			$attributes['data-toggle'] = 'buttons-'.$toggle;

		$attributes = TB::add_class($attributes, 'btn-group');

		return '<div'.HTML::attributes($attributes).'>';
	}

	/**
	 * Closes the ButtonGroup section.
	 *
	 * @return string
	 */
	public static function close()
	{
		return '</div>';
	}
}