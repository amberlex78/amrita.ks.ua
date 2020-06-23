<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Model_Static
 */
class Model_Static extends ORM
{
	protected $_table_name = 'static';

	protected $_table_columns = array(
		'id'            => array('type'=>'int'),
		'title'         => array('type'=>'string'),
		'title_menu'    => array('type'=>'string'),
		'text'          => array('type'=>'string'),
		'fimage_static' => array('type'=>'string'),
		'show_image'    => array('type'=>'int'),
		'enabled'       => array('type'=>'int'),
		'show_in_menu'  => array('type'=>'int'),
		'sort_in_menu'  => array('type'=>'int'),
		'allow_delete'  => array('type'=>'int'),
		'slug'          => array('type'=>'string'),
		'meta_t'        => array('type'=>'string'),
		'meta_d'        => array('type'=>'string'),
		'meta_k'        => array('type'=>'string'),
	);

	//protected $_reload_on_wakeup = FALSE;

	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty')
			),
			'slug' => array(
				array('alpha_dash')
			),
			'enabled' => array(
				array('in_array', array(':value', array(0, 1)))
			),
		);
	}

	public function labels()
	{
		return array(
			'title'  => __('static.page_title'),
			'slug'   => __('app.seo_slug'),
		);
	}

	public function filters()
	{
		return array(
			TRUE         => array(array('trim')),
			'title'      => array(array('strip_tags')),
			'title_menu' => array(array('strip_tags')),
			'slug'       => array(array('mb_strtolower')),
			'meta_t'     => array(array('strip_tags')),
			'meta_k'     => array(array('strip_tags')),
			'meta_d'     => array(array('strip_tags')),
		);
	}


//======================================================================================================================

	/**
	 * Get pages for top menu (select only slug and title for menu)
	 *
	 * @return mixed
	 */
	public static function get_for_menu()
	{
		return DB::select('slug', 'title_menu')
			->from('static')
			->where('show_in_menu', '=', 1)
			->where('enabled', '=', 1)
			->order_by('sort_in_menu')
			->as_object()
			->execute();
	}

	/**
	 * Prepare POST title_menu, meta_t
	 */
	public function pre_post()
	{
		unset ($_POST['allow_delete']);

		if (trim(Arr::get($_POST, 'title_menu')) == '')
			$_POST['title_menu'] = Arr::get($_POST, 'title');

		if (trim(Arr::get($_POST, 'meta_t')) == '')
			$_POST['meta_t'] = Arr::get($_POST, 'title');

		if (trim(Arr::get($_POST, 'meta_d')) == '')
			$_POST['meta_d'] = Arr::get($_POST, 'title');

		// Чтоб не затиралась пустым полем из формы
		// т.к. сохраняется отдельно в связи с аяксом
		unset($_POST['fimage_static']);
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
			$this->slug = $this->_get_unique_slug($this->title);

		parent::save($validation);

		if (Request::get('action') == 'add')
			$this->fimage_static = Model_User_Tmpimage::get_filename('static.image', $this->id);

		return parent::save($validation);
	}

	/**
	 * Delete page
	 *
	 * @return ORM
	 */
	public function delete()
	{
		// Delete image files
		Model_User_Tmpimage::remove_obj_images('static.image', $this->id, $this->fimage_static);

		return parent::delete();
	}

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

		while ($post = ORM::factory('Static', array('slug' => $slug))
			AND $post->loaded()
			AND $post->id !== $this->id
		)
			$slug = $original . '-' . $i++;

		return $slug;
	}
}