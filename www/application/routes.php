<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Common routes
 */
if ( ! Route::cache())
{
	// Errors
	Route::set('error', 'error/<action>(/<message>)',
		array(
			'action'  => '[0-9]++',
			'message' => '.+'
		))
		->defaults(array(
			'controller' => 'error',
		));

	// Static file serving (CSS, JS, Images)
	Route::set('media', 'media(/<file>)', array('file' => '.+'))
		->defaults(array(
			'controller' => 'media',
			'action'     => 'media',
			'file'       => NULL,
		));

	// Ajax route
	Route::set('ajax', 'ajax/<controller>/<action>(/<id>)')
		->defaults(array(
			'directory' => 'ajax',
		));

	// Widgets route
	Route::set('widget', 'widget/<controller>/<action>(/<param>)')
		->defaults(array(
			'directory' => 'widgets',
		));


	// Auth admin route
	Route::set('backend_auth', ADMIN.'/<action>(/<passtoken>)',
		array(
			'action' => 'login|logout|restore|restore_confirmed'
		))
		->defaults(array(
			'directory'  => 'backend',
			'controller' => 'auth',
		));

	// Default route for admin
	Route::set('backend', ADMIN.'(/<controller>(/<action>(/<id>)(/page-<page>)))',
		array(
			'id'   => '\d+',
			'page' => '\d+'
		))
		->defaults(array(
			'directory'  => 'backend',
			'controller' => 'dashboard',
			'action'     => 'index',
			'page'       => 1,
		));
}
