<?php defined('SYSPATH') or die('No direct script access.');

return array(

	'css' => array (
		'app' => array (
			'bootstrap.min.css',
			'font-awesome.min.css',
			'jquery.navgoco.css',
			'style.css',
		),
	),

	'js' => array(
		'app' => array (
			'jquery-1.9.1.min.js', //'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
			'bootstrap.min.js',
			//'jquery.cookie.min.js',
			//'jquery.navgoco.min.js',
			//'snowfall.jquery.js',
			'cart.js',
			'app.js',
		),
	),


	// Backend media
	'backend' => array(

		'css' => array (
			'app' => array (
				'bootstrap.min.css',
				'style.css',
				'bootstrap-responsive.min.css',
				'bootstrap-notify.css',
				'font-awesome.min.css',
			),
		),

		'js' => array(
			'app' => array (
				'jquery-1.9.1.min.js', //'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
				'jquery.extends.js',
				'bootstrap.min.js',
				'bootstrap-notify.js',
				'jquery.liteuploader.min.js',
				'app.js',
			),
		),
	)
);
