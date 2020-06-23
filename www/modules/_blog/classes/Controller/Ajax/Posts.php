<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Ajax_Posts
 */
class Controller_Ajax_Posts extends Controller_Ajax
{
	// For access all roles required
	public $auth_required = array('login', 'admin');

	// Path to config image for upload
	protected $path_to_config = 'blog.post.image';

	/**
	 * Change post enabled
	 */
	public function action_change_enabled()
	{
		$this->_change_enabled('Blog_Post');
	}
	
	
	/**
	 * Upload image
	 */
	public function action_fimage_post_upload_add()
	{
		$this->_upload_image();
	}

	public function action_fimage_post_upload_edit()
	{
		$this->_upload_image('Blog_Post');
	}


	/**
	 * Rotate image
	 */
	public function action_fimage_post_rotate_add()
	{
		$this->_rotate_image();
	}

	public function action_fimage_post_rotate_edit()
	{
		$this->_rotate_image('Blog_Post');
	}


	/**
	 * Delete image
	 */
	public function action_fimage_post_delete_add()
	{
		$this->_delete_image();
	}

	public function action_fimage_post_delete_edit()
	{
		$this->_delete_image('Blog_Post');
	}
}