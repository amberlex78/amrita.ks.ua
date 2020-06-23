<?php defined('SYSPATH') or die('No direct script access.');

return array(

	'css' =>array(
		'cart' => array(
			'checkout' => array(
				'bootstrap-select.min.css',
			)
		)
	),

	'js' =>array(
		'cart' => array(
			'checkout' => array(
				'jquery.maskedinput.js',
				'bootstrap-select.min.js',
			)
		)
	),

	// Backend media
	'backend' => array(

		'css' => array(
			'products' => array(
				'add' => array(
					'tagmanager.css',
					'datepicker.css',
				),
				'edit' => array(
					'tagmanager.css',
					'datepicker.css',
				),
			),
		),

		'js' => array(
			'categories' => array(
				'categories.js',
			),
			'products' => array(
				'products.js',
				'add' => array(
					'tagmanager.js',
					'bootstrap-datepicker.js',
				),
				'edit' => array(
					'tagmanager.js',
					'bootstrap-datepicker.js',
				),
			),
		),
	)
);
