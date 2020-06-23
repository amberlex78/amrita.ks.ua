<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Ajax_Static
 */
class Controller_Ajax_Static extends Controller_Ajax
{
	// For access all roles required
	public $auth_required = array('login', 'admin');

	// Path to config image for upload
	protected $path_to_config = 'static.image';

	/**
	 * Change page enabled
	 */
	public function action_change_enabled()
	{
		$this->_change_enabled('Static');
	}

	/**
	 * Upload image
	 */
	public function action_fimage_static_upload_add()
	{
		$this->_upload_image();
	}

	public function action_fimage_static_upload_edit()
	{
		$this->_upload_image('Static');
	}


	/**
	 * Rotate image
	 */
	public function action_fimage_static_rotate_add()
	{
		$this->_rotate_image();
	}

	public function action_fimage_static_rotate_edit()
	{
		$this->_rotate_image('Static');
	}


	/**
	 * Delete image
	 */
	public function action_fimage_static_delete_add()
	{
		$this->_delete_image();
	}

	public function action_fimage_static_delete_edit()
	{
		$this->_delete_image('Static');
	}
}