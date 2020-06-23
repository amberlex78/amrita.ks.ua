<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Tags
 */
class Controller_Tags extends Controller_Frontend
{
	public $module = 'tags';

	/**
	 * Before action
	 */
	public function before()
	{
		parent::before();

		// If not exist blog module
		if ( ! isset($this->modules['_blog']))
		{
			Message::set('error', __('app.error_not_found_blog',
					array(
						':module1' => __('app.module_name_tags'),
						':module2' => __('app.module_name_blog'))
				)
			);
			HTTP::redirect(Route::url('home'));
		}
	}

	/**
	 * List tags or list posts by select tag
	 */
	public function action_index()
	{
		$slug = $this->request->param('slug');

		if ( ! $slug)
		{
			$this->title = __('tags.all_tags');

			$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
				->set('page_title', $this->title);

			$o_tags = ORM::factory('Tag');

			$this->content = View::factory('tags/v_all')
				->set('title', $this->title)
				->set('obj', $o_tags->get_for_cloud($this->modules));
		}
		else
		{
			$o_tag = ORM::factory('Tag', array('slug' => $this->request->param('slug')));

			if ( ! $o_tag->loaded())
				HTTP::redirect(Route::url('tags'));

			// ids of the same tag
			$o_tags = ORM::factory('Tag')
				->where('slug', '=', $this->request->param('slug'))
				->find_all()
				->as_array(NULL, 'id');

			// Get query select
			// Included fields: 'id', 'title', 'slug', 'preview', 'fimage', 'created', 'type'
			$res_select = $o_tag->get_select($this->modules, $o_tags);

			$this->breadcrumbs = View::factory('tags/partials/v_breadcrumbs')
				->set('page_title', $o_tag->name);

			// Data for template
			$this->title = $o_tag->name;
			$this->content = View::factory('tags/v_index', array(
				'config_image_post' => isset($this->modules['_blog']) ? Config::get('blog.post.image') : '',
				'tag_name'          => $o_tag->name,
				'pagination'        => $pg = $o_tag->get_pagination($res_select),
				'obj'               => $o_tag->get_items($res_select, $pg->offset, $pg->items_per_page),
			));
		}
	}
}