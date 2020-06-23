<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Shop_Customer
 */
class Model_Shop_Customer extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'shop_customers';

	/**
	 * @var array Relationhips
	 */
	protected $_has_many = [
		'orders' => [
			'model'       => 'Shop_Order',
			'foreign_key' => 'customer_id',
		],
	];

	/**
	 * Rules
	 * @return array
	 */
	public function rules()
	{
		return [
			'fio'   => [['not_empty']],
			'phone' => [['not_empty']],
			'email' => [['email'], ['not_empty']],
		];
	}

	/**
	 * Labels
	 * @return array
	 */
	public function labels()
	{
		return ['email' => 'Email'];
	}

	/**
	 * Filters
	 * @return array
	 */
	public function filters()
	{
		return [
			true    => [['trim']],
			'fio'   => [['strip_tags']],
			'phone' => [['strip_tags']],
		];
	}

	public function pre_post()
	{
		$email = Arr::get($_POST, 'email');
		if (!$email) {
			unset($_POST['email']);
		}

		$_POST['phone'] = Filter::phone_only_numbers(Arr::get($_POST, 'phone'));
	}

	public static function total($customer_id)
	{
		return DB::select([DB::expr('SUM(`total`)'), 'total'])
			->from('shop_orders')
			->where('customer_id', '=', $customer_id)
			->execute()
			->get('total');
	}
}