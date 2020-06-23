<?php defined('SYSPATH') OR die('No direct script access.');
return array(
	'per_page_home' => '3',
	'per_page_frontend' => '7',
	'per_page_backend' => '0',
	'product' =>	array(
		'image' => array(
			'max_filesize' => '5M',
			'field_name' => 'fimage_product',
			'upload_dir' => 'shop/products',
			'path_to_img' => 'shop/products/',
			'generate_subdir' => FALSE,
			'generate_filename' => FALSE,
			'transformed' => array(
				'max_width' => 220,
				'max_height' => 220,
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
	),
);