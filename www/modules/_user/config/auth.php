<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'driver'       => 'ORM',
	'hash_method'  => 'sha256',
	'hash_key'     => 'kzNHtCHEuKIbVBEUJ7sEXI2R6gi3FZfh3Xg04MlpsaZdaDEVpjukKmJSGVYqHdZ',
	'lifetime'     => Date::DAY,
	'session_type' => Session::$default,
	'session_key'  => 'auth_user',
	'token_password_expires' => Date::HOUR,

    /**
     * Username rules for validation
     * @var  array
     */
    'name' => array(
        'chars' => 'a-zA-Z0-9_\-\.',
        'length_min' => 2,
        'length_max' => 32,
    ),
);

