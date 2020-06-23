<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Ajax_Page
 */
class Controller_Ajax_User extends Controller_Ajax
{
	// For access all roles required
	public $auth_required = array('login', 'admin');

	// Path to config image for upload
	protected $path_to_config = 'user.avatar';


	/**
	 * Upload avatar
	 */
	public function action_fimage_avatar_upload_add()
	{
		$this->_upload_image();
	}

	public function action_fimage_avatar_upload_edit()
	{
		$this->_upload_image('User');
	}

	/**
	 * Upload logo
	 */
	public function action_fimage_logo_upload_add()
	{
		$this->path_to_config = 'user.logo';
		$this->_upload_image();
	}

	public function action_fimage_logo_upload_edit()
	{
		$this->path_to_config = 'user.logo';
		$this->_upload_image('User');
	}


	/**
	 * Rotate avatar
	 */
	public function action_fimage_avatar_rotate_add()
	{
		$this->_rotate_image();
	}

	public function action_fimage_avatar_rotate_edit()
	{
		$this->_rotate_image('User');
	}

	/**
	 * Rotate logo
	 */
	public function action_fimage_logo_rotate_add()
	{
		$this->path_to_config = 'user.logo';
		$this->_rotate_image();
	}

	public function action_fimage_logo_rotate_edit()
	{
		$this->path_to_config = 'user.logo';
		$this->_rotate_image('User');
	}


	/**
	 * Delete avatar
	 */
	public function action_fimage_avatar_delete_add()
	{
		$this->_delete_image();
	}

	public function action_fimage_avatar_delete_edit()
	{
		$this->_delete_image('User');
	}

	/**
	 * Delete logo
	 */
	public function action_fimage_logo_delete_add()
	{
		$this->path_to_config = 'user.logo';
		$this->_delete_image();
	}

	public function action_fimage_logo_delete_edit()
	{
		$this->path_to_config = 'user.logo';
		$this->_delete_image('User');
	}
}