<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class Kohana_Gravatar
 *
 * Used:
 *     echo Gravatar::factory(array('email' => $user->email))
 *         ->size_set(200)
 *         ->https_set_false()
 *         ->rating_set_pg()
 *         ->default_set_wavatar()
 *         ->image(array('class' => 'img-polaroid'));
 */
class Gravatar
{
	const HTTP_URL  = 'http://www.gravatar.com/avatar/';
	const HTTPS_URL = 'https://secure.gravatar.com/avatar/';

	/**
	 * Email addres
	 *
	 * @var string
	 */
	protected $email;

	/**
	 * Content rating
	 *
	 * @var string
	 */
	protected $rating = 'g';

	/**
	 * Image size
	 *
	 * @var int
	 */
	protected $size = 64;

	/**
	 * Default image type.
	 *
	 * @var mixed
	 */
	protected $image_default = 'mm';

	/**
	 * If default image shall be shown
	 * even if user the has an gravatar profile.
	 *
	 * @var boolean
	 */
	protected $default_force = FALSE;

	/**
	 * Whether or not to use HTTPS
	 *
	 * @var boolean
	 */
	protected $https = TRUE;

	/**
	 * List of valid ratings
	 *
	 * @var array
	 */
	private static $_valid_ratings = array('g', 'pg', 'r', 'x');

	/**
	 * List of valid imagesets
	 *
	 * @var array
	 */
	private static $_valid_image_default_types = array(404, 'mm', 'identicon', 'monsterid', 'wavatar', 'retro', 'blank');

	//==================================================================================================================
	// Base

	/**
	 * Retuns new Gravatar object
	 *
	 * @param array $params
	 *
	 * @return Gravatar
	 */
	public static function factory(array $params = array())
	{
		return new Gravatar($params);
	}

	/**
	 * Constructor forces execution of $this->setup()
	 *
	 * @param array $params
	 */
	public function __construct(array $params = array())
	{
		if ( ! empty($params))
			$this->setup($params);

		return $this;
	}

	/**
	 * Helps to load default settings passed by array.
	 *
	 * @param array $params
	 *
	 * @return $this
	 */
	public function setup(array $params)
	{
		if (isset($params['email']))
			$this->email_set($params['email']);

		if (isset($params['size']))
			$this->size_set($params['size']);

		if (isset($params['https']))
			$this->https_set($params['https']);

		if (isset($params['rating']))
			$this->rating_set($params['rating']);

		if (isset($params['default']))
			$this->default_set($params['default']);

		if (isset($params['default_force']))
			$this->default_force($params['default_force']);

		return $this;
	}

	/**
	 * Resets all properties. This functon helps to reuse object for another gravatar request.
	 *
	 * @return $this
	 */
	public function reset()
	{
		$this->email
			= $this->rating
			= $this->size
			= $this->image_default
			= $this->default_force
			= NULL;

		$this->https = TRUE;

		return $this;
	}


	//==================================================================================================================
	// Email

	/**
	 * Sets used email address.
	 *
	 * @param $email
	 *
	 * @return $this
	 */
	public function email_set($email)
	{
		$email = trim($email);

		if ( ! Valid::email($email))
			$this->exception('Invalid email address passed');

		$this->email = strtolower($email);

		// return self
		return $this;
	}

	/**
	 * Returns set email address.
	 *
	 * @return string
	 */
	public function email_get()
	{
		return $this->email;
	}


	//==================================================================================================================
	// Image

	/**
	 * Returns html code e.g.
	 * <img src="htp://someurl" />
	 *
	 * @param array $attributes
	 * @param boolean $protocol
	 * @param boolean $index
	 * @return string
	 */
	public function image(array $attributes = NULL, $protocol = NULL, $index = FALSE)
	{
		$attributes_auto = array(
			'width'  => $this->size,
			'height' => $this->size
		);

		$attributes = Arr::merge($attributes_auto, (array) $attributes);

		return HTML::image($this->url_make(), $attributes, $protocol, $index);
	}

	/**
	 * Sets returnes image size.
	 *
	 * @param $size
	 *
	 * @return $this
	 */
	public function size_set($size)
	{
		if ( ! is_int($size))
			$this->exception('Image size has to be integer');

		if ($size < 16 OR $size > 640)
			$this->exception('Avatar size must be within 16 and 640');

		$this->size = $size;

		return $this;
	}

	/**
	 * Returns set image size.
	 *
	 * @return mixed
	 */
	public function size_get()
	{
		return $this->size;
	}


	//==================================================================================================================
	// URL

	/**
	 * Returns gravatar URL based on passed settings.
	 *
	 * @throws Kohana_Exception
	 * @return string
	 */
	protected function url_make()
	{
		$this->validate();

		$url = $this->https ? Gravatar::HTTPS_URL : Gravatar::HTTP_URL;

		$url .= md5($this->email);

		$url .= URL::query(
			array(
				's' => $this->size,                        // image size
				'd' => $this->image_default,               // default image
				'r' => $this->rating,                      // image rating
				'f' => ($this->default_force ? 'y' : NULL) // force default image
			),
			FALSE
		);

		return $url;
	}

	/**
	 * Public function returning $this->url_make();
	 *
	 * @return string
	 */
	public function url()
	{
		return $this->url_make();
	}


	//==================================================================================================================
	// Ratings

	/**
	 * Sets content rating to G
	 *
	 * @return $this
	 */
	public function rating_set_g()
	{
		return $this->rating_set('g');
	}

	/**
	 * Sets content rating to PG
	 *
	 * @return $this
	 */
	public function rating_set_pg()
	{
		return $this->rating_set('pg');
	}

	/**
	 * Sets content rating to R
	 *
	 * @return $this
	 */
	public function rating_set_r()
	{
		return $this->rating_set('r');
	}

	/**
	 * Sets content rating to X
	 *
	 * @return $this
	 */
	public function rating_set_x()
	{
		return $this->rating_set('x');
	}

	/**
	 * Sets content rating.
	 *
	 * @param  string  $rating
	 *
	 * @return $this
	 */
	public function rating_set($rating)
	{
		$rating = trim(strtolower($rating));

		if ( ! in_array($rating, self::$_valid_ratings))
			$this->exception('Invalid rating passed');

		$this->rating = $rating;

		return $this;
	}

	/**
	 * Returns rating.
	 *
	 * @return string
	 */
	public function rating_get()
	{
		return $this->rating;
	}


	//==================================================================================================================
	// Set image

	/**
	 * Sets default image to url.
	 *
	 * @param  string  $url
	 *
	 * @return $this
	 */
	public function default_set_url($url)
	{
		return $this->default_set($url);
	}

	/**
	 * Sets default image to 404.
	 *
	 * @return $this
	 */
	public function default_set_404()
	{
		return $this->default_set(404);
	}

	/**
	 * Sets default image to mm.
	 *
	 * @return $this
	 */
	public function default_set_mm()
	{
		return $this->default_set('mm');
	}

	/**
	 * Sets default image to identicon.
	 *
	 * @return $this
	 */
	public function default_set_identicon()
	{
		return $this->default_set('identicon');
	}

	/**
	 * Sets default image to monsterid.
	 *
	 * @return $this
	 */
	public function default_set_monsterid()
	{
		return $this->default_set('monsterid');
	}

	/**
	 * Sets default image to wavatar.
	 *
	 * @return $this
	 */
	public function default_set_wavatar()
	{
		return $this->default_set('wavatar');
	}

	/**
	 * Sets default image to retro.
	 *
	 * @return $this
	 */
	public function default_set_retro()
	{
		return $this->default_set('retro');
	}

	/**
	 * Sets default image to blank.
	 *
	 * @return $this
	 */
	public function default_set_blank()
	{
		return $this->default_set('blank');
	}

	/**
	 * Sets default image if the user has no gravatar profile.
	 *
	 * @param mixed|string $image_default
	 *
	 * @return $this
	 */
	public function default_set($image_default)
	{
		$image_default = trim($image_default);

		// is default image a url?
		$is_url = Valid::url($image_default);

		if ( ! $is_url)
		{
			// make sure passed imageset is valid
			if ( ! in_array($image_default, self::$_valid_image_default_types))
				$this->exception('Invalid default image passed (valid: :valid_values',
					array(':valid_values' => implode(',', self::$_valid_image_default_types))
				);
		}
		else
			$image_default = urlencode($image_default);

		$this->image_default = $image_default;

		return $this;
	}

	/**
	 * Returns $this->image_default;
	 *
	 * @return mixed|string
	 */
	public function image_default_get()
	{
		return $this->image_default;
	}


	//==================================================================================================================
	// Set https

	/**
	 * Forces default image.
	 *
	 * @return $this
	 */
	public function default_force_set_true()
	{
		return $this->default_force_set(TRUE);
	}

	/**
	 * Disabled forcing of default image.
	 *
	 * @return $this
	 */
	public function default_force_set_false()
	{
		return $this->default_force_set(FALSE);
	}

	/**
	 * Forces gravatar to display default image.
	 *
	 * @param  boolean  $force
	 *
	 * @return $this
	 */
	public function default_force_set($force)
	{
		if ( ! is_bool($force))
			$this->exception('Image size has to be integer');

		$this->default_force = $force;

		return $this;
	}


	//==================================================================================================================
	// Set https

	/**
	 * Enabled https.
	 *
	 * @return $this
	 */
	public function https_set_true()
	{
		return $this->https_set(TRUE);
	}

	/**
	 * Disables https.
	 *
	 * @return $this
	 */
	public function https_set_false()
	{
		return $this->https_set(FALSE);
	}

	/**
	 * Sets whether https ot http should be used to query image.
	 *
	 * @param  boolean  $enabled
	 *
	 * @return $this
	 */
	public function https_set($enabled)
	{
		if ( ! is_bool($enabled))
			$this->exception('https needs to be TRUE of FALSE');

		$this->https = $enabled;

		return $this;
	}


	//==================================================================================================================
	// Download

	/**
	 * Downloads gravatar to location on server. Defaults to tmp directory.
	 *
	 * @param mixed $destination
	 *
	 * @return stdClass
	 */
	public function download($destination = NULL)
	{
		// get tmp direcoty if no destination passed
		if ( ! $destination)
			$destination = sys_get_temp_dir();

		$destination = Text::reduce_slashes($destination . DIRECTORY_SEPARATOR);

		// make sure destination is a directory
		if ( ! is_dir($destination))
			$this->exception('Download destination is not a directory', array(), 100);

		// make sure destination is writeable
		if (!is_writable($destination))
			$this->exception('Download destination is not writable', array(), 105);

		// make url
		$url = $this->url_make();

		try
		{
			$headers = get_headers($url, 1);
		}
		catch (ErrorException $e)
		{
			if ($e->getCode() === 2)
				$this->exception('URL does not seem to exist', array(), 200);
		}

		$valid_content_types = array(
			'image/jpg',
			'image/jpeg',
			'image/png',
			'image/gif'
		);

		// make sure content type exists
		if ( ! isset($headers['Content-Type']))
			$this->exception('Download - Content-Type not found', array(), 300);

		// make sure content type is valid
		if ( ! in_array($headers['Content-Type'], $valid_content_types))
			$this->exception('Download - Content-Type invalid', array(), 305);

		// make sure content disposition exist
		if (isset($headers['Content-Disposition']))
		{
			preg_match('~filename="(.*)"~', $headers['Content-Disposition'], $matches);

			if ( ! isset($matches[1]))
				$this->exception('Download - Filename not found', array(), 315);

			$filename = $matches[1];
		}
		else
			$filename = md5($url) . '.' . File::ext_by_mime($headers['Content-Type']);

		try
		{
			file_put_contents($destination . $filename, file_get_contents($url));
		}
		catch (ErrorException $e)
		{
			$this->exception('Download - File could not been downloaded', array(), 400);
		}

		$result = new stdClass;
		$result->filename = $filename;
		$result->extension = File::ext_by_mime($headers['Content-Type']);
		$result->location = $destination . $filename;

		return $result;
	}


	//==================================================================================================================
	// Validate and Exception

	/**
	 * Checks whether all necessary properties have been set correclty.
	 *
	 * @param boolean $throw_exceptions
	 * @throws Kohana_Exception
	 * @return boolean
	 */
	public function validate($throw_exceptions = TRUE)
	{
		// init var
		$valid_is = TRUE;

		if ( ! $this->email)
		{
			$valid_is = FALSE;
			if ($throw_exceptions)
				$this->exception('Email address has not been set');
		}

		if ( ! $this->rating)
		{
			$valid_is = FALSE;
			if ($throw_exceptions)
				$this->exception('Rating has not been set');
		}

		if ( ! $this->size)
		{
			$valid_is = FALSE;
			if ($throw_exceptions)
				$this->exception('Image size has not been set');
		}

		if ( ! $this->image_default)
		{
			$valid_is = FALSE;
			if ($throw_exceptions)
				$this->exception('Default image has not been set');
		}

		return $valid_is;
	}

	/**
	 * Kohana Exception Helper
	 *
	 * @param string $message
	 * @param array $variables
	 * @param int $code
	 * @param Exception $previous
	 * @throws Kohana_Exception
	 */
	protected function exception($message = '', array $variables = NULL, $code = 0, Exception $previous = NULL)
	{
		$message = 'Gravatar: '.$message;

		throw new Kohana_Exception($message, $variables, $code, $previous);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->image();
	}
}
