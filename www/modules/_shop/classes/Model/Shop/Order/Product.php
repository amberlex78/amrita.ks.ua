<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Shop_Order_Product
 */
class Model_Shop_Order_Product extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'shop_order_products';

	/**
	 * @var array Relationhips
	 */
	protected $_belongs_to = [
		'order' => [
			'model'       => 'Shop_Order',
			'foreign_key' => 'order_id',
		],
	];


	/**
	 * @param $order_id
	 * @param $cart_contents
	 * @return void
	 */
	public function add_order_products($order_id, $cart_contents)
	{
		$obj_order_product = ORM::factory('Shop_Order_Product');

		// Заказанные товары
		foreach ($cart_contents as $product) {
			$obj_order_product->order_id   = $order_id;
			$obj_order_product->product_id = $product['id'];
			$obj_order_product->qty        = $product['qty'];
			$obj_order_product->price      = $product['price'];
			$obj_order_product->subtotal   = $product['subtotal'];
			$obj_order_product->title      = $product['name'];
			$obj_order_product->slug       = $product['slug'];

			// Сохраняем товары заказа
			$obj_order_product
				->save()
				->clear();
		}
	}
}