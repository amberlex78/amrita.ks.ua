<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Ajax_Cart
 */
class Controller_Ajax_Cart extends Controller_Ajax
{
	/**
	 * Add to cart
	 *
	 * @throws HTTP_Exception_404
	 * @throws Kohana_Exception
	 */
	public function action_add()
	{
		$id = Arr::get($_POST, 'id');

		$obj = ORM::factory('Shop_Product')
			->where('id', '=', $id)
			->where('enabled', '=', 1)
			->find();

		if (!$obj->loaded()) {
			throw new HTTP_Exception_404;
		}

		$product = [
			'id'    => $obj->id,
			'qty'   => 1,
			'price' => $obj->price,
			'name'  => $obj->title,
			'slug'  => $obj->slug,
		];

		$cart = new Cart();
		$cart->insert($product);

		$this->json['success'] = true;
		$this->json['content'] = View::factory('shop/v_cart_top', [
			'cart' => $cart,
		])->render();
	}

	/**
	 * @throws View_Exception
	 */
	public function action_checkout()
	{
		$delivery_id = Arr::get($_POST, 'delivery_id');

		$obj_order = ORM::factory('Shop_Order');

		if (!in_array($delivery_id, Model_Shop_Order_Delivery::get_arr_valid_ids())) {
			$delivery_id = 1;
		}

		$v_delivery_address = View::factory('shop/v_delivery_addr_' . $delivery_id, [
			'obj_order' => $obj_order,
		])
			->bind('errors', $errors)
			->bind('v_addresses', $v_addresses)
			->render();

		$this->json['success'] = true;
		$this->json['content'] = $v_delivery_address;
	}

	/**
	 *
	 * @throws View_Exception
	 */
	public function action_warehouses()
	{
		$city_ref = Arr::get($_POST, 'city_ref');

		$sel_addresses = Model_Novaposhta_Warehouse::get_warehouses($city_ref);

		$v_sel_address = View::factory('shop/v_sel_addresses', ['sel_addresses' => $sel_addresses])->render();

		$this->json['success'] = true;
		$this->json['content'] = $v_sel_address;
	}
}
