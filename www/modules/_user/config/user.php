<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'per_page_backend' => 10,
	'avatar' => array (
		'max_filesize' => '5M',
		'field_name' => 'fimage_avatar',
		'upload_dir' => 'user/avatar',
		'path_to_img' => 'user/avatar/',
		'generate_subdir' => false,
		'generate_filename' => true,
		'transformed' => array (
			'max_width' => 200,
			'max_height' => 235,
			'jpeg_quality' => 75,
			'fixed_size' => true,
		),
		'thumbnails' => array (
			0 => array (
				'prefix' => 'th_',
				'max_width' => 50,
				'max_height' => 60,
				'jpeg_quality' => 75,
				'fixed_size' => true,
			),
		),
	),
	'logo' => array (
		'max_filesize' => '5M',
		'field_name' => 'fimage_logo',
		'upload_dir' => 'user/logo',
		'path_to_img' => 'user/logo/',
		'generate_subdir' => false,
		'generate_filename' => true,
		'transformed' => array (
			'max_width' => 200,
			'max_height' => 200,
			'jpeg_quality' => 75,
			'fixed_size' => false,
		),
	),
);