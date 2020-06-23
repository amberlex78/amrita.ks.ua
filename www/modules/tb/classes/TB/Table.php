<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class TB_Table
 *
 * Small helper class for creating tables with Bootstrap
 *
 * @category   HTML/UI
 * @package    TB
 * @subpackage Twitter
 * @author     Alexey Abrosimov - <amberlex78@gmail.com>
 * @license    MIT License <http://www.opensource.org/licenses/mit>
 *
 * @see        http://twitter.github.io/bootstrap/base-css.html#tables
 */
class TB_Table extends TB
{
	/**
	 * The current Table instance
	 *
	 * @var Table
	 */
	private static $table = NULL;

	/**
	 * The availables classes for a Table
	 *
	 * @var array
	 */
	private static $classes = array('striped', 'bordered', 'hover', 'condensed');

	/**
	 * The current table's number of columns
	 *
	 * @var integer
	 */
	private $numberColumns = 50;

	/**
	 * The current table body in memory
	 *
	 * @var string
	 */
	private $tbody = array();

	/**
	 * The rows to be ignored in the next body to come
	 *
	 * @var array
	 */
	private $ignore = array();

	/**
	 * The order in which the columns are to be printed out
	 *
	 * @var array
	 */
	private $order = array();

	/**
	 * Columns to append/replace in the current body
	 *
	 * @var array
	 */
	private $columns = array();

	/**
	 * The table's attributes
	 *
	 * @var array
	 */
	private $attributes = array();


	//==================================================================================================================
	// Static functions

	/**
	 * Checks call to see if we can create a table from a magic call (for you wizards).
	 * hover_striped, bordered_condensed, etc.
	 *
	 * @param string $method     Method name
	 * @param array  $parameters Method parameters
	 *
	 * @return mixed
	 */
	public static function __callStatic($method, $parameters)
	{
		// Opening a table
		if (TB::str_contains($method, 'open') OR $method == 'open')
		{
			$method  = strtolower($method);
			$classes = explode('_', $method);
			$method  = array_pop($classes);

			// Fallback to default type if defined
			if (sizeof($classes) == 0)
				$classes = Kohana::$config->load('tb.table.classes');

			// Filter table classes
			$classes    = array_intersect($classes, static::$classes);
			$attributes = TB::set_multi_class_attributes($method, $classes, $parameters, 0, 'table-');
			$attributes = Arr::get($attributes, 0);

			static::$table = new static($attributes);

			return static::$table->open();
		}

		// Set default function
		if ( ! $method)
			$method = 'table';

		// Use cases
		switch ($method)
		{
			case 'close':
				$close = static::table()->close();
				static::$table = NULL;
				return $close;
				break;

			default:
				return call_user_func_array(array(static::table(), $method), $parameters);
				break;
		}
	}

	/**
	 * Pass a method to the Table instance
	 *
	 * @param string $method     The method to call
	 * @param array  $parameters Its parameters
	 *
	 * @return Table A Table instance
	 */
	public function __call($method, $parameters)
	{
		// If trying to set a column
		if ( ! method_exists(static::$table, $method))
		{
			$this->$method = $parameters[0];

			return $this;
		}

		// Else, call the available method
		return call_user_func_array(array(static::$table, $method), $parameters);
	}

	/**
	 * Dynamically set a column's content
	 *
	 * @param string $column  The column's name and classes
	 * @param mixed  $content Its content
	 */
	public function __set($column, $content)
	{
		// List known keys
		$columns = Arr::get($this->tbody, key($this->tbody), array());
		$columns = array_keys(is_object($columns)
			? $columns->attributes
			: $columns);

		// If we're not replacing something, we're creating, assume classes
		if ( ! in_array($column, $columns))
			$column = str_replace('_', ' ', $column);

		// Store Closure/content
		$this->columns[$column] = $content;
	}

	/**
	 * Object
	 *
	 * @return static
	 */
	public static function table()
	{
		return static::$table ? : new static;
	}


	//==================================================================================================================
	// Table instance

	/**
	 * Creates a Table instance
	 *
	 * @param array $attributes An array of attributes to create for the table
	 */
	private function __construct($attributes = array())
	{
		$this->attributes = TB::add_class($attributes, 'table');
	}

	/**
	 * Creates a table opening tag
	 *
	 * @param array $attributes An array of attributes
	 *
	 * @return string A table opening tag
	 */
	private function open()
	{
		return PHP_EOL.'<table'.HTML::attributes($this->attributes).'>'.PHP_EOL;
	}

	/**
	 * Tag colgroup
	 *
	 * @param array $cols
	 *
	 * @return string
	 */
	private function colgroup(array $cols)
	{
		$colgroup = '<colgroup>'.PHP_EOL;

		foreach ($cols as $col)
		{
			$colgroup .= $col ? '<col width="'.$col.'">' : '<col>';
			$colgroup .= PHP_EOL;
		}

		$colgroup .= '</colgroup>'.PHP_EOL;

		return $colgroup;
	}

	/**
	 * Creates a table <thead> tag
	 *
	 * @return string A <thead> tag prefilled with rows
	 */
	private function headers()
	{
		$headers = func_get_args();

		if (sizeof($headers) == 1 and is_array($headers[0]))
			$headers = $headers[0];

		// Open headers
		$thead = '<thead>'.PHP_EOL;

		// Store the number of columns in this table
		$this->numberColumns = sizeof($headers);

		// Add each header with its attributes
		foreach ($headers as $header => $attributes)
		{
			// Allows to not specify an attributes array for leaner syntax
			if (is_numeric($header) AND is_string($attributes))
			{
				$header     = $attributes;
				$attributes = array();
			}

			$thead .= '<th'.HTML::attributes($attributes).'>'.$header.'</th>'.PHP_EOL;
		}

		$thead .= '</thead>'.PHP_EOL;

		return $thead;
	}

	/**
	 * Creates a table <thead> tag  with sorter columns
	 *
	 * @return string A <thead> tag prefilled with rows
	 */
	private function headers_sorter()
	{
		$headers = func_get_args();

		if (sizeof($headers) == 1 and is_array($headers[0]))
		{
			$headers = $headers[0];
		}

		//d($headers);

		// Open headers
		$thead = '<thead>' . PHP_EOL;

		// Store the number of columns in this table
		$this->numberColumns = sizeof($headers);

		// HTTP query array
		$_qsort = array();

		// Получаем параметры сортировки
		$sort['colunm'] = Request::current()->query('colunm');
		$sort['order']  = Request::current()->query('order');

		// Типы столбцов <thead>
		$types_columns_sorter = Kohana::$config->load('tb.table.types_columns_sorter');

		// Add each header with its attributes
		foreach ($headers as $header => $attributes)
		{
			if (is_array($attributes) AND in_array($attributes[0], $types_columns_sorter))
			{
				if ($sort['colunm'] == $header)
				{
					// Меняем сортировку текущего столбца на противоположную
					$_qsort['order'] = $sort['order'] == 'asc' ? 'desc' : 'asc';

					// Стрелка направления сортировки
					$caret = '<i class="icon-angle-' . ($sort['order'] == 'asc' ? 'down' : 'up') . '"></i> ';
				}
				else
				{
					// Сортировка нового столбца всегда с ASC
					$_qsort['order'] = 'asc';

					// Стрелка направления сортировки
					$caret = '';
				}

				$_qsort['colunm'] = $header;

				if ($attributes[0] == 'none')
					$header = $attributes[1];
				else
					$header = $attributes[1] == ''
						? ''
						: HTML::anchor(Request::current()->url().URL::query($_qsort).TB::$anchor_name, $attributes[1]).' '.$caret;

				$attributes = array();
			}

			$thead .= '<th style="white-space:nowrap;"' . HTML::attributes($attributes) . '>' . $header . '</th>' . PHP_EOL;
		}

		$thead .= '</thead>' . PHP_EOL;

		return $thead;
	}

	/**
	 * Set the content to be used for the next body
	 *
	 * @param mixed $content Can be results from a Query or a bare array
	 *
	 * @return Table The current table instance
	 */
	private function body($content)
	{
		if ( ! $content)
			return FALSE;

		$this->tbody = $content;

		return $this;
	}

	/**
	 * Ignore certains rows in the body to come
	 *
	 * @return Table The current table instance
	 */
	private function ignore()
	{
		$this->ignore = func_get_args();

		return $this;
	}

	/**
	 * Iterate the columns in a certain order in the body to come
	 */
	private function order()
	{
		$this->order = func_get_args();

		return $this;
	}

	/**
	 * Outputs the current body in memory
	 *
	 * @return string A <tbody> with content
	 */
	public function __toString()
	{
		if ( ! $this->tbody)
			return FALSE;

		// Fetch ignored columns
		if ( ! $this->ignore)
			$this->ignore = Kohana::$config->load('tb.table.ignore');

		// Fetch variables
		$content = $this->tbody;

		// Open table body
		$html = '<tbody>';

		// Iterate through the data
		foreach ($content as $row) {

			$html .= '<tr>';
			$columnCount = 0;
			$data = is_object($row) ? $row->attributes : $row;

			// Reorder columns if necessary
			if ($this->order)
				$data = array_merge(array_flip($this->order), $data);

			// Read the data row with ignored keys
			foreach ($data as $column => $value)
			{
				if (in_array($column, (array) $this->ignore))
					continue;

				// Check for replacing columns
				$replace = Arr::get($this->columns, $column);

				if ($replace)
				{
					$value = is_callable($replace) ? $replace($row) : $replace;
					$value = static::replace_keywords($value, $data);
				}

				$columnCount++;
				$html .= static::appendColumn($column, $value);
			}

			// Add supplementary columns
			if ($this->columns)
			{
				foreach ($this->columns as $class => $column)
				{
					// Check for replacing columns
					if (array_key_exists($class, $data))
						continue;

					// Calculate closures
					if (is_callable($column))
						$column = $column($row);

					// Parse and decode content
					$column = static::replace_keywords($column, $data);
					$column = HTML::decode($column);

					// Wrap content in a <td> tag if necessary
					$columnCount++;
					$html .= static::appendColumn($class, $column);
				}
			}

			$html .= '</tr>';

			// Save new number of columns
			if ($columnCount > $this->numberColumns)
				$this->numberColumns = $columnCount;
		}

		$html .= '</tbody>'.PHP_EOL;

		// Empty data from this body
		$this->ignore  = array();
		$this->columns = array();
		$this->tbody   = NULL;

		return $html;
	}

	/**
	 * Render a full_row with <th> tags
	 *
	 * @param string $content    The content to display
	 * @param array  $attributes An array of attributes
	 *
	 * @return string A table opening tag
	 */
	private function full_header($content, $attributes = array())
	{
		return static::table()->full_row($content, $attributes, TRUE);
	}

	/**
	 * Creates a table-wide row to display content
	 *
	 * @param string $content    The content to display
	 * @param array  $attributes The rows's attributes
	 * @param bool   $asHeaders  Draw row as header
	 *
	 * @return string A single-column row spanning all table
	 */
	private function full_row($content, $attributes = array(), $asHeaders = FALSE)
	{
		// Add a class for easy styling
		$attributes = TB::add_class($attributes, 'full-row');
		$tag        = $asHeaders ? 'th' : 'td';

		return '<tr'.HTML::attributes($attributes).'>
				<'.$tag.' colspan="'.$this->numberColumns.'">'.$content.'</'.$tag.'>
			</tr>';
	}

	/**
	 * Closes current table
	 *
	 * @return string A </table> closing tag
	 */
	private function close()
	{
		return '</table>'.PHP_EOL;
	}


	//==================================================================================================================
	// Helpers

	/**
	 * Wrap a supplementary column in a column if it isn't
	 *
	 * @param string $name  The column's name
	 * @param string $value Its value
	 *
	 * @return string A <td> tag
	 */
	private static function appendColumn($name, $value)
	{
		return TB::starts_with($value, '<td') ? $value : '<td class="column-'.$name.'">'.$value.'</td>';
	}

	/**
	 * Replace keywords with data in a string
	 *
	 * @param string $string A string with Laravel patterns (:key)
	 * @param array  $data   An array of data to fetch from
	 *
	 * @return string The modified string
	 */
	private static function replace_keywords($string, $data)
	{
		// Gather used patterns
		preg_match_all('/\(:(.+)\)/', $string, $matches);

		// Replace patterns with data
		foreach ($matches[0] as $key => $replace)
		{
			$with   = Arr::get($matches, '1.'.$key);
			$with   = Arr::get($data, $with);
			$string = str_replace($replace, $with, $string);
		}

		return $string;
	}
}
