<?php defined('SYSPATH') or die('No direct script access.');

return array(

	// The default icons prefix
	'icons_prefix' => 'icon-',

	// Options relatives to the Table class
	'table' => array(

		// An array of columns to never display
		'ignore' => array(),

		// An array of classes to use for all tables
		// Example: array('condensed', 'bordered', 'striped', 'hover'),
		'classes' => array('striped', 'hover'),

		// Типы столбцов <thead> (для сортировки)
		'types_columns_sorter' => array('none', 'number', 'string', 'select'),
	),

);