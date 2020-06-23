<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'username' => array(
		'unique' => 'user.required_unique_login',
	),
	'email' => array(
		'unique' => 'user.required_unique_email',
	),
);