<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Model_Shop_Category
 */
class Model_Shop_Category extends MPTT
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'shop_categories';

	/**
	 * A category has many products and sub-categories
	 * @var array Relationhips
	 */
	protected $_has_many = array
	(
		'products' => array(
			'model'       => 'Shop_Product',
			'through'     => 'shop_products_categories',
			'foreign_key' => 'category_id',
			'far_key'     => 'product_id',
		),
		'subs' => array(
			'model'       => 'Shop_Category',
			'foreign_key' => 'parent_id',
		),
	);

	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns = array(
		'id' => array('type'=>'int'),
		'parent_id'              => array('type'=>'int'),
		'lft'                    => array('type'=>'int'),
		'rgt'                    => array('type'=>'int'),
		'lvl'                    => array('type'=>'int'),
		'scope'                  => array('type'=>'int'),
		'allow_delete'           => array('type'=>'int'),
		'title'                  => array('type'=>'string'),
		'description'            => array('type'=>'string'),
		'enabled'                => array('type'=>'int'),
		'show_description'       => array('type'=>'int'),
		'show_subs'              => array('type'=>'int'),
		'show_subs_descriptions' => array('type'=>'int'),
		'show_products'             => array('type'=>'int'),
		'show_products_preview'     => array('type'=>'int'),
		'show_products_images'      => array('type'=>'int'),
		'products_orderby'          => array('type'=>'string'),
		'products_orderto'          => array('type'=>'string'),
		'slug'                   => array('type'=>'string'),
		'meta_t'                 => array('type'=>'string'),
		'meta_k'                 => array('type'=>'string'),
		'meta_d'                 => array('type'=>'string'),
	);


	//==================================================================================================================
	//  Rules, Labels, Filters

	/**
	 * Rules
	 * @return array
	 */
	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
			),
			'slug' => array(
				array('alpha_dash'),
			),
			'products_orderby' => array(
				array('in_array', array(':value', array('pubdate', 'title')))
			),
			'products_orderto' => array(
				array('in_array', array(':value', array('asc', 'desc')))
			),
		);
	}

	/**
	 * Labels
	 * @return array
	 */
	public function labels()
	{
		return array(
			'title' => __('shop.category_title'),
			'slug'  => __('app.seo_slug'),
		);
	}

	/**
	 * Filters
	 * @return array
	 */
	public function filters()
	{
		return array(
			TRUE     => array(array('trim')),
			'title'  => array(array('strip_tags')),
			'slug'   => array(array('mb_strtolower')),
			'meta_t' => array(array('strip_tags')),
			'meta_d' => array(array('strip_tags')),
			'meta_k' => array(array('strip_tags')),
		);
	}


	//==================================================================================================================
	//  Model public methods

	/**
	 * Prepare some POST values
	 */
	public function pre_post()
	{
		unset(
			$_POST['parent_id'],
			$_POST['lft'],
			$_POST['rgt'],
			$_POST['lvl'],
			$_POST['scope'],
			$_POST['allow_delete']
		);

		if (trim(Arr::get($_POST, 'meta_t')) == '')
			$_POST['meta_t'] = Arr::get($_POST, 'title');

		if (trim(Arr::get($_POST, 'meta_d')) == '')
			$_POST['meta_d'] = Arr::get($_POST, 'description');
	}

	/**
	 * Add category
	 *
	 * @access  public
	 * @param   ORM_MPTT|int  primary key value or ORM_MPTT object of target node
	 * @return  ORM_MPTT
	 */
	public function insert_as_last_child($target)
	{
		if ($this->slug == '')
			$this->slug = $this->_get_unique_slug($this->title);

		return parent::insert_as_last_child($target);
	}

	/**
	 * Save category
	 *
	 * @param Validation $validation
	 *
	 * @return ORM
	 */
	public function save(Validation $validation = NULL)
	{
		if ($this->slug == '')
			$this->slug = $this->_get_unique_slug($this->title);

		return parent::save($validation);
	}

	/**
	 * products without a relationship move to uncategorized
	 */
	public function move_to_uncategorized()
	{
		$ids = ORM::factory('Shop_Product')
			->join(array('shop_products_categories', 'bpr'), 'LEFT')
			->on('bpr.product_id', '=', 'shop_product.id')
			->where('bpr.product_id', 'IS', NULL)
			->find_all()
			->as_array(NULL, 'id');

		if ( ! empty($ids))
		{
			// Add to uncategorized
			ORM::factory('Shop_Category', 2)->add('products', $ids);
		}
	}

	/**
	 * Get array of categories for html select tag
	 *
	 * @param null $k - for option tag
	 * @param null $v - for option tag
	 *
	 * @return array
	 */
	public static function for_select($k = NULL, $v = NULL)
	{
		return ORM::factory('Shop_Category', 1)
			->mptt_for_select('id', 'title', NDASH_NBSP, array($k, $v));
	}

	/**
	 * Get subs
	 *
	 * @return mixed
	 */
	public function get_subs()
	{
		return $this->subs
			->order_by('lft')
			->find_all();
	}

	/**
	 * Array for build menu categories
	 *
	 * @return array
	 */
	public static function categories_menu()
	{
		return DB::select('lvl', 'slug', 'title')
			->from('shop_categories')
			->where('parent_id', '>', 0)
			->where('enabled', '=', 1)
			->order_by('lft')
			->as_object()
			->execute();
	}

	/**
	 * Get array tree of categories for menu
	 *
	 * @return array
	 */
	public static function arr_tree_category()
	{
		$arr = DB::select('id', 'parent_id', 'lvl', 'slug', 'title')
			->from('shop_categories')
			->where('parent_id', '>', 1)
			->where('enabled', '=', 1)
			->where('show_in_menu', '=', 1)
			->order_by('lft')
			->execute()
			->as_array('id');

		$tree = array();

		foreach ($arr as $id => &$node)
		{
			if (isset($node['parent_id']))
			{
				if ($node['parent_id'] == 71)
					$tree[$id] = &$node;
				else
					$arr[$node['parent_id']]['sub'][$id] = &$node;
			}
		}

		return $tree;
	}


	//==================================================================================================================
	// Private

	/**
	 * Get unique slug by title for page
	 *
	 * @param $title
	 *
	 * @return string
	 */
	private function _get_unique_slug($title)
	{
		static $i = 1;
		$original = $slug = Inflector::slug($title);

		if ($slug === '')
			return;

		while ($product = ORM::factory('Shop_Category', array('slug' => $slug))
			AND $product->loaded()
			AND $product->id !== $this->id
		)
			$slug = $original . '-' . $i++;

		return $slug;
	}
}