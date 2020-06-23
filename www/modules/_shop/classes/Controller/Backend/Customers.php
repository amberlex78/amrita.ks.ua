<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Backend_Customers
 */
class Controller_Backend_Customers extends Controller_Backend
{
	public $module = 'shop';

	public $block_title = 'shop.shop';

	public function action_list()
	{
		$obj = ORM::factory('Shop_Customer');

		$pg = Pagination::get(
			$obj->count_all(false),
			Config::get('shop.per_page_backend')
		);

		$obj = $obj->find_per_page($pg->offset, $pg->items_per_page, true);

		// Data for template
		$this->title = __('Клиенты');
		$this->content = View::factory('shop/backend/customers/v_list', [
			'pg'  => $pg,
			'obj' => $obj,
		]);
	}

	public function action_view()
	{
		$obj = ORM::factory('Shop_Customer', $this->request->param('id'));

		if (!$obj->loaded()) {
			throw new HTTP_Exception_404;
		}

		$total_items = $obj->orders->reset(false)->count_all();
		$orders = $obj->orders->order_by('created', 'DESC')->find_all();

		$this->title = 'Клиент';
		$this->content = View::factory('shop/backend/customers/v_view', [
			'obj'         => $obj,
			'orders'      => $orders,
			'total_items' => $total_items,
			'total_sum'   => Model_Shop_Customer::total($obj->id),
		]);
	}

	public function action_edit()
	{

	}

	public function action_delete()
	{

	}
}