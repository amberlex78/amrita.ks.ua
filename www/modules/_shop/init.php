<?php defined('SYSPATH') or die('No direct script access.');

if ( ! Route::cache())
{
	// Вместо части `category` можно подставить, например, `category`
	Route::set('shop_category', 'products/<slug>(/page-<page>)', array('page' => '\d+'))
		->defaults(array(
			'controller' => 'shop',
			'action'     => 'category',
		));

	// Вместо части `product` можно подставить, например, `page`
	Route::set('shop_product', 'product/<slug>')
		->defaults(array(
			'controller' => 'shop',
			'action'     => 'product',
		));

	// Корзина
	Route::set('shop_cart', 'cart(/<action>(/<rowid>))')
		->defaults(array(
			'controller' => 'cart',
			'action'     => 'index',
		));
}