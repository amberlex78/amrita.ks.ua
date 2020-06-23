<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Tags
 */
class Controller_Backend_Tags extends Controller_Backend
{
	public $module = 'tags';

	// Block title on page
	public $block_title = 'tags.tags';

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
			HTTP::redirect(Route::url('backend'));
		}
	}

	/**
	 * List
	 */
	public function action_index()
	{
		$this->title = __('tags.tag_manager');

		$this->save_referer($this->module);

		$obj = ORM::factory('Tag');

		$this->content = View::factory('tags/backend/v_index',
			array(
				'pagination' => $pg = Pagination::get($obj->count_all(FALSE)),
				'obj'        => $obj->find_per_page($pg->offset, $pg->items_per_page, TRUE),
			));
	}

	/**
	 * Add
	 */
	public function action_add()
	{
		$this->title = __('tags.tag_add');

		$obj = ORM::factory('Tag');

		if ($this->request->is_post())
		{
			$obj->pre_post();
			$obj->values($_POST, array('name', 'slug'));

			try
			{
				$obj->save();

				Message::set('success', __('tags.tag_added'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}

		$this->content = View::factory('tags/backend/v_form')
			->bind('obj', $obj)
			->bind('errors', $errors);
	}

	/**
	 * Edit
	 */
	public function action_edit()
	{
		$this->title = __('tags.tag_edit');

		$obj = ORM::factory('Tag', $this->request->param('id'));

		if ( ! $obj->loaded())
			throw new HTTP_Exception_404();

		if ($this->request->is_post())
		{
			$obj->pre_post();
			$obj->values($_POST, array('name', 'slug'));

			try
			{
				$obj->save();

				Message::set('success', __('tags.tag_edited'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}

		$this->content = View::factory('tags/backend/v_form')
			->bind('obj', $obj)
			->bind('errors', $errors);
	}

	/**
	 * Delete
	 */
	public function action_delete()
	{
		$obj = ORM::factory('Tag', $this->request->param('id'));

		if ($obj->loaded())
		{
			$obj->delete();
			Message::set('success', __('tags.tag_deleted'));
		}
		else
			Message::set('error', __('app.error_deleting'));

		$this->_redirect();
	}

	/**
	 * Edit module interface
	 * (internationalization module)
	 */
	public function action_i18n()
	{
		$this->module_i18n();
	}


	//==================================================================================================================
	// Private methods

	/**
	 * Uri to redirect
	 *
	 * @param string $id
	 */
	private function _redirect($id = NULL)
	{
		if (Arr::get($_POST, 'save_and_stay') == 'ok')
			if ($id !== NULL)
				HTTP::redirect(ADMIN.'/tags/edit/'.$id);

		$this->redirect_referer($this->module, ADMIN.'/tags/index');
	}
}
