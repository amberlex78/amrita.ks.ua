<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Extends HTML helper class
 */
class HTML extends Kohana_HTML
{
	/**
	 * Convert entities to HTML characters.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function decode($value)
	{
		return html_entity_decode( (string) $value, ENT_QUOTES, Kohana::$charset);
	}

	/**
	 * Creates a image link (added alt attribute by default)
	 *
	 *     echo HTML::image('media/img/logo.png', array('alt' => 'My Company'));
	 *
	 * @param   string  $file       file name
	 * @param   array   $attributes default attributes
	 * @param   mixed   $protocol   protocol to pass to URL::base()
	 * @param   boolean $index      include the index page
	 * @return  string
	 * @uses    URL::base
	 * @uses    HTML::attributes
	 */
	public static function image($file, array $attributes = NULL, $protocol = NULL, $index = FALSE)
	{
		if ( ! isset($attributes['alt']))
			$attributes['alt'] = '';

		return parent::image($file, $attributes, $protocol, $index);
	}

	/**
	 * Creates a image link.
	 *
	 *     echo HTML::if_image(
	 *         'uploads/user/33/' . $user->avatar,
	 *         array('alt' => $user->username),
	 *         'media/img/default_avatar.png'
	 *     );
	 *
	 * @param string $file
	 * @param array  $attributes
	 * @param null   $default_image  if not image exists, set default image
	 * @param null   $protocol
	 * @param bool   $index
	 *
	 * @return string
	 */
	public static function if_image($file, array $attributes = NULL, $default_image = NULL, $protocol = NULL, $index = FALSE)
	{
		$file_from_another_server = FALSE;

		if (strpos($file, '://') === FALSE)
		{
			$file_exists = is_file(DOCROOT . $file);

			// Add the base URL
			$file = URL::site($file, $protocol, $index);
		}
		else
		{
			$file_from_another_server = TRUE;
		}

		// Add the image link
		$attributes['src'] = $file;

		if ( ! isset($attributes['alt']))
			$attributes['alt'] = '';

		if ($file_from_another_server OR $file_exists)
		{
			return '<img'.HTML::attributes($attributes).' />';
		}
		elseif ($default_image)
		{
			return '<img'.HTML::attributes($attributes).' />';
		}

		return $attributes['alt'];
	}
}
