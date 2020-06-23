<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Posts
 */
class Controller_Backend_Posts extends Controller_Backend
{
	public $module = 'blog';

	// Block title on page
	public $block_title = 'blog.blog';

	/**
	 * List of posts
	 */
	public function action_list()
	{
		$this->_monitor_filter();

		$this->save_referer('blog_post');

		$id = $this->request->param('id');

		if ($id)
		{
			$obj = ORM::factory('Blog_Rubric', $id);

			$this->breadcrumbs = View::factory('blog/backend/v_breadcrumbs')
				->set('breadcrumbs', $obj->parents(FALSE, TRUE));

			$obj = $obj->posts;
		}
		else
			$obj = ORM::factory('Blog_Post');


		// Data for template
		$this->title   = __('blog.post_manager');
		$this->content = View::factory('blog/backend/posts/v_list',
			array(
				'for_select'   => Model_Blog_Rubric::for_select(1, __('app.all')),
				'selected'     => $id,
				'pagination'   => $pg = Pagination::get($obj->count_all(FALSE), Config::get('blog.per_page_backend')),
				'obj'          => $obj->find_per_page($pg->offset, $pg->items_per_page, TRUE),
				'config_image' => Config::get('blog.post.image'),
			));
	}

	/**
	 * Add new post
	 */
	public function action_add()
	{
		$obj = ORM::factory('Blog_Post');

		$rubric_ids = Arr::get($_POST, 'rubric_ids', array());

		$arr_tags = Arr::get($_POST, 'arr_tags');

		if ($this->request->is_post())
		{
			$obj->user_id = $this->user->id;

			$obj->pre_post();
			$obj->values($_POST);

			try
			{
				$obj->save();
				$obj->save_rubrics($rubric_ids);

				if (isset($this->modules['_tags']))
					$obj->save_tags($arr_tags);

				Message::set('success', __('blog.post_added'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}

		// Data for template
		$this->title   = __('blog.post_add');
		$this->content = View::factory('blog/backend/posts/v_form',
			array(
				'obj'            => $obj,
				'rubric_chboxes' => $obj->rubric_chboxes($rubric_ids),
				'v_th_image'     => AjaxImage::v_th_image('blog.post.image', $this->user->id),
			))
			->bind('arr_tags', $arr_tags)
			->bind('errors', $errors);
	}

	/**
	 * Edit post
	 */
	public function action_edit()
	{
		$id = $this->request->param('id');

		$obj = ORM::factory('Blog_Post', $id);

		if ( ! $obj->loaded())
			throw new HTTP_Exception_404();

		$arr_tags = Arr::get($_POST, 'arr_tags');

		if ($this->request->is_post())
		{
			$rubric_ids = Arr::get($_POST, 'rubric_ids', array());

			$obj->pre_post();
			$obj->values($_POST);

			try
			{
				$obj->save();
				$obj->save_rubrics($rubric_ids);

				if (isset($this->modules['_tags']))
					$obj->save_tags($arr_tags);

				Message::set('success', __('app.changes_saved'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}
		else
		{
			$rubric_ids = $obj->rubric_ids();

			// Get post tags
			if (isset($this->modules['_tags']))
				$arr_tags = $obj->get_arr_tags();
		}

		// Data for template
		$this->title   = __('blog.post_edit');
		$this->content = View::factory('blog/backend/posts/v_form',
			array(
				'obj'            => $obj,
				'rubric_chboxes' => $obj->rubric_chboxes($rubric_ids),
				'v_th_image'     => AjaxImage::v_th_image('blog.post.image', $obj->id, $obj->fimage_post),
			))
			->bind('arr_tags', $arr_tags)
			->bind('errors', $errors);
	}

	/**
	 * Delete
	 */
	public function action_delete()
	{
		$obj = ORM::factory('Blog_Post', $this->request->param('id'));

		if ($obj->loaded())
		{
			$obj->delete();
			Message::set('success', __('blog.post_deleted'));
		}
		else
			Message::set('error', __('app.error_deleting'));

		$this->_redirect();
	}


	//==================================================================================================================
	//  Private methods

	/**
	 * Redirect to saved referer or editing
	 *
	 * @param string $id
	 */
	private function _redirect($id = NULL)
	{
		if (Arr::get($_POST, 'save_and_stay') == 'ok')
			if ($id !== NULL)
				$this->redirect(ADMIN.'/posts/edit/'.$id);

		$this->redirect_referer('blog_post', ADMIN.'/posts/list');
	}

	/**
	 * If request is post - filter by rubric
	 */
	private function _monitor_filter()
	{
		if ($this->request->is_post())
		{
			$id = (int) $this->request->post('id');

			$id > 1
				? HTTP::redirect(ADMIN.'/posts/list/'.$id)
				: HTTP::redirect(ADMIN.'/posts/list');
		}
	}
}