<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'password_current' => array(
		'not_empty'      => 'user.must_old_password',
		'check_password' => 'user.incorrect_current_password',
	),
);