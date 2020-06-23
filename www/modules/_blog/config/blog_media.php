<?php defined('SYSPATH') or die('No direct script access.');

return array(

	// Backend media
	'backend' => array(

		'css' => array(
			'posts' => array(
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
			'rubrics' => array(
				'rubrics.js',
			),
			'posts' => array(
				'posts.js',
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
