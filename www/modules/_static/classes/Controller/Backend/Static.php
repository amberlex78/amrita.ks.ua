<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Static
 * Static pages
 */
class Controller_Backend_Static extends Controller_Backend
{
	public $module = 'static';

	// Block title on page
	public $block_title = 'static.pages';


	/**
	 * List
	 */
	public function action_index()
	{
		$this->title = __('static.pages');

		$this->save_referer($this->module);

		$obj = ORM::factory('Static');

		$this->content = View::factory('static/backend/v_index',
			array(
				'pagination'   => $pg = Pagination::get($obj->count_all(FALSE)),
				'obj'          => $obj->find_per_page($pg->offset, $pg->items_per_page, TRUE),
				'config_image' => Config::get('static.image'),
			));
	}

	/**
	 * Add
	 */
	public function action_add()
	{
		$this->title = __('static.page_add');

		$obj = ORM::factory('Static');

		if ($this->request->is_post())
		{
			$obj->pre_post();
			$obj->values($_POST);

			try
			{
				$obj->save();

				Message::set('success', __('static.page_added'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}

		$this->content = View::factory('static/backend/v_form',
			array(
				'obj'        => $obj,
				'v_th_image' => AjaxImage::v_th_image('static.image', $this->user->id),
			))
			->bind('errors', $errors);
	}

	/**
	 * Edit
	 */
	public function action_edit()
	{
		$this->title = __('static.page_edit');

		$obj = ORM::factory('Static', $this->request->param('id'));

		if ( ! $obj->loaded())
			throw new HTTP_Exception_404();

		if ($this->request->is_post())
		{
			$obj->pre_post();
			$obj->values($_POST);

			try
			{
				$obj->save();

				Message::set('success', __('app.changes_saved'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}

		$this->content = View::factory('static/backend/v_form',
			array(
				'obj'        => $obj,
				'v_th_image' => AjaxImage::v_th_image('static.image', $obj->id, $obj->fimage_static),
			))
			->bind('errors', $errors);
	}

	/**
	 * Delete
	 */
	public function action_delete()
	{
		$obj = ORM::factory('Static', $this->request->param('id'));

		if ($obj->loaded() AND $obj->allow_delete)
		{
			$obj->delete();
			Message::set('success', __('static.page_deleted'));
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

	/**
	 * Uri to redirect
	 *
	 * @param string $id
	 */
	private function _redirect($id = NULL)
	{
		if (Arr::get($_POST, 'save_and_stay') == 'ok')
			if ($id !== NULL)
				HTTP::redirect(ADMIN.'/static/edit/'.$id);

		$this->redirect_referer($this->module, ADMIN.'/static/index');
	}
}
