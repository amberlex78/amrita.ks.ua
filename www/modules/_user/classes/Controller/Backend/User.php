<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_User
 */
class Controller_Backend_User extends Controller_Backend
{
	public $module = 'user';

	// Block title on page
	public $block_title = 'user.users';


	/**
	 * List
	 */
	public function action_index()
	{
		$this->title = __('user.user_manager');

		// Save referer for go to back
		$this->save_referer($this->module);

		$id = $this->request->param('id');

		$obj = ORM::factory('User');

		$this->content = View::factory('user/backend/v_index',
			array(
				'id'         => $id,
				'pagination' => $pg = Pagination::get($obj->count_all(FALSE)),
				'obj'        => $obj->find_per_page($pg->offset, $pg->items_per_page, TRUE),
			));
	}

	/**
	 * Add
	 */
	public function action_add()
	{
		$this->title = __('user.user_add');

		$obj = ORM::factory('User');

		if ($this->request->is_post())
		{
			try
			{
				// Перечисляем поля для сохранения
				$obj->create_user($_POST, array(
					'username',
					'email',
					'password',
					'first_name',
					'last_name',
					'gender',
					'dob',
				));
				$obj->add('roles', ORM::factory('Role', array('name' => 'login')));

				// Т.к. юзер сохраняется не стандартным save()
				// изображения сохраняем отдельным методом
				$obj->save_image();

				Message::set('success', __('user.user_added'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('model');
				//d($errors);
			}
		}

		$this->content = View::factory('user/backend/v_form_add',
			array(
				'obj'         => $obj,
				'v_th_avatar' => AjaxImage::v_th_image('user.avatar', $this->user->id),
				'v_th_logo'   => AjaxImage::v_th_image('user.logo', $this->user->id),
			))
			->bind('errors', $errors);
	}

	/**
	 * Edit
	 */
	public function action_edit()
	{
		$this->title = __('user.edit_user_profile');

		$obj = ORM::factory('User', $this->request->param('id'));

		if ($this->request->is_post())
		{
			try
			{
				// Перечисляем поля для сохранения
				$obj->update_user($_POST, array(
					'first_name',
					'last_name',
					'gender',
					'dob',
					'password',
				));

				Message::set('success', __('user.profile_saved'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('model');
				//d($errors);
			}
		}

		$this->content = View::factory('user/backend/v_form_edit',
			array(
				'obj'         => $obj,
				'v_th_avatar' => AjaxImage::v_th_image('user.avatar', $obj->id, $obj->fimage_avatar),
				'v_th_logo'   => AjaxImage::v_th_image('user.logo', $obj->id, $obj->fimage_logo),
			))
			->bind('errors', $errors);
	}

	/**
	 * Delete
	 */
	public function action_delete()
	{
		$obj = ORM::factory('User', (int) $this->request->param('id'));

		// If user loaded and not admin
		if ($obj->loaded() AND ! $obj->has('roles', array(1, 2)))
		{
			$obj->delete();
			Message::set('success', __('user.user_deleted'));
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
				HTTP::redirect(ADMIN.'/user/edit/'.$id);

		$this->redirect_referer($this->module, ADMIN.'/user/index');
	}
}
