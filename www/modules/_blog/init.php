<?php defined('SYSPATH') or die('No direct script access.');

if ( ! Route::cache())
{
	// Вместо части `rubric` можно подставить, например, `category`
	Route::set('blog_rubric', 'category/<slug>(/page-<page>)', array('page' => '\d+'))
		->defaults(array(
			'controller' => 'blog',
			'action'     => 'rubric',
		));

	// Вместо части `post` можно подставить, например, `page`
	Route::set('blog_post', 'item/<slug>')
		->defaults(array(
			'controller' => 'blog',
			'action'     => 'post',
		));
}