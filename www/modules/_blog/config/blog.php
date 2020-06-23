<?php defined('SYSPATH') OR die('No direct script access.');
return array(
	'per_page_home' => '3',
	'per_page_frontend' => '0',
	'per_page_backend' => '0',
	'post' =>	array(
		'image' => array(
			'max_filesize' => '5M',
			'field_name' => 'fimage_post',
			'upload_dir' => 'blog/posts',
			'path_to_img' => 'blog/posts/',
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