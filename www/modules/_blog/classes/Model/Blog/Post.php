<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Model_Blog_Post
 */
class Model_Blog_Post extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'blog_posts';

	/**
	 * A post has many rubrics and tags
	 * @var array Relationhips
	 */
	protected $_has_many = array
	(
		'rubrics' => array(
			'model'       => 'Blog_Rubric',
			'through'     => 'blog_posts_rubrics',
			'foreign_key' => 'post_id',
			'far_key'     => 'rubric_id',
		),
		'tags' => array(
			'model'       => 'Tag',
			'through'     => 'blog_posts_tags',
			'foreign_key' => 'post_id',
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
		'id'           => array('type'=>'int'),
		'user_id'      => array('type'=>'int'),
		'title'        => array('type'=>'string'),
		'preview'      => array('type'=>'string'),
		'text'         => array('type'=>'string'),
		'fimage_post'  => array('type'=>'string'),
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
			'title' => __('blog.post_title'),
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
		unset($_POST['fimage_post']);
	}

	/**
	 * Save post
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
			$this->fimage_post = Model_User_Tmpimage::get_filename('blog.post.image', $this->id);

		return parent::save($validation);
	}

	/**
	 * Delete post
	 *
	 * @return ORM
	 */
	public function delete()
	{
		// Delete image files
		Model_User_Tmpimage::remove_obj_images('blog.post.image', $this->id, $this->fimage_post);

		return parent::delete();
	}

	/**
	 * Last posts
	 *
	 * @param int $num
	 *
	 * @return Database_Result
	 */
	public static function last($num = 3)
	{
		return ORM::factory('Blog_post')
			->where('enabled', '=', 1)
			->order_by('created', 'DESC')
			->limit($num)
			->find_all();
	}


	//------------------------------------------------------------------------------------------------------------------
	// Rubrics for posts

	/**
	 * Save rubrics of post
	 *
	 * @param $ids
	 */
	public function save_rubrics($ids)
	{
		if (empty($ids))
			$ids[] = 2;  // Uncategorized

		$this->remove('rubrics')
			->add('rubrics', $ids);
	}

	/**
	 * Get rubrics ids of post
	 *
	 * @return mixed
	 */
	public function rubric_ids()
	{
		return $this->rubrics
			->find_all()
			->as_array(NULL, 'id');
	}

	/**
	 * Get list html anchors by post (backend or frontend)
	 *
	 * @param null   $attributes
	 *
	 * @return string
	 */
	public function rubric_anchors($attributes = NULL)
	{
		$rubrics = '';

		if (Request::get('directory') == 'backend')
		{
			foreach($this->rubrics->find_all() as $rubric)
			{
				$rubrics .=
					HTML::anchor(
						ADMIN.'/posts/list/'.$rubric->id,
						$rubric->title,
						array('rel' => 'tooltip', 'data-original-title' => __('blog.rubric_filter'))
					).', ';
			}

			return rtrim($rubrics, ', ');
		}
		else
		{
			foreach($this->rubrics->where('enabled', '=', 1)->find_all() as $rubric)
			{
				$rubrics .=
					HTML::anchor(
						Route::url('blog_rubric', array('slug' => $rubric->slug)),
						$rubric->title,
						$attributes
					).' | ';
			}

			return rtrim($rubrics, ' | ');
		}
	}


	/**
	 * Get all list checkboxes of rubrics
	 *
	 * @param $rubric_ids
	 *
	 * @return string html checkboxes
	 */
	public function rubric_chboxes($rubric_ids)
	{
		$rubric_chboxes = '';

		foreach (ORM::factory('Blog_Rubric', 1)->fulltree(FALSE) as $rubric)
		{
			$lvl = $rubric->lvl > 2
				? ' style="margin-left: '.(18 * ($rubric->lvl - 2)).'px;"'
				: '';

			$checkbox = Form::checkbox('rubric_ids[]', $rubric->id, in_array($rubric->id, $rubric_ids) ? TRUE : FALSE);

			$rubric_chboxes .= '<label class="checkbox"'.$lvl.'>'.$checkbox.$rubric->title.'</label>';
		}

		return $rubric_chboxes;
	}

	/**
	 * Get query for all posts of rubric
	 *
	 * @param $id
	 *
	 * @return $this
	 */
	public function rubric_posts($id)
	{
		// For all posts of rubric and subrubrics
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
			->join('blog_posts_rubrics')->on('blog_posts_rubrics.post_id', '=', 'blog_post.id')
			->join('users', 'LEFT')->on('users.id', '=', 'blog_post.user_id')
			->where('blog_posts_rubrics.rubric_id', $op, $id)
			->where('enabled', '=', 1);
	}

	/**
	 * Get name user of post
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
	// Tags for posts

	/**
	 * Save tags
	 *
	 * @param $arr_tags
	 */
	public function save_tags($arr_tags)
	{
		$this->remove('tags');

		$ids = $this->tags
			->get_added_ids($arr_tags, 'post');

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
	// posts of rubric for sitemap

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

		while ($post = ORM::factory('Blog_Post', array('slug' => $slug))
			AND $post->loaded()
			AND $post->id !== $this->id
		)
			$slug = $original . '-' . $i++;

		return $slug;
	}
}