<?php defined('SYSPATH') or die('No direct script access.');

if ( ! Route::cache())
{
	// Home page
	Route::set('home', '')
		->defaults(array(
			'controller' => 'home',
			'action'     => 'index'
		));
}