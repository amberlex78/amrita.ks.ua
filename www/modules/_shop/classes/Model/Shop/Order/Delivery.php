<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Shop_Order_Delivery
 */
class Model_Shop_Order_Delivery extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'shop_order_deliveries';

	/**
	 * Valid ids delivery method
	 * @return array
	 */
	public static  function get_arr_valid_ids() {
		return [1,2];
	}
}