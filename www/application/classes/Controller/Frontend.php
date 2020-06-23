<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Frontend controller
 */
class Controller_Frontend extends Controller_Template
{
	public $template = 'app/layout/v_default';

	/**
	 * @var Cart
	 */
	protected $cart;

	public function before()
	{
		parent::before();

		// Menu blog - rubrics
		if (isset($this->modules['_shop']))
		{
			$this->cart = new Cart();
			$this->template->set_global('cart', $this->cart);

			if (Request::get('route') !== 'shop_cart') {
				$this->blocks_lft['shop_categories'] = View::factory('shop/partials/v_menu_categories', [
					'obj' => Model_Shop_Category::categories_menu(),
				]);

				$this->blocks_lft['social_links'] = View::factory('app/partials/v_social_links', [
					'obj' => Model_Shop_Category::categories_menu(),
				]);
			}
		}

		// Tag cloud - links
		if (isset($this->modules['_tags']) AND isset($this->modules['_blog']))
		{
			// Tag cloud
			$this->blocks_lft['tag_cloud'] =
				View::factory('tags/partials/v_cloud')
					->set('obj', Model_Tag::get_for_cloud($this->modules));
		}

		// Menu static pages
		if (isset($this->modules['_static']))
		{
			$menu_static = Model_Static::get_for_menu();
		}

		// View header
		$this->template->v_header = View::factory('app/partials/v_header')
			->bind('menu_static', $menu_static); // Списка статических страниц может и не быть

		// Footer
		$this->template->v_footer = View::factory('app/partials/v_footer');
	}
}