<?php defined('SYSPATH') or die('No direct script access.');

if ( ! Route::cache())
{
	// Route for send contact form
	Route::set('contact', 'contact')
		->defaults(array(
			'controller' => 'contact',
			'action'     => 'index'
		));
}