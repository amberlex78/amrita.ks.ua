<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Backend_Orders
 */
class Controller_Backend_Orders extends Controller_Backend
{
	public $module = 'shop';

	public $block_title = 'shop.shop';

	public function action_list()
	{
		$this->save_referer('shop_order');

		$obj = ORM::factory('Shop_Order');

		$pg = Pagination::get(
			$obj->count_all(false),
			Config::get('shop.per_page_backend')
		);

		$obj = $obj->find_per_page($pg->offset, $pg->items_per_page, true);

		// Data for template
		$this->title = __('Заказы');
		$this->content = View::factory('shop/backend/orders/v_list', [
			'pg'          => $pg,
			'obj'         => $obj,
			'total_items' => $pg->total_items,
		]);
	}

	public function action_edit()
	{
		$obj = ORM::factory('Shop_Order', $this->request->param('id'));
		if (!$obj->loaded()) {
			throw new HTTP_Exception_404;
		}

		if ($this->request->is_post()) {
			$obj->values($_POST);
			try {
				$obj->save();
				Message::set('success', __('app.changes_saved'));
				$this->redirect_referer('shop_order', ADMIN . '/orders/list');
			} catch (ORM_Validation_Exception $e) {
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}

		}

		$this->title = __('Редактирование заказа');
		$this->content = View::factory('shop/backend/orders/v_form', [
			'obj'      => $obj,
			'products' => $obj->products->find_all(),
		])->bind('errors', $errors);
	}

	public function action_delete()
	{

	}
}