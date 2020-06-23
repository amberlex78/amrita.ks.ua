<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class AjaxImage
 */
class AjaxImage
{
	const MODE_ADD  = 'add';
	const MODE_EDIT = 'edit';

	private $_user_id;
	private $_is_admin;
	private $_settings;
	private $_validation;
	private $_fn;
	private $_img;

	private $_mode;
	private $_path;
	private $_filename;
	private $_thumb;

	private $_transformed;
	private $_thumbnails;

	/**
	 * Creates and returns a new object.
	 *
	 * @param array $path_to_config
	 *
	 * @return AjaxImage
	 */
	public static function factory($path_to_config)
	{
		return new AjaxImage($path_to_config);
	}

	/**
	 * Constructor
	 *
	 * @param $path_to_config
	 */
	private function __construct($path_to_config)
	{
		$this->_settings = array_merge(
			Config::get('app.image'),
			Config::get($path_to_config)
		);

		// Current user
		$user = Auth::instance()->get_user();
		if ($user)
		{
			$this->_user_id = $user->id;
		}

		// Is admin
		$this->_is_admin = Auth::instance()->logged_in('admin');

		// For short
		$this->_fn = $this->_settings['field_name'];

		// Parameters to transform
		$this->_transformed = Arr::get($this->_settings, 'transformed');

		// Parameters to thumbnails
		$this->_thumbnails  = Arr::get($this->_settings, 'thumbnails');
	}


	//==================================================================================================================
	// Validation

	/**
	 * Set and return object validation for image
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function validation()
	{
		if ( ! Upload::valid(Arr::get($_FILES, $this->_fn)))
			throw new Exception('Error field input name');

		// Hack for jquery.liteuploader
		//
		$_FILES[$this->_fn]['name']     = $_FILES[$this->_fn]['name'][0];
		$_FILES[$this->_fn]['type']     = $_FILES[$this->_fn]['type'][0];
		$_FILES[$this->_fn]['tmp_name'] = $_FILES[$this->_fn]['tmp_name'][0];
		$_FILES[$this->_fn]['error']    = $_FILES[$this->_fn]['error'][0];
		$_FILES[$this->_fn]['size']     = $_FILES[$this->_fn]['size'][0];

		// Object validation upload image file
		$this->_validation = Validation::factory($_FILES)
			->rule($this->_fn, 'Upload::valid')
			->rule($this->_fn, 'Upload::type', array(':value', $this->_settings['ext_allowed']))
			->rule($this->_fn, 'Upload::size', array(':value', $this->_settings['max_filesize']))
			->rule($this->_fn, 'Upload::image');

		return $this;
	}

	/**
	 * Return check result
	 *
	 * @return mixed
	 */
	public function check()
	{
		return $this->_validation->check();
	}

	/**
	 * Return errors
	 *
	 * @param string $message_file
	 *
	 * @return mixed
	 */
	public function errors($message_file = 'upload')
	{
		$errors = $this->_validation->errors($message_file);
		return $errors[$this->_fn];
	}


	//==================================================================================================================
	// Gets

	/**
	 * Return image src for image
	 *
	 * @return mixed
	 */
	public function get_src()
	{
		$prefix = $this->_thumbnails ? $this->_settings['thumbnails'][0]['prefix'] : '';
		$subdir = $this->_settings['generate_subdir'] ? ($this->_img->id . '/') : '';

		if ($this->_mode == self::MODE_EDIT)
		{
			return UPLOAD_URL . $this->_settings['upload_dir'] . '/' . $subdir . $prefix . $this->_img->{$this->_fn} . '?r=' . uniqid();
		}
		else
		{
			return IMG_TMP_URL . $prefix . $this->_img->fimage . '?r=' . uniqid();
		}
	}

	/**
	 * Return id record
	 *
	 * @return mixed
	 */
	public function get_id()
	{
		return $this->_img->id;
	}

	/**
	 * Return mode
	 *
	 * @return mixed
	 */
	public function get_mode()
	{
		return $this->_mode;
	}

	/**
	 * Return field name
	 *
	 * @return mixed
	 */
	public function get_fn()
	{
		return $this->_fn;
	}


	//==================================================================================================================
	// Upload

	/**
	 * Add single image for ADD mode
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function upload_for_add()
	{
		$this->_mode = self::MODE_ADD;

		// Upload path
		$this->_path = IMG_TMP_DIR;

		// New file name for record
		$this->_filename = File::create_new_filename(
			$this->_validation[$this->_fn]['name'],
			$this->_settings['generate_filename']
		);

		// Create and save images to dir
		$this->_save_image();

		// Previous image
		$o_image = ORM::factory('User_Tmpimage', array('user_id' => $this->_user_id, 'field_name' => $this->_fn));

		if ($o_image->loaded())
		{
			// Delete image
			@unlink(IMG_TMP_DIR . $o_image->fimage);

			// Delete thumbnails images from folder
			if ($this->_thumbnails)
				foreach ($this->_thumbnails as $thumbnail)
					@unlink(IMG_TMP_DIR . $thumbnail['prefix'] . $o_image->fimage);

			$o_image->delete();
		}

		// Save uploaded image to DB
		$this->_img = ORM::factory('User_Tmpimage');
		$this->_img->user_id    = $this->_user_id;
		$this->_img->fimage     = $this->_filename;
		$this->_img->field_name = $this->_fn;
		$this->_img->save();

		return $this;
	}

	/**
	 * Add single image for EDIT mode
	 *
	 * @param $model_name
	 * @param $id
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function upload_for_edit($model_name, $id)
	{
		$this->_mode = self::MODE_EDIT;

		$this->_img = ORM::factory($model_name, $id);

		if ( ! $this->_img->loaded())
			throw new Exception('Object not loaded');

		// Sub dir if enabled
		$subdir = $this->_settings['generate_subdir'] ? ($this->_img->id . DS) : '';

		// Upload path
		$this->_path = UPLOAD_DIR . $this->_settings['upload_dir'] . DS . $subdir;

		// Ctreate dir if not exist
		File::mkdir($this->_path);

		// New file name for record
		$this->_filename = File::create_new_filename(
			$this->_validation[$this->_fn]['name'],
			$this->_settings['generate_filename']
		);

		// Create and save images to dir
		$this->_save_image();


		$field_name = $this->_fn;

		// Delete image
		@unlink($this->_path . $this->_img->$field_name);

		// Delete thumbnails images from folder
		if ($this->_thumbnails)
			foreach ($this->_thumbnails as $thumbnail)
				@unlink($this->_path . $thumbnail['prefix'] . $this->_img->$field_name);

		// Save uploaded image to DB
		$this->_img->$field_name = $this->_filename;
		$this->_img->save();

		return $this;
	}


	//==================================================================================================================
	// Rotate

	/**
	 * Rotate image for ADD mode
	 *
	 * @param $id
	 * @param $direction
	 *
	 * @return mixed
	 */
	public function rotate_for_add($id, $direction)
	{
		$this->_mode = self::MODE_ADD;

		// Object for rotate
		if ($this->_is_admin)
		{
			$this->_img = ORM::factory('User_Tmpimage', $id);
		}
		else
		{
			$this->_img = ORM::factory('User_Tmpimage', array('id' => $id, 'user_id' => $this->_user_id));
		}

		if ($this->_img->loaded())
		{
			// Path
			$this->_path = IMG_TMP_DIR;
			$this->_filename = $this->_img->fimage;

			$this->_rotate($direction);
		}

		return $this->get_src();
	}

	/**
	 * Rotate image for EDIT mode
	 *
	 * @param $model_name
	 * @param $id
	 * @param $direction
	 *
	 * @return mixed
	 */
	public function rotate_for_edit($model_name, $id, $direction)
	{
		$this->_mode = self::MODE_EDIT;

		// Delete object
		$this->_img = ORM::factory($model_name, $id);

		// If object loaded and admin or object of user
		if ($this->_img->loaded())
		{
			// Sub dir if enabled
			$subdir = $this->_settings['generate_subdir'] ? ($this->_img->id . DS) : '';

			$field_name = $this->_fn;

			// Upload path
			$this->_path = UPLOAD_DIR . $this->_settings['upload_dir'] . DS . $subdir;
			$this->_filename = $this->_img->$field_name;

			$this->_rotate($direction);
		}

		return $this->get_src();
	}


	//==================================================================================================================
	// Delete

	/**
	 * Delete single image for ADD mode
	 *
	 * @param $id
	 *
	 * @return $this
	 */
	public function delete_for_add($id)
	{
		// Object for delete
		if ($this->_is_admin)
		{
			$obj = ORM::factory('User_Tmpimage', $id);
		}
		else
		{
			$obj = ORM::factory('User_Tmpimage', array('id' => $id, 'user_id' => $this->_user_id));
		}

		if ($obj->loaded())
		{
			// Delete image
			@unlink(IMG_TMP_DIR . $obj->fimage);

			// Delete thumbnails images from folder
			if ($this->_thumbnails)
				foreach ($this->_thumbnails as $thumbnail)
					@unlink(IMG_TMP_DIR . $thumbnail['prefix'] . $obj->fimage);

			$obj->delete();

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Delete single image for EDIT mode
	 *
	 * @param $model_name
	 * @param $id
	 *
	 * @return $this
	 */
	public function delete_for_edit($model_name, $id)
	{
		// Delete object
		$obj = ORM::factory($model_name, $id);

		// If object loaded and admin or object of user
		if ($obj->loaded())
		{
			// Sub dir if enabled
			$subdir = $this->_settings['generate_subdir'] ? ($obj->id . DS) : '';

			// Upload path
			$path = UPLOAD_DIR . $this->_settings['upload_dir'] . DS . $subdir;


			$field_name = $this->_fn;

			// Delete image
			@unlink($path . $obj->$field_name);

			// Delete thumbnails images from folder
			if ($this->_thumbnails)
				foreach ($this->_thumbnails as $thumbnail)
					@unlink($path . $thumbnail['prefix'] . $obj->$field_name);

			// Remove filename from DB
			$obj->$field_name = '';
			$obj->save();

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Get view thumbnail image for backend
	 *
	 * @param      $path
	 * @param      $id
	 * @param null $filename
	 *
	 * @return string|View
	 */
	public static function v_th_image($path, $id, $filename = NULL)
	{
		$config = Config::get($path);

		$th = isset($config['thumbnails']) ? $config['thumbnails'][0]['prefix'] : '';

		// For edit mode
		if ($filename !== NULL)
		{
			if ($filename)
			{
				return View::factory('app/backend/v_th_image', array(
					'id'  => $id,
					'fn'  => $config['field_name'],
					'src' => UPLOAD_URL.$config['path_to_img'].$th.$filename
				));
			}

			return '';
		}

		// For add mode

		// Get tmp uploaded image
		$o_image = ORM::factory('User_Tmpimage', array(
			'user_id'    => $id,
			'field_name' => $config['field_name']
		));

		if ($o_image->loaded() AND $o_image->fimage)
		{
			return View::factory('app/backend/v_th_image', array(
				'id'  => $o_image->id,
				'fn'  => $config['field_name'],
				'src' => IMG_TMP_URL.$th.$o_image->fimage
			));
		}

		return '';
	}


	//==================================================================================================================
	// Privates

	/**
	 * Rotate image
	 *
	 * @param $direction
	 *
	 * @throws Exception
	 */
	private function _rotate($direction)
	{
		try
		{
			$this->_thumb = PhpThumbFactory::create($this->_path . $this->_filename);
			$this->_thumb->rotateImage($direction);
			$this->_thumb->save($this->_path . $this->_filename);

			// If image has thumbnails
			if ($this->_thumbnails)
			{
				$this->_create_thumbs();
			}
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Save image
	 *
	 * @throws Exception
	 */
	private function _save_image()
	{
		try
		{
			$thumb = PhpThumbFactory::create($this->_validation[$this->_fn]['tmp_name']);

			// If image need transform
			if ($this->_transformed)
			{
				$thumb->setOptions(array('jpegQuality' => $this->_transformed['jpeg_quality']));

				// If size is specified fixed
				if ($this->_transformed['fixed_size'])
					$thumb->adaptiveResize($this->_transformed['max_width'], $this->_transformed['max_height']);
				else
					$thumb->resize($this->_transformed['max_width'], $this->_transformed['max_height']);
			}

			// Save image (from this to be create thumbnails)
			$thumb->save($this->_path . $this->_filename);

			// If image has thumbnails
			if ($this->_thumbnails)
			{
				$this->_create_thumbs();
			}
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Create thumbs
	 */
	private function _create_thumbs()
	{
		// Thumbnails
		foreach ($this->_thumbnails as $thumbnail)
		{
			// Create thumbnail from previous image
			$this->_thumb = PhpThumbFactory::create($this->_path . $this->_filename);
			$this->_thumb->setOptions(array('jpegQuality' => $thumbnail['jpeg_quality']));

			// If size is specified fixed
			if ($thumbnail['fixed_size'])
				$this->_thumb->adaptiveResize($thumbnail['max_width'], $thumbnail['max_height']);
			else
				$this->_thumb->resize($thumbnail['max_width'], $thumbnail['max_height']);

			// Save image
			$this->_thumb->save($this->_path . $thumbnail['prefix'] . $this->_filename);
		}
	}
}