<?php defined('SYSPATH') or die('No direct script access.');

//if ( ! Route::cache())
{
	Route::set('tags', 'tags(/<slug>(/page-<page>))', array('page' => '\d+'))
		->defaults(array(
			'controller' => 'tags',
			'action'     => 'index',
		));
}