<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Model_Novaposhta_City
 */
class Model_Novaposhta_City extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'novaposhta_cities';

	/**
	 * @return array
	 */
	public static function get_cities()
	{
		return ORM::factory('Novaposhta_City')
			->find_for_select('Ref', 'DescriptionRu', ['', 'Выберите город']);
	}
}
