<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class TB_Form
 *
 * Form methods for creating Twitter Bootstrap forms.
 *
 * @category   HTML/UI
 * @package    TB
 * @subpackage Twitter
 * @author     Alexey Abrosimov - <amberlex78@gmail.com>
 * @license    MIT License <http://www.opensource.org/licenses/mit>
 *
 * @see        http://twitter.github.io/bootstrap/base-css.html#forms
 */
class TB_Form extends TB
{
	/**
	 * Right-aligned labels controls are on the same line.
	 * This requires the control-group container.
	 * @see control_group($label, $control, $group_class = '', $help = null)
	 */
	const TYPE_HORIZONTAL = 'form-horizontal';

	/**
	 * Left-aligned labels and inline controls for small forms
	 */
	const TYPE_INLINE = 'form-inline';

	/**
	 * Adds extra roundind to text input fields
	 */
	const TYPE_SEARCH = 'form-search';

	/**
	 * Function adds the given value to the attribute of for the provided HTML.
	 *
	 * @param string $attr  attribute string
	 * @param string $value value to add to attribute string
	 * @param string $html  html to search for attribute
	 *
	 * @return string
	 */
	protected static function add_attribute($attr, $value, $html)
	{
		$_attr = $attr.'=';

		$attr_pos = strpos($html, $_attr);

		if ($attr_pos === FALSE OR strpos($html, 'span class="required"') !== FALSE)
		{
			$str_pos = strpos($html, ' ') + 1;
			$html    = substr_replace($html, $_attr.'"'.$value.'" ', $str_pos,  0);
		}
		else
		{
			$start = $attr_pos + strlen($_attr) + 1;
			$end   = strpos($html, '"', $start);

			$classes = substr($html, $start, $end - $start);

			if (strpos($classes, $value) === FALSE)
				$html = str_replace($classes, $value.' '.$classes,  $html);
		}

		return $html;
	}


	//==================================================================================================================
	// forms

	/**
	 * Open a HTML form styled for a horizontal form.
	 *
	 * @param string $action     form action
	 * @param array  $attributes array of attributes for form
	 *
	 * @return string
	 */
	public static function horizontal_open($action = NULL, array $attributes = NULL)
	{
		$attributes = TB::add_class($attributes, TB_Form::TYPE_HORIZONTAL);

		return Form::open($action, $attributes);
	}

	/**
	 * Open a HTML form styled as an inline form.
	 *
	 * @param string $action     form action
	 * @param array  $attributes array of attributes for form
	 *
	 * @return string
	 */
	public static function inline_open($action = NULL, array $attributes = NULL)
	{
		$attributes = TB::add_class($attributes, TB_Form::TYPE_INLINE);

		return Form::open($action, $attributes);
	}

	/**
	 * Open a HTML form styled for search.
	 *
	 * @param string $action     form action
	 * @param array  $attributes array of attributes for form
	 *
	 * @return string
	 */
	public static function search_open($action = NULL, array $attributes = NULL)
	{
		$attributes = TB::add_class($attributes, TB_Form::TYPE_SEARCH);

		return Form::open($action, $attributes);
	}

	/**
	 * Creates the closing form tag.
	 *
	 * @return  string
	 */
	public static function close()
	{
		return '</form>';
	}


	//==================================================================================================================
	// checkboxes and radios

	/**
	 * Create a HTML checkbox input element with a label.
	 * Uses the standard checkbox function.
	 *
	 * @param string $name       name attribute of the checkbox
	 * @param string $label      label text
	 * @param string $value      value of the checkbox
	 * @param bool   $checked    is checked
	 * @param array  $attributes attributes for label
	 *
	 * @return string
	 * @see    Form::checkbox()
	 */
	public static function labelled_checkbox($name, $label, $value = NULL, $checked = FALSE, array $attributes = NULL)
	{
		$hidden   = Form::hidden($name, 0);
		$checkbox = Form::checkbox($name, $value, $checked, $attributes).' '.$label;

		return Form::label($name, $hidden.$checkbox, array('class' => 'checkbox'));
	}

	/**
	 * Create a HTML checkbox input element with a label.
	 * Uses the standard checkbox function.
	 *
	 * @param string $name       name attribute of the checkbox
	 * @param string $label      label text
	 * @param string $value      value of the checkbox
	 * @param bool   $checked    is checked
	 * @param array  $attributes attributes for label
	 *
	 * @return string
	 * @see    Form::checkbox()
	 */
	public static function inline_labelled_checkbox($name, $label, $value = NULL, $checked = FALSE, array $attributes = NULL)
	{
		$hidden   = Form::hidden($name, 0);
		$checkbox = Form::checkbox($name, $value, $checked, $attributes).' '.$label;

		return Form::label($name, $hidden.$checkbox, array('class' => 'checkbox inline'));
	}

	/**
	 * Create a HTML radio input element with a label.
	 * Uses the standard radio function.
	 *
	 * @param string $name       name attribute of the radio
	 * @param string $label      label text
	 * @param string $value      value of the radio
	 * @param bool   $checked    is checked
	 * @param array  $attributes attributes for label
	 *
	 * @return string
	 * @see    Form::radio()
	 */
	public static function labelled_radio($name, $label, $value = NULL, $checked = FALSE, array $attributes = array())
	{
		return '<label class="radio">'.Form::radio($name, $value, $checked, $attributes).' '.$label.'</label>';
	}

	/**
	 * Create a HTML radio input element with a label.
	 * Uses the standard radio function.
	 *
	 * @param string $name       name attribute of the radio
	 * @param string $label      label text
	 * @param string $value      value of the radio
	 * @param bool   $checked    is checked
	 * @param array  $attributes attributes for label
	 *
	 * @return string
	 * @see    Form::radio()
	 */
	public static function inline_labelled_radio($name, $label, $value = NULL, $checked = FALSE, array $attributes = array())
	{
		return '<label class="radio inline">'.Form::radio($name, $value, $checked, $attributes).' '.$label.'</label>';
	}


	//==================================================================================================================
	// help

	/**
	 * Create a HTML span tag with the bootstrap help-inline class.
	 *
	 * @param string $value      value of help text
	 * @param array  $attributes attributes for help span
	 *
	 * @return string
	 */
	public static function inline_help($value, $attributes = array())
	{
		$attributes = TB::add_class($attributes, 'help-inline');

		return '<small><span'.HTML::attributes($attributes).'>'.$value.'</span></small>';
	}

	/**
	 * Create a HTML span tag with the bootstrap help-block class.
	 *
	 * @param string $value      value of help text
	 * @param array  $attributes attributes for help span
	 *
	 * @return string
	 */
	public static function block_help($value, $attributes = array())
	{
		$attributes = TB::add_class($attributes, 'help-block');

		return '<small><span'.HTML::attributes($attributes).'>'.$value.'</span></small>';
	}

	/**
	 * Create a bootstrap control group.
	 * $label, $control, and $help expect a fully formed HTML
	 *
	 * @param string $label       html of the label for the group
	 * @param string $control     html of the control for the group
	 * @param string $group_class extra classes for the group
	 * @param string $help        help value for the group
	 *
	 * @return string
	 */
	public static function control_group($label, $control, $help = '', $group_class = '')
	{
		$class = 'control-group';

		if ($group_class !== '')
			$class .= ' '.$group_class;

		$html  = '<div class="'.$class.'">';
		$html .= static::add_attribute('class', 'control-label', $label);
		$html .= '<div class="controls">';

		$html .= $control;
		$html .= $help;

		$html .= '</div></div>';

		return $html;
	}


	//==================================================================================================================
	// control-group with error message

	/**
	 * Create a bootstrap control group with error field
	 * $label, $control, $error and $help expect a fully formed HTML
	 *
	 * @param string $label    html of the label for the group
	 * @param string $control  html of the control for the group
	 * @param string $error    extra classes for the group
	 * @param string $help        help value for the group
	 * @param string $group_class help value for the group
	 *
	 * @return string
	 */
	public static function control_group_with_error($label, $control, $error, $help = '', $group_class = '')
	{
		$class = 'control-group';

		if ($group_class !== '')
			$class .= ' '.$group_class;

		if ($error !== '')
			$class .= ' error';

		$html  = '<div class="'.$class.'">';
		$html .= static::add_attribute('class', 'control-label', $label);
		$html .= '<div class="controls">';

		$html .= $control;

		if ($error !== '')
			if (strpos($error, 'help-inline') === FALSE)
				$html .= $help.$error;
			else
				$html .= $error.$help;
		else
			$html .= $help;

		$html .= '</div></div>';

		return $html;
	}

	/**
	 * Return HTML element with inline error
	 *
	 * @param       $name
	 * @param array $errors
	 *
	 * @return string
	 */
	public static function inline_error($name, array $errors = NULL)
	{
		if (isset($errors['_external'][$name]))
			return static::inline_help($errors['_external'][$name]);

		if (isset($errors[$name]))
			return static::inline_help($errors[$name]);

		return '';
	}

	/**
	 * Return HTML element with block error
	 *
	 * @param       $name
	 * @param array $errors
	 *
	 * @return string
	 */
	public static function block_error($name, array $errors = NULL)
	{
		if (isset($errors['_external'][$name]))
			return static::block_help($errors['_external'][$name]);

		if (isset($errors[$name]))
			return static::block_help($errors[$name]);

		return '';
	}


	//==================================================================================================================
	//

	/**
	 * Create a group of form actions (buttons).
	 *
	 * @return string
	 */
	public static function actions()
	{
		// Fetch arguments
		$buttons = func_get_args();

		if (sizeof($buttons) == 1)
			$buttons = $buttons[0];

		$html  = '<div class="form-actions">';
		$html .= is_array($buttons) ? implode(' ', $buttons) : $buttons;
		$html .= '</div>';

		return $html;
	}

	/**
	 * Create an input control with a prepended string.
	 *
	 * @param string $control control that should have a prepended value
	 * @param string $value   value to prepend to control
	 *
	 * @return string
	 */
	public static function prepend($control, $value)
	{
		return '<div class="input-prepend"><span class="add-on">'.$value.'</span>'.$control.'</div>';
	}

	/**
	 * Create an input control with an appended string.
	 *
	 * @param string $control control that should have an appended value
	 * @param string $value   value to append to control
	 *
	 * @return string
	 */
	public static function append($control, $value)
	{
		return '<div class="input-append">'.$control.'<span class="add-on">'.$value.'</span></div>';
	}
}