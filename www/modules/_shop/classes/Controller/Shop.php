<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Shop
 */
class Controller_Shop extends Controller_Frontend
{
	public $module = 'shop';

	/**
	 * Category view
	 */
	public function action_category()
	{
		if ($this->request->param('slug') == 'price') {

			$products = ORM::factory('Shop_Product')
				->where('enabled', '=', 1)
				->order_by('title')
				->find_all();

			$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
				->set('page_title', __('Прайс'));

			$this->content = View::factory('shop/v_price', [
				'products' => $products,
			]);

		} else {

			$category = ORM::factory('Shop_Category')
				->where('slug', '=', $this->request->param('slug'))
				->where('enabled', '=', 1)
				->find();

			if ( ! $category->loaded())
				throw new HTTP_Exception_404();

			$this->breadcrumbs = View::factory('shop/partials/v_breadcrumbs_category')
				->set('breadcrumbs', $category->parents(FALSE, TRUE));

//		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
//			->set('page_title', __('shop.categories_archive').': '.$category->title);

			// Sub-categories
			if ($category->show_subs)
				$subcategories = $category->get_subs();

			$products = ORM::factory('Shop_Product');

			// Query for get products of categories
			if ($category->show_products)
				$products->category_products($category->id);
			else
				$products->category_products($category->descendants_ids(TRUE));

			// Data for template
			$this->title       = $category->meta_t;
			$this->description = $category->meta_d;
			$this->keywords    = $category->meta_k;

			$this->content = View::factory('shop/v_category',
				array(
					'category'       => $category,
					'pagination'   => $pg = Pagination::get($products->count_all(FALSE), Config::get('shop.per_page_frontend')),
					'products'        => $products->find_per_page($pg->offset, $pg->items_per_page, FALSE, $category->products_orderby, $category->products_orderto),
					'config_image' => Config::get('shop.product.image'),
				))
				->bind('subcategories', $subcategories);
		}
	}

	/**
	 * product view
	 * Путь вида http://product/category_slug/product_slug
	 */
	public function action_product()
	{
		$product = ORM::factory('Shop_Product')
			->where('slug', '=', $this->request->param('slug'))
			->where('enabled', '=', 1)
			->find();

		if ( ! $product->loaded())
			throw new HTTP_Exception_404();

		$category_anchors = $product->category_anchors();

		$this->breadcrumbs = View::factory('shop/partials/v_breadcrumbs_product')
			->set('categories', $category_anchors)
			->set('product_title', $product->title);

//		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
//			->set('page_title', __('shop.product').': '.$product->title);

		// Data for template
		$this->title       = $product->meta_t;
		$this->description = $product->meta_d;
		$this->keywords    = $product->meta_k;

		$this->content = View::factory('shop/v_product', array(
			'product'         => $product,
			'config_image' => Config::get('shop.product.image'),
		))
		->bind('category_anchors', $category_anchors);
	}
}