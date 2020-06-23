<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Shop_Order_Status
 */
class Model_Shop_Order_Status extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'shop_order_statuses';

	/**
	 * Get color status as label
	 * @return string
	 */
	public function get_title()
	{
		switch ($this->id) {
			case 1:
				$color = ' label-important';
				break;
			case 2:
				$color = ' label-info';
				break;
			case 3:
				$color = ' label-success';
				break;
			default:
				$color = '';
		}

		return '<span class="label' . $color . '">' . $this->title . '</span>';
	}

	/**
	 * Get color status as class for button
	 * @return string
	 */
	public function get_class()
	{
		switch ($this->id) {
			case 1:
				$class = 'btn-danger';
				break;
			case 2:
				$class = 'btn-info';
				break;
			case 3:
				$class = 'btn-success';
				break;
			default:
				$class = '';
		}

		return $class;
	}
}