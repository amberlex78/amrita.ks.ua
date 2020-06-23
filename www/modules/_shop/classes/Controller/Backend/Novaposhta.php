<?php defined('SYSPATH') or die('No direct script access.');

use DiDom\Document;

use NovaPoshta\Config;

/**
 * Class Controller_Backend_Novaposhta
 */
class Controller_Backend_Novaposhta extends Controller_Backend
{
	public $module = 'shop';

	// Block title on page
	public $block_title = 'shop.shop';

	/**
	 * List cities
	 */
	public function action_list()
	{
		$obj = ORM::factory('Novaposhta_City');

		$pg = Pagination::get($obj->count_all(false), 14);

		$obj = $obj->find_per_page($pg->offset, $pg->items_per_page);

		// Data for template
		$this->title = __('Новая Почта - Населенные пункты');
		$this->content = View::factory('shop/backend/novaposhta/v_list', [
			'pg'          => $pg,
			'obj'         => $obj,
			'total_items' => $pg->total_items,
		]);
	}

	/**
	 * Update cities for novaposhta
	 */
	public function action_npupdate()
	{

		// Типы отделений
		//
		NP::$config['calledMethod'] = 'getWarehouseTypes';
		$warehouseTypes = NP::send(NP::$config);
//		d($warehouseTypes);

		$table_name = 'novaposhta_warehouse_types';
		DB::delete($table_name)->execute();
		DB::query(null, "ALTER TABLE $table_name AUTO_INCREMENT = 1")->execute();

		$query = DB::insert($table_name, [
			'Ref',
			'Description'
		]);
		foreach ($warehouseTypes['data'] as $item) {
			$query->values([
				$item->Ref,
				$item->Description
			]);
		}
		$query->execute();


		// Города
		//
		NP::$config['calledMethod'] = 'getCities';
		$cities = NP::send(NP::$config);
//		d($cities);

		$table_name = 'novaposhta_cities';
		DB::delete($table_name)->execute();
		DB::query(null, "ALTER TABLE $table_name AUTO_INCREMENT = 1")->execute();

		$query = DB::insert($table_name, [
			'CityID',
			'Description',
			'DescriptionRu',
			'Ref',
			'Delivery1',
			'Delivery2',
			'Delivery3',
			'Delivery4',
			'Delivery5',
			'Delivery6',
			'Delivery7',
			'Area',
		]);
		foreach ($cities['data'] as $item) {
			$query->values([
				$item->CityID,
				$item->Description,
				$item->DescriptionRu,
				$item->Ref,
				$item->Delivery1,
				$item->Delivery2,
				$item->Delivery3,
				$item->Delivery4,
				$item->Delivery5,
				$item->Delivery6,
				$item->Delivery7,
				$item->Area,
			]);
		}
		$query->execute();


		// Отделения
		//
		NP::$config['calledMethod'] = 'getWarehouses';
//		NP::$config['methodProperties'] = [
//			'CityRef' => 'db5c88cc-391c-11dd-90d9-001a92567626', // Херсон
//		];
		$warehouses = NP::send(NP::$config);
//		d($warehouses);

		$table_name = 'novaposhta_warehouses';
		DB::delete($table_name)->execute();
		DB::query(null, "ALTER TABLE $table_name AUTO_INCREMENT = 1")->execute();

		$query = DB::insert($table_name, [
			'Description',
			'DescriptionRu',
			'Phone',
			'TypeOfWarehouse',
			'Ref',
			'Number',
			'CityRef',
			'CityDescription',
			'CityDescriptionRu',
			'Longitude',
			'Latitude',
			'TotalMaxWeightAllowed',
			'PlaceMaxWeightAllowed',
			'Reception',
			'Delivery',
			'Schedule',
		]);
		foreach ($warehouses['data'] as $item) {
			$query->values([
				$item->Description,
				$item->DescriptionRu,
				$item->Phone,
				$item->TypeOfWarehouse,
				$item->Ref,
				$item->Number,
				$item->CityRef,
				$item->CityDescription,
				$item->CityDescriptionRu,
				$item->Longitude,
				$item->Latitude,
				$item->TotalMaxWeightAllowed,
				$item->PlaceMaxWeightAllowed,
				serialize($item->Reception),
				serialize($item->Delivery),
				serialize($item->Schedule),
			]);
		}
		$query->execute();

		Message::set('success', 'База данных для Новой Почты обновлена!');
		HTTP::redirect(ADMIN . '/novaposhta/list');

		/*
		$db_name = 'shop_cities_novaposhta';

		$document = new Document('http://novaposhta.ua/ru/office', true);
		$cities = $document
			->find('.list')[0]
			->find('li');

		$count = DB::select([DB::expr('COUNT(id)'), 'count'])
			->from($db_name)
			->execute()
			->get('count');

		DB::delete($db_name)->execute();
		DB::query(null, "ALTER TABLE $db_name AUTO_INCREMENT = 1")->execute();
		$query = DB::insert($db_name, ['title']);
		foreach ($cities as $city) {
			$query->values([$city->text()]);
		}
		$affected_rows = $query->execute();

		$msg = 'База населенных пунктов обновлена!
				<br> Было: '  . $count . '
				<br> Стало: ' . $affected_rows[1];

		Message::set('success', $msg);
		HTTP::redirect(ADMIN . '/novaposhta/list');
		*/
	}
}