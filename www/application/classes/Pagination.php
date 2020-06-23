<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Pagination
 */
class Pagination extends Kohana_Pagination
{
	/**
	 * Get pagination object
	 *
	 * @param int  $total_items
	 * @param null $per_page
	 *
	 * @return Pagination object
	 */
	public static function get($total_items = 0, $per_page = NULL)
	{
		$config = 'app.per_page_frontend'; // frontend global per page
		$viev   = 'app/v_pagination';      // frontend view pagination

		if (Request::get('directory') == 'backend')
		{
			$config = 'app.per_page_backend';     // backend global per page
			$viev   = 'app/backend/v_pagination'; // backend view pagination
		}

		if ($per_page === NULL OR $per_page == 0)
		{
			// global per page
			$per_page = Config::get($config);
		}

		return Pagination::factory(array(
			'total_items'    => $total_items,
			'items_per_page' => $per_page,
			'view'           => $viev
		));
	}
}