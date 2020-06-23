<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Backend_Dashboard
 */
class Controller_Backend_Dashboard extends Controller_Backend
{
	// К какому блоку меню относится (для свертывания/развертывания меню)
	public $module = 'dashboard';

	// Название блока меню
	public $block_title = 'dashboard.administration';


	public function action_index()
	{
		$this->save_referer('shop_order');

		$this->title = __('dashboard.control_panel');

		$total_customers = ORM::factory('Shop_Customer')->count_all();

		$obj_orders = ORM::factory('Shop_Order');
		$total_orders = $obj_orders->reset(false)->count_all();
		$total_sum = DB::select([DB::expr('SUM(`total`)'), 'total'])
			->from('shop_orders')
			->execute()
			->get('total');


		$this->content = View::factory('dashboard/backend/v_index', [
			'total_customers' => $total_customers,
			'total_orders'    => $total_orders,
			'total_sum'       => $total_sum,
			'obj_orders'      => $obj_orders->order_by('created', 'DESC')->limit(5)->find_all(),
		]);
	}
}
