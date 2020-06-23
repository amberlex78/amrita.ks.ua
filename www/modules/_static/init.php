<?php defined('SYSPATH') or die('No direct script access.');

if ( ! Route::cache())
{
	Route::set('static', '<slug>')
		->defaults(array(
			'controller' => 'static',
			'action'     => 'index',
		));
}