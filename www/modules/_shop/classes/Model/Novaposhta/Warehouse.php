<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Novaposhta_Warehouse
 */
class Model_Novaposhta_Warehouse extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'novaposhta_warehouses';


	/**
	 * @return array
	 */
	public static function get_warehouses($ref = '')
	{
		return ORM::factory('Novaposhta_Warehouse')
			->where('CityRef', '=', $ref)
			->find_for_select('Ref', 'DescriptionRu', ['', 'Выберите отделение']);
	}
}
