<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Model_Tag
 */
class Model_Tag extends ORM
{
	/**
	 * Many-To-Many
	 * @var array
	 */
	protected $_has_many = array
	(
		// Tags - Posts
		'posts' => array(
			'model'       => 'Blog_Post',
			'through'     => 'blog_posts_tags',
			'foreign_key' => 'tag_id',
			'far_key'     => 'post_id',
		),
	);

	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns = array(
		'id'   => array('type'=>'int'),
		'name' => array('type'=>'string'),
		'slug' => array('type'=>'string'),
		'type' => array('type'=>'string'),
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
			'name' => array(
				array('not_empty'),
			),
			'slug' => array(
				array('alpha_dash'),
			),
			'type' => array(
				array('in_array', array(':value', Config::get('tags.valid_types')))
			),
		);
	}

	/**
	 * Labels
	 * @return array
	 */
	public function labels()
	{
		return array('name' => __('tags.tag'));
	}

	/**
	 * Filters
	 * @return array
	 */
	public function filters()
	{
		return array(
			TRUE   => array(array('trim')),
			'name' => array(array('strip_tags')),
			'slug' => array(array('mb_strtolower')),
		);
	}


	//==================================================================================================================
	//  Model public methods

	/**
	 * Prepare some POST values
	 */
	public function pre_post()
	{
		$_POST['type'] = 'post';
	}

	/**
	 * Save page
	 *
	 * @param Validation $validation
	 *
	 * @return ORM
	 */
	public function save(Validation $validation = NULL)
	{
		if ($this->slug == '')
			$this->slug = $this->_get_unique_slug($this->name, $this->type);

		return parent::save($validation);
	}

	/**
	 * Added tegs and return ids tags
	 *
	 * @param $arr_tags
	 * @param $type
	 *
	 * @return array|bool
	 */
	public function get_added_ids($arr_tags, $type)
	{
		if ( ! is_array($arr_tags))
			return FALSE;

		if (empty($arr_tags))
			return FALSE;

		$arr_tags = array_unique(array_map('trim', array_map('strip_tags', $arr_tags)));

		$o_tag = ORM::factory('Tag');

		// Перебираем полученные теги, чтобы добавить в таблицу уникальные
		foreach ($arr_tags as $name)
		{
			if ($name != '' AND $this->_is_unique($name, $type))
			{
				$o_tag->name = $name;
				$o_tag->type = $type;

				$o_tag->save()->clear();
			}
		}

		if ($arr_tags)
			return ORM::factory('Tag')
				->where('name', 'IN', $arr_tags)
				->where('type', '=', $type)
				->find_all()
				->as_array(NULL, 'id');

		return FALSE;
	}

	/**
	 * Get json names tags for bootstrap-tagmanager.js
	 *
	 * @param $type
	 *
	 * @return array
	 */
	public static function get_json_tags($type)
	{
		$q = "SELECT `name` FROM `tags` WHERE `type` = :type";

		return json_encode(
			DB::query(Database::SELECT, $q)
				->param(':type', $type)
				->execute()
				->as_array(NULL, 'name')
		);
	}


	//------------------------------------------------------------------------------------------------------------------
	// Frontend

	/**
	 * Get tags as object for tag cloud
	 *
	 * @param string $modules
	 * @param string $orderby
	 * @param string $orderto
	 *
	 * @return object
	 */
	public static function get_for_cloud($modules, $orderby = 'count', $orderto = 'DESC')
	{
		if (isset($modules['_blog']))
		{
			$res = self::_select_cloud_blog();
		}

		return DB::select('name', 'slug', array(DB::expr('SUM(`count`)'), 'count'))
			->from(array($res, 'union'))
			->order_by($orderby, $orderto)
			->group_by('name')
			->as_object()
			->execute();
	}

	/**
	 * Get query for cloud tags with count
	 *
	 * @return $this
	 */
	private static function _select_cloud_blog()
	{
		return DB::select(
			array('t.name', 'name'),
			array('t.slug', 'slug'),
			array(DB::expr('COUNT(*)'), 'count')
		)
			->from(array('blog_posts_tags', 'bpt'))
			->where('bp.enabled', '=', 1)
			->join(array('tags', 't'))->on('bpt.tag_id', '=', 't.id')
			->join(array('blog_posts', 'bp'))->on('bpt.post_id', '=', 'bp.id')
			->group_by('t.id');
	}

	/**
	 * Get query
	 *
	 * @param $modules
	 * @param $o_tags
	 *
	 * @return $this
	 */
	public function get_select($modules, $o_tags)
	{
		if (isset($modules['_blog']))
		{
			$res = $this->_select_blog($o_tags);
		}

		return $res;
	}

	/**
	 * Get query for select tag with posts
	 *
	 * @param $o_tags
	 *
	 * @return $this
	 */
	private function _select_blog($o_tags)
	{
		return DB::select(
			array('bp.id',          'id'),
			array('bp.title',       'title'),
			array('bp.slug',        'slug'),
			array('bp.preview',     'preview'),
			array('bp.fimage_post', 'fimage'),
			array('bp.created',     'created'),
			array('t.type',         'type')
		)
			->from(array('blog_posts', 'bp'))
			->where('enabled', '=', 1)
			->join(array('blog_posts_tags', 'bpt'))->on('bpt.post_id', '=', 'bp.id')
			->join(array('tags', 't'))->on('t.id', '=', 'bpt.tag_id')
			->where('bpt.tag_id', 'IN', $o_tags);
	}

	/**
	 * Get pagination object
	 *
	 * @param $res
	 *
	 * @return Pagination
	 */
	public function get_pagination($res)
	{
		// Total records found
		$total_items = DB::select(array(DB::expr('COUNT(*)'), 'total_items'))
			->from(array($res, 'combined_table'))
			->as_object()
			->execute()
			->get('total_items');

		// Pagination
		return Pagination::factory(array(
			'total_items'    => $total_items,
			'items_per_page' => Config::get('app.per_page_frontend'),
			'view'           => 'app/v_pagination'
		));
	}

	/**
	 * All records that include tags
	 *
	 * @param $res
	 * @param $offset
	 * @param $items_per_page
	 *
	 * @return object
	 */
	public function get_items($res, $offset, $items_per_page)
	{
		return DB::select()
			->from(array($res, 'combined_table'))
			->limit($items_per_page)
			->offset($offset)
			->order_by('created', 'DESC')
			->as_object()
			->execute();
	}


	//==================================================================================================================
	//  Model private methods

	/**
	 * Check by unique name
	 *
	 * @param $name
	 * @param $type
	 *
	 * @return bool
	 */
	private function _is_unique($name, $type)
	{
		return ! (bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))
			->from($this->_table_name)
			->where($this->_primary_key, '!=', $this->pk())
			->where('name', '=', $name)
			->where('type', '=', $type)
			->execute($this->_db)
			->get('total_count');
	}

	/**
	 * Get unique slug by title for page
	 *
	 * @param $title
	 * @param $type
	 *
	 * @return string
	 */
	private function _get_unique_slug($title, $type)
	{
		static $i = 1;
		$original = $slug = Inflector::slug($title);

		while ($post = ORM::factory('Tag', array('slug' => $slug, 'type' => $type))
			AND $post->loaded()
			AND $post->id !== $this->id
		)
			$slug = $original . '-' . $i++;

		return $slug;
	}
}