<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Products
 */
class Controller_Backend_Products extends Controller_Backend
{
	public $module = 'shop';

	// Block title on page
	public $block_title = 'shop.shop';

	/**
	 * List of products
	 */
	public function action_list()
	{
		$this->_monitor_filter();

		$this->save_referer('shop_product');

		$id = $this->request->param('id');

		if ($id)
		{
			$obj = ORM::factory('Shop_Category', $id);

			$this->breadcrumbs = View::factory('shop/backend/v_breadcrumbs')
				->set('breadcrumbs', $obj->parents(FALSE, TRUE));

			$obj = $obj->products;
		}
		else
			$obj = ORM::factory('Shop_Product');


		// Data for template
		$this->title   = __('shop.product_manager');
		$this->content = View::factory('shop/backend/products/v_list',
			array(
				'for_select'   => Model_Shop_Category::for_select(1, __('app.all')),
				'selected'     => $id,
				'pagination'   => $pg = Pagination::get($obj->count_all(FALSE), Config::get('shop.per_page_backend')),
				'obj'          => $obj->find_per_page($pg->offset, $pg->items_per_page, TRUE),
				'config_image' => Config::get('shop.product.image'),
			));
	}

	/**
	 * Add new product
	 */
	public function action_add()
	{
		$obj = ORM::factory('Shop_Product');

		$category_ids = Arr::get($_POST, 'category_ids', array());

		$arr_tags = Arr::get($_POST, 'arr_tags');

		if ($this->request->is_post())
		{
			$obj->user_id = $this->user->id;

			$obj->pre_post();
			$obj->values($_POST);

			try
			{
				$obj->save();
				$obj->save_categories($category_ids);

				if (isset($this->modules['_tags']))
					$obj->save_tags($arr_tags);

				Message::set('success', __('shop.product_added'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}

		// Data for template
		$this->title   = __('shop.product_add');
		$this->content = View::factory('shop/backend/products/v_form',
			array(
				'obj'            => $obj,
				'category_chboxes' => $obj->category_chboxes($category_ids),
				'v_th_image'     => AjaxImage::v_th_image('shop.product.image', $this->user->id),
			))
			->bind('arr_tags', $arr_tags)
			->bind('errors', $errors);
	}

	/**
	 * Edit product
	 */
	public function action_edit()
	{
		$id = $this->request->param('id');

		$obj = ORM::factory('Shop_Product', $id);

		if ( ! $obj->loaded())
			throw new HTTP_Exception_404();

		$arr_tags = Arr::get($_POST, 'arr_tags');

		if ($this->request->is_post())
		{
			$category_ids = Arr::get($_POST, 'category_ids', array());

			$obj->pre_post();
			$obj->values($_POST);

			try
			{
				$obj->save();
				$obj->save_categories($category_ids);

				if (isset($this->modules['_tags']))
					$obj->save_tags($arr_tags);

				Message::set('success', __('app.changes_saved'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}
		else
		{
			$category_ids = $obj->category_ids();

			// Get product tags
			if (isset($this->modules['_tags']))
				$arr_tags = $obj->get_arr_tags();
		}

		// Data for template
		$this->title   = __('shop.product_edit');
		$this->content = View::factory('shop/backend/products/v_form',
			array(
				'obj'            => $obj,
				'category_chboxes' => $obj->category_chboxes($category_ids),
				'v_th_image'     => AjaxImage::v_th_image('shop.product.image', $obj->id, $obj->fimage_product),
			))
			->bind('arr_tags', $arr_tags)
			->bind('errors', $errors);
	}

	/**
	 * Delete
	 */
	public function action_delete()
	{
		$obj = ORM::factory('Shop_Product', $this->request->param('id'));

		if ($obj->loaded())
		{
			$obj->delete();
			Message::set('success', __('shop.product_deleted'));
		}
		else
			Message::set('error', __('app.error_deleting'));

		$this->_redirect();
	}


	//==================================================================================================================
	//  Private methods

	/**
	 * Redirect to saved referer or editing
	 *
	 * @param string $id
	 */
	private function _redirect($id = NULL)
	{
		if (Arr::get($_POST, 'save_and_stay') == 'ok')
			if ($id !== NULL)
				$this->redirect(ADMIN.'/products/edit/'.$id);

		$this->redirect_referer('shop_product', ADMIN.'/products/list');
	}

	/**
	 * If request is product - filter by category
	 */
	private function _monitor_filter()
	{
		if ($this->request->is_post())
		{
			$id = (int) $this->request->post('id');

			$id > 1
				? HTTP::redirect(ADMIN.'/products/list/'.$id)
				: HTTP::redirect(ADMIN.'/products/list');
		}
	}
}