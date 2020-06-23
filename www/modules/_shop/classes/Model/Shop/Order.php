<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Shop_Order
 */
class Model_Shop_Order extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'shop_orders';

	/**
	 * @var array Relationhips
	 */
	protected $_has_many = [
		'products' => [
			'model'       => 'Shop_Order_Product',
			'foreign_key' => 'order_id',
		],
		'deliveries' => [
			'model'       => 'Shop_Order_Delivery',
			'foreign_key' => 'delivery_id',
		],
	];

	/**
	 * @var array Relationhips
	 */
	protected $_belongs_to = [
		'customer' => [
			'model'       => 'Shop_Customer',
			'foreign_key' => 'customer_id',
		],
		'status' => [
			'model'       => 'Shop_Order_Status',
			'foreign_key' => 'status_id',
		],
	];

	/**
	 * Auto-update columns for creation
	 * @var string
	 */
	protected $_created_column = array(
		'column' => 'created',
		'format' => 'Y-m-d H:i:s',
	);

	/**
	 * Auto-update columns for updates
	 * @var string
	 */
	protected $_updated_column = array(
		'column' => 'updated',
		'format' => 'Y-m-d H:i:s',
	);

	/**
	 * @param $customer_id
	 * @param $total
	 * @return mixed
	 */
	public function add_order($customer_id, $delivery_id, $total)
	{
		$detect = new Mobile();
		$device_type = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

		$np_city_ref = Arr::get($_POST, 'np_city_ref');
		$np_address_ref = Arr::get($_POST, 'np_address_ref');

		$city = Arr::get($_POST, 'city');
		$address = Arr::get($_POST, 'address');

		if ($delivery_id = 1) {
			$obj_city = ORM::factory('Novaposhta_City', ['Ref' => $np_city_ref]);
			if ($obj_city->loaded()) {
				$city = $obj_city->DescriptionRu;
			}
			$obj_warehouse = ORM::factory('Novaposhta_Warehouse', ['Ref' => $np_address_ref]);
			if ($obj_warehouse->loaded()) {
				$address = $obj_warehouse->DescriptionRu;
			}
		}

		$obj_order = ORM::factory('Shop_Order');
		$obj_order->customer_id = $customer_id;
		$obj_order->delivery_id = $delivery_id;
		$obj_order->total = $total;
		$obj_order->checked = 0;
		$obj_order->device_type = $device_type;

		$obj_order->oblast = Arr::get($_POST, 'oblast');
		$obj_order->region = Arr::get($_POST, 'region');

		$obj_order->city = $city;
		$obj_order->address = $address;
		$obj_order->np_city_ref = $np_city_ref;
		$obj_order->np_address_ref = $np_address_ref;

		$obj_order->postcode = Arr::get($_POST, 'postcode');

		$obj_order->save();

		$obj_order->number = $obj_order->id . Text::random('nozero', 4);
		$obj_order->save();

		return $obj_order->id;
	}
}