<?php defined('SYSPATH') or die('No direct script access.');
/**
 * [Object Relational Mapping] (ORM)
 */
class ORM extends Kohana_ORM
{
	/**
	 * Count the number of records in the table.
	 *
	 * @param bool $reset Pass FALSE to avoid resetting on the next call
	 *
	 * @return int
	 */
	public function count_all($reset = TRUE)
	{
		$is_distinct = FALSE;
		$selects = array();

		foreach ($this->_db_pending as $key => $method)
		{
			if ($method['name'] == 'distinct')
			{
				$is_distinct = TRUE;

				// Ignore any selected columns for now
				$selects[] = $method;
				unset($this->_db_pending[$key]);
			}

			if ($method['name'] == 'select')
			{
				// Ignore any selected columns for now
				$selects[] = $method;
				unset($this->_db_pending[$key]);
			}
		}

		if ( ! empty($this->_load_with))
		{
			foreach ($this->_load_with as $alias)
			{
				// Bind relationship
				$this->with($alias);
			}
		}

		$this->_build(Database::SELECT);

		$records = $this->_db_builder
			->from(array($this->_table_name, $this->_object_name));

		if ($is_distinct)
		{
			$records->select(array(
				DB::expr('COUNT(DISTINCT(`'.Inflector::singular($this->_table_name).'`.`'.$this->_primary_key.'`))'),
				'records_found'
			));
		}
		else
		{
			$records->select(array(DB::expr('COUNT(*)'), 'records_found'));
		}

		$records = $records->execute($this->_db)
			->get('records_found');

		// Add back in selected columns
		$this->_db_pending += $selects;

		$this->reset($reset);

		// Return the total number of records in a table
		return $records;
	}

	/**
	 * Get items per page
	 *
	 * Поле и направление сортировки указывается в $order и $direction
	 *
	 * Для $extra = FALSE
	 *     - если $order и $direction не указаны явно, сортируется по полю `primary_key` - 'asc'
	 *
	 * Для $extra = TRUE
	 *     - если $order и $direction не указаны явно, значения по умолчанию меняются на 'created' и 'desc'
	 *     - поле и направление сортировки берестся из $_GET
	 *
	 */
	public function find_per_page($offset = 0, $limit = 10, $extra = FALSE, $order = NULL, $direction = 'asc')
	{
		if ($extra)
		{
			if ($order === NULL)
			{
				$order = 'created';
				$direction = 'desc';
			}

			$get_column    = Arr::get($_GET, 'colunm');
			$get_direction = Arr::get($_GET, 'order');

			if ($get_column AND $get_direction
				AND in_array($get_direction, array('asc', 'desc'))
				AND array_key_exists($get_column, $this->_table_columns)
			)
				$this->order_by($get_column, $get_direction);
			else
				if (array_key_exists($order, $this->_table_columns))
					$this->order_by($order, $direction);
				else
					$this->order_by($this->primary_key(), $direction);
		}
		else
			$this->order_by($order ? $order : $this->primary_key(), $direction);

		return $this
			->offset($offset)
			->limit($limit)
			->find_all();
	}

	/**
	 * Return array for html <select> element
	 *
	 * Example:
	 *
	 *     $arr_options = ORM::factory('Model_Name')
	 *         ->order_by('title');
	 *         ->find_for_select('id', 'title', array('none', 'Select a value'));
	 *
	 *     echo Form::select('id', $arr_options);
	 *
	 * @param $key value      for option tag
	 * @param $val body       for option tag
	 * @param array $unshift  add an empty value to the start of a select list
	 *                        first element of $unshift - value for first option tag
	 *                        second element of $unshift - body for first option tag
	 *
	 * @return array
	 */
	public function find_for_select($key, $val, array $unshift = array())
	{
		$array = $this->find_all()->as_array($key, $val);

		if (isset($unshift[0]) AND isset($unshift[1])) {
			Arr::unshift($array, $unshift[0], $unshift[1]);
		}

		return $array;
	}

	/**
	 * Updates all existing records
	 *
	 * @return $this
	 */
	public function update_all()
	{
		$this->_build(Database::UPDATE);

		if (empty($this->_changed))
		{
			// Nothing to update
			return $this;
		}

		$data = array();
		foreach ($this->_changed as $column)
		{
			// Compile changed data
			$data[$column] = $this->_object[$column];
		}

		if (is_array($this->_updated_column))
		{
			// Fill the updated column
			$column = $this->_updated_column['column'];
			$format = $this->_updated_column['format'];

			$data[$column] = $this->_object[$column] = ($format === TRUE) ? time() : date($format);
		}

		$this->_db_builder->set($data)->execute($this->_db);

		return $this;
	}

	/**
	 * Delete all objects in the associated table. This does NOT destroy
	 * relationships that have been created with other objects.
	 *
	 * @chainable
	 * @return  ORM
	 */
	public function delete_all()
	{
		$this->_build(Database::DELETE);

		$this->_db_builder->execute($this->_db);

		return $this->clear();
	}


	/**
	 * Generate and save for log file array of $_table_columns
	 *
	 * @return array
	 */
	/*
	public function list_columns()
	{
		$columns_str  = PHP_EOL.'===================================='.PHP_EOL;
		$columns_str .= PHP_EOL.'TABLE: '.$this->table_name()         .PHP_EOL;
		$columns_str .= PHP_EOL.'===================================='.PHP_EOL;

		$_columns_data = $this->_db->list_columns($this->_table_name);

		$columns_str .= 'protected $_table_columns = array('.PHP_EOL;
		foreach ($_columns_data as $col_name => $col_prop){
			$columns_str .= "'$col_name' => array('type'=>'{$col_prop['type']}'),".PHP_EOL;
		}
		$columns_str .= ');'.PHP_EOL.PHP_EOL.PHP_EOL;

		kohana::$log->add(LOG::ERROR, $columns_str );

		return $_columns_data;
	}
	*/
}
