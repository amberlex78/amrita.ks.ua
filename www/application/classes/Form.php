<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Form extends Kohana_Form
 */
class Form extends Kohana_Form
{
	/**
	 * Creates a form input for tag manager
	 *
	 * @param       $name
	 * @param       $type
	 * @param       $populate
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function tm($name, $type, $populate = '', array $attributes = NULL)
	{
		$attributes['type']         = 'text';
		$attributes['autocomplete'] = 'off';

		if ( ! isset($attributes['id']))
			$attributes['id'] = $name;

		$jsonDataTags = '
		<script type="text/javascript">
			var jsondata_'.$type.' = '.Model_Tag::get_json_tags($type).';
			var populate_'.$type.' = '.json_encode($populate).';
		</script>';

		return parent::input('_'.$name, '', $attributes) . $jsonDataTags;
	}

	/**
	 * Creates a form input. If no type is specified, a "text" type input will be returned.
	 * Added id attribute.
	 *
	 *     echo Form::input('username', $username);
	 *
	 * @param   string  $name       input name
	 * @param   string  $value      input value
	 * @param   array   $attributes html attributes
	 * @param   array   $special
	 * @return  string
	 * @uses    HTML::attributes
	 */
	public static function input($name, $value = NULL, array $attributes = NULL, $special = NULL)
	{
		if ( ! isset($attributes['id']))
			$attributes['id'] = $name;

		if (isset($attributes['type']) AND $attributes['type'] == 'hidden')
			unset($attributes['id']);

		return parent::input($name, $value, $attributes);
	}

	/**
	 * Creates a textarea form input.
	 *
	 *     echo Form::textarea('about', $about);
	 *
	 * @param   string  $name           textarea name
	 * @param   string  $body           textarea body
	 * @param   array   $attributes     html attributes
	 * @param   boolean $double_encode  encode existing HTML characters
	 * @return  string
	 * @uses    HTML::attributes
	 * @uses    HTML::chars
	 */
	public static function textarea($name, $body = '', array $attributes = NULL, $double_encode = TRUE)
	{
		if ( ! isset($attributes['id']))
			$attributes['id'] = $name;

		return parent::textarea($name, $body, $attributes, $double_encode);
	}

	/**
	 * Creates a form label. Label text is not automatically translated.
	 *
	 *     echo Form::label('username', 'Username', array('required', 'class' => 'classname'));
	 *
	 * @param   string  $input      target input
	 * @param   string  $text       label text
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    HTML::attributes
	 */
	public static function label($input, $text = NULL, array $attributes = NULL)
	{
		if ($text === NULL)
		{
			// Use the input name as the text
			$text = ucwords(preg_replace('/[\W_]+/', ' ', $input));
		}

		// Set the label target
		$attributes['for'] = $input;

		if (isset($attributes[0]) AND $attributes[0] == 'required')
		{
			unset($attributes[0]);

			return '<label'.HTML::attributes($attributes).'>'.$text.'<span class="required">*</span></label>';
		}
		else
			return '<label'.HTML::attributes($attributes).'>'.$text.'</label>';
	}


	//==================================================================================================================
	// Errors

	/**
	 * Display error of field inline
	 *
	 * @param       $name
	 * @param array $errors
	 *
	 * @return string
	 */
	public static function error_inline($name, array $errors = NULL)
	{
		return self::error($name, $errors, 'inline');
	}

	/**
	 * Display error of field block
	 *
	 * @param       $name
	 * @param array $errors
	 *
	 * @return string
	 */
	public static function error_block($name, array $errors = NULL)
	{
		return self::error($name, $errors, 'block');
	}

	/**
	 * Display error of field
	 *
	 * @param        $name
	 * @param array  $errors
	 * @param string $type
	 *
	 * @return string
	 */
	public static function error($name, array $errors = NULL, $type = 'inline')
	{
		if (isset($errors['_external'][$name]))
			return '<small><span class="tbred help-' . $type . '">' . $errors['_external'][$name] . '</span></small>';

		if (isset($errors[$name]))
			return '<small><span class="tbred help-' . $type . '">' . $errors[$name] . '</span></small>';
	}

	/**
	 * Return part class name for display error
	 *
	 * @param       $name
	 * @param array $errors
	 *
	 * @return string
	 */
	public static function if_error($name, array $errors = NULL)
	{
		if (isset($errors['_external'][$name]) OR isset($errors[$name]))
			return ' error';
	}
}
