<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Model_Shop_Product
 */
class Model_Shop_Product extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'shop_products';

	/**
	 * A product has many categories and tags
	 * @var array Relationhips
	 */
	protected $_has_many = array
	(
		'categories' => array(
			'model'       => 'Shop_Category',
			'through'     => 'shop_products_categories',
			'foreign_key' => 'product_id',
			'far_key'     => 'category_id',
		),
		'tags' => array(
			'model'       => 'Tag',
			'through'     => 'shop_products_tags',
			'foreign_key' => 'product_id',
			'far_key'     => 'tag_id',
		),
	);

	protected $_belongs_to = array
	(
		'user' => array(
			'model'       => 'User',
			'foreign_key' => 'user_id',
		),
	);

	/**
	 * Auto-update columns for creation
	 * @var string
	 */
	protected $_created_column = array(
		'column' => 'created',
		'format' => 'Y-m-d H:i:s',
	);

	/**
	 * Auto-update columns for updates
	 * @var string
	 */
	protected $_updated_column = array(
		'column' => 'updated',
		'format' => 'Y-m-d H:i:s',
	);

	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns = array(
		/*
		'id'           => array('type'=>'int'),
		'user_id'      => array('type'=>'int'),
		'title'        => array('type'=>'string'),
		'preview'      => array('type'=>'string'),
		'text'         => array('type'=>'string'),
		'fimage_product'  => array('type'=>'string'),
		'enabled'      => array('type'=>'int'),
		'show_preview' => array('type'=>'int'),
		'show_image'   => array('type'=>'int'),
		'slug'         => array('type'=>'string'),
		'meta_t'       => array('type'=>'string'),
		'meta_k'       => array('type'=>'string'),
		'meta_d'       => array('type'=>'string'),
		'created'      => array('type'=>'string'),
		'updated'      => array('type'=>'string'),
		'pubdate'      => array('type'=>'string'),
		*/
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
			'price' => array(
				array('not_empty'),
			),
			'slug' => array(
				array('alpha_dash'),
			),
			'enabled' => array(
				array('in_array', array(':value', array(0, 1)))
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
			'title' => __('shop.product_title'),
			'price' => __('shop.product_price'),
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
			TRUE        => array(array('trim')),
			'title'     => array(array('strip_tags')),
			'slug'      => array(array('mb_strtolower')),
			'meta_t'    => array(array('strip_tags')),
			'meta_k'    => array(array('strip_tags')),
			'meta_d'    => array(array('strip_tags')),
		);
	}


	//==================================================================================================================
	//  Model public methods

	/**
	 * Prepare some POST values
	 */
	public function pre_post()
	{
		$_POST['preview'] = strip_tags(Arr::get($_POST, 'preview'), '<p>');

		if (trim(Arr::get($_POST, 'meta_t')) == '')
			$_POST['meta_t'] = Arr::get($_POST, 'title');

		if (trim(Arr::get($_POST, 'meta_d')) == '')
			$_POST['meta_d'] = Arr::get($_POST, 'title');

		if (trim(Arr::get($_POST, 'meta_k')) == '')
			$_POST['meta_k'] = Arr::get($_POST, 'title');

		// Чтоб не затиралась пустым полем из формы
		// т.к. сохраняется отдельно в связи с аяксом
		unset($_POST['fimage_product']);
	}

	/**
	 * Save product
	 *
	 * @param Validation $validation
	 *
	 * @return ORM
	 */
	public function save(Validation $validation = NULL)
	{
		if ($this->slug == '')
			$this->slug = $this->_get_unique_slug($this->title);

		parent::save($validation);

		if (Request::get('action') == 'add')
			$this->fimage_product = Model_User_Tmpimage::get_filename('shop.product.image', $this->id);

		return parent::save($validation);
	}

	/**
	 * Delete product
	 *
	 * @return ORM
	 */
	public function delete()
	{
		// Delete image files
		Model_User_Tmpimage::remove_obj_images('shop.product.image', $this->id, $this->fimage_product);

		return parent::delete();
	}

	/**
	 * Last products
	 *
	 * @param int $num
	 *
	 * @return Database_Result
	 */
	public static function last($num = 3)
	{
		return ORM::factory('Shop_product')
			->where('enabled', '=', 1)
			->order_by('created', 'DESC')
			->limit($num)
			->find_all();
	}


	//------------------------------------------------------------------------------------------------------------------
	// Categories for products

	/**
	 * Save categories of product
	 *
	 * @param $ids
	 */
	public function save_categories($ids)
	{
		if (empty($ids))
			$ids[] = 2;  // Uncategorized

		$this->remove('categories')
			->add('categories', $ids);
	}

	/**
	 * Get categories ids of product
	 *
	 * @return mixed
	 */
	public function category_ids()
	{
		return $this->categories
			->find_all()
			->as_array(NULL, 'id');
	}

	/**
	 * Get list html anchors by product (backend or frontend)
	 *
	 * @param null   $attributes
	 *
	 * @return string
	 */
	public function category_anchors($attributes = NULL)
	{
		$categories = '';

		if (Request::get('directory') == 'backend')
		{
			foreach($this->categories->find_all() as $category)
			{
				$categories .=
					HTML::anchor(
						ADMIN.'/products/list/'.$category->id,
						$category->title,
						array('rel' => 'tooltip', 'data-original-title' => __('shop.category_filter'))
					).', ';
			}

			return rtrim($categories, ', ');
		}
		else
		{
			foreach($this->categories->where('enabled', '=', 1)->find_all() as $category)
			{
				$categories .=
					HTML::anchor(
						Route::url('shop_category', array('slug' => $category->slug)),
						$category->title,
						$attributes
					).' | ';
			}

			return rtrim($categories, ' | ');
		}
	}


	/**
	 * Get all list checkboxes of categories
	 *
	 * @param $category_ids
	 *
	 * @return string html checkboxes
	 */
	public function category_chboxes($category_ids)
	{
		$category_chboxes = '';

		foreach (ORM::factory('Shop_Category', 1)->fulltree(FALSE) as $category)
		{
			$lvl = $category->lvl > 2
				? ' style="margin-left: '.(18 * ($category->lvl - 2)).'px;"'
				: '';

			$checkbox = Form::checkbox('category_ids[]', $category->id,
				in_array($category->id, $category_ids) ? TRUE : FALSE,
				$category->id == 71 ? ['disabled'] : []
			);

			$category_chboxes .= '<label class="checkbox"'.$lvl.'>'.$checkbox.$category->title.'</label>';
		}

		return $category_chboxes;
	}

	/**
	 * Get query for all products of category
	 *
	 * @param $id
	 *
	 * @return $this
	 */
	public function category_products($id)
	{
		// For all products of category and subcategories
		if (is_array($id))
		{
			$this->distinct(TRUE);
			$op = 'IN';
		}
		else
			$op = '=';

		return $this
			->select(
				'username'
				, 'first_name'
				, 'last_name'
			)
			->join('shop_products_categories')->on('shop_products_categories.product_id', '=', 'shop_product.id')
			->join('users', 'LEFT')->on('users.id', '=', 'shop_product.user_id')
			->where('shop_products_categories.category_id', $op, $id)
			->where('enabled', '=', 1);
	}

	/**
	 * Get name user of product
	 *
	 * @return mixed|string
	 */
	public function get_username()
	{
		return ($this->first_name AND $this->last_name)
			? HTML::chars($this->first_name.' '.$this->last_name)
			: $this->username;
	}

	//------------------------------------------------------------------------------------------------------------------
	// Tags for products

	/**
	 * Save tags
	 *
	 * @param $arr_tags
	 */
	public function save_tags($arr_tags)
	{
		$this->remove('tags');

		$ids = $this->tags
			->get_added_ids($arr_tags, 'product');

		if ($ids)
			$this->add('tags', $ids);
	}


	/**
	 * Get tags
	 *
	 * @return mixed
	 */
	public function get_tags()
	{
		return $this->tags
			->find_all();
	}

	/**
	 * Get tags as array
	 *
	 * @return string
	 */
	public function get_arr_tags()
	{
		return $this->tags
			->find_all()
			->as_array(NULL, 'name');
	}


	//------------------------------------------------------------------------------------------------------------------
	// products of category for sitemap

	public function for_sitemap()
	{
		return $this
			->where('enabled', '=', 1)
			->order_by('title')
			->find_all();
	}


	//==================================================================================================================
	//  Model private methods

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

		while ($product = ORM::factory('Shop_Product', array('slug' => $slug))
			AND $product->loaded()
			AND $product->id !== $this->id
		)
			$slug = $original . '-' . $i++;

		return $slug;
	}
}