<?php defined('SYSPATH') OR die('No direct script access.');
return array(
	'per_page_home' => '0',
	'per_page_frontend' => '5',
	'per_page_backend' => '0',
	'image' => array(
		'max_filesize' => '5M',
		'field_name' => 'fimage_static',
		'upload_dir' => 'static',
		'path_to_img' => 'static/',
		'generate_subdir' => FALSE,
		'generate_filename' => FALSE,
		'transformed' => array(
			'max_width' => 350,
			'max_height' => 350,
			'jpeg_quality' => 75,
			'fixed_size' => FALSE,
		),
		'thumbnails' => array(
			0 => array(
				'prefix' => 'th_',
				'max_width' => 160,
				'max_height' => 130,
				'jpeg_quality' => 75,
				'fixed_size' => TRUE,
			),
		),
	),
);