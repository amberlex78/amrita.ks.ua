<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_Home
 */
class Controller_Home extends Controller_Frontend
{
	public $module = 'home';

	public function action_index()
	{
		$obj = Config::get('home');

		$this->title = $obj->meta_t;
		$this->keywords = $obj->meta_k;
		$this->description = $obj->meta_d;

		$last_posts = Model_Blog_Post::last(Config::get('blog.per_page_home'));
		$last_products = Model_Shop_Product::last(Config::get('shop.per_page_home'));

		$this->content = View::factory('home/v_index', [
			'text'          => $obj->text,
			'last_posts'    => $last_posts,
			'last_products' => $last_products,
			'config_image'  => Config::get('shop.product.image'),
		]);
	}
}