<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'native' => array(
		'lifetime'  => Date::DAY,
	),
	'cookie' => array(
		'encrypted' => TRUE,
		'lifetime'  => Date::DAY,
	),
);