<?php defined('SYSPATH') or die('No direct access allowed.');

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
{
	return array
	(
		'default' => array
		(
			'type'       => 'MySQL',
			'connection' => array(
				'hostname'   => 'localhost',
				'database'   => 'db_amrita',
				'username'   => 'root',
				'password'   => 'root',
				'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => false,
			'profiling'    => true,
		)
	);
}
else
{
	return array
	(
		'default' => array
		(
			'type'       => 'MySQL',
			'connection' => array(
				'hostname'   => '',
				'database'   => '',
				'username'   => '',
				'password'   => '',
				'persistent' => FALSE,
			),
			'table_prefix' => '',
			'charset'      => 'utf8',
			'caching'      => true,
			'profiling'    => Kohana::$environment !== Kohana::PRODUCTION,
		)
	);
}

