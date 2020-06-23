<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Class Controller_Cart
 */
class Controller_Cart extends Controller_Frontend
{
	public $module = 'shop';
	public $template = 'app/layout/v_one_column';

	/**
	 * Cart
	 */
	public function action_index()
	{
		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
			->set('page_title', __('Моя корзина'));

		$this->content = View::factory('shop/v_cart_index', [
			'cart' => $this->cart,
		]);
	}

	/**
	 * Checkout
	 */
	public function action_checkout()
	{
		$errors = [];
		$cart_contents = $this->cart->contents();
		if (empty($cart_contents)) {
			$this->redirect(Route::url('shop_cart'));
		};

		/**
		 * @var $obj_customer Model_Shop_Customer
		 */
		$obj_customer = ORM::factory('Shop_Customer');

		/**
		 * @var $obj_order Model_Shop_Order
		 */
		$obj_order = ORM::factory('Shop_Order');

		$sel_deliveries = ORM::factory('Shop_Order_Delivery')
			->find_for_select('id', 'title');

		$delivery_id = Arr::get($_POST, 'delivery_id', 1);
		if (!in_array($delivery_id, Model_Shop_Order_Delivery::get_arr_valid_ids())) {
			$delivery_id = 1;
		}

		if ($this->request->is_post()) {
			$obj_customer->pre_post();

			$obj_customer
				->where('phone', '=', Arr::get($_POST, 'phone'))
				->find();

			if (!$obj_customer->loaded()) {
				$obj_customer = ORM::factory('Shop_Customer');
			}

			$obj_customer->values($_POST);
			try {
				$obj_customer->save();
			} catch (ORM_Validation_Exception $e) {
				Message::set('error', 'Ошибка при оформлении заказа!');
				$errors = $e->errors('validation');
			}

			if (empty($errors)) {

				// Сохраняем новый заказ
				$order_id = $obj_order->add_order($obj_customer->id, $delivery_id, $this->cart->total());

				/**
				 * @var $obj_order_product Model_Shop_Order_Product
				 */
				$obj_order_product = ORM::factory('Shop_Order_Product');
				// Сохраняем заказанные товары
				$obj_order_product->add_order_products($order_id, $cart_contents);

				// Отправляем mail
				if (Mail::order($obj_customer->email, $order_id)) {
					//Message::set('success', 'Mail sent');
				} else {
					//Message::set('error', 'Mail not send');
				}

				$this->cart->destroy();

				//Message::set('success', 'Ваш заказ успешно отправлен на обработку!');
				$this->redirect(Route::url('shop_cart', ['action' => 'checked', 'rowid' => $order_id]));
			}
		}

		// Views
		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
			->set('page_title', __('Оформление заказа'));

		$v_delivery_address = View::factory('shop/v_delivery_addr_' . $delivery_id, [
			'obj_order' => $obj_order,
			'errors'    => $errors,
		])->bind('v_addresses', $v_addresses);

		$this->content = View::factory('shop/v_cart_checkout', [
			'v_delivery_address' => $v_delivery_address,
			'sel_deliveries'     => $sel_deliveries,
			'obj_customer'       => $obj_customer,
			'obj_order'          => $obj_order,
			'cart'               => $this->cart,
			'errors'             => $errors,
		]);
	}

	/**
	 * Checkout end
	 */
	public function action_checked()
	{
		$id = (int)$this->request->param('rowid');

		if ($id < 1) {
			$this->redirect(Route::url('shop_cart'));
		}

		/**
		 * @var $obj_order Model_Shop_Order
		 */
		$obj_order = ORM::factory('Shop_Order')
			->where('checked', '=', 0)
			->where('id', '=', $id)
			->find();

		if ($obj_order->loaded()) {
			$obj_order->checked = 1;
			$obj_order->save();
		} else {
			$this->redirect(Route::url('shop_cart'));
		}

		// Views
		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
			->set('page_title', __('Заказ'));

		$this->content = View::factory('shop/v_cart_checked', [
			'obj_order'          => $obj_order,
			'obj_order_products' => $obj_order->products->find_all(),
		]);
	}

	/**
	 * Update cart
	 */
	public function action_update()
	{
		if ($this->request->is_post()) {

			// Защита - обновляем только нужные поля
			$data = [];
			foreach ($_POST as $key => $val) {
				if (isset($val['rowid']) AND isset($val['qty'])) {
					$val['qty'] = (int)$val['qty'];
					$data[$key] = [
						'rowid' => $val['rowid'],
						'qty'   => ($val['qty'] < 1) ? 1 : $val['qty'],
					];
				}
			}

			$this->cart->update($data);
		}

		$this->redirect(Route::url('shop_cart'));
	}

	/**
	 * Delete product from cart
	 */
	public function action_delete()
	{
		$rowid = $this->request->param('rowid');

		if ($rowid) {
			$this->cart->remove($rowid);
		}

		$this->redirect(Route::url('shop_cart'));
	}
}