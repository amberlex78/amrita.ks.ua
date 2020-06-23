<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Ajax_Products
 */
class Controller_Ajax_Products extends Controller_Ajax
{
	// For access all roles required
	public $auth_required = array('login', 'admin');

	// Path to config image for upload
	protected $path_to_config = 'shop.product.image';

	/**
	 * Change product enabled
	 */
	public function action_change_enabled()
	{
		$this->_change_enabled('Shop_Product');
	}
	
	
	/**
	 * Upload image
	 */
	public function action_fimage_product_upload_add()
	{
		$this->_upload_image();
	}

	public function action_fimage_product_upload_edit()
	{
		$this->_upload_image('Shop_Product');
	}


	/**
	 * Rotate image
	 */
	public function action_fimage_product_rotate_add()
	{
		$this->_rotate_image();
	}

	public function action_fimage_product_rotate_edit()
	{
		$this->_rotate_image('Shop_Product');
	}


	/**
	 * Delete image
	 */
	public function action_fimage_product_delete_add()
	{
		$this->_delete_image();
	}

	public function action_fimage_product_delete_edit()
	{
		$this->_delete_image('Shop_Product');
	}
}