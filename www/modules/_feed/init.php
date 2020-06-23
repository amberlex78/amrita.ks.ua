<?php defined('SYSPATH') or die('No direct script access.');

if ( ! Route::cache())
{
	// Feed RSS
	Route::set('feed', 'feed')
		->defaults(array(
			'controller' => 'feed',
			'action'     => 'index'
		));
}