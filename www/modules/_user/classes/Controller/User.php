<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User controller
 */
class Controller_User extends Controller_Frontend
{
	public $module = 'user';

	/**
	 * User profile
	 */
	public function action_profile()
	{
		$username = $this->request->param('username');

		if ($username)
			$this->_profile_public($username);
		else
			$this->_profile_authorized();
	}

	/**
	 * Edit user profile (settings)
	 */
	public function action_settings()
	{
		if ( ! $this->user)
			HTTP::redirect(Route::url('auth', array('action' => 'login')));

		$this->title = __('user.change_profile');

		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
			->set('page_title', $this->title);

		$obj = ORM::factory('User', $this->user->id);

		if ($this->request->is_post())
		{
			try
			{
				$obj->update_user($_POST, array(
					'first_name',
					'last_name',
					'gender',
					'dob',
					'password',
				));

				Message::set('success', __('user.profile_saved'));
				HTTP::redirect(Route::url('user', array('action' => 'profile')));
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('model');
				//d($errors);
			}
		}

		$this->content = View::factory('user/v_form_edit')
			->set('obj', $obj)
			//->set('v_th_avatar', $v_th_avatar)
			//->set('v_th_logo', $v_th_logo)
			->bind('errors', $errors);
	}


	//==================================================================================================================
	// Private

	/**
	 * Logged in user's profile
	 */
	private function _profile_authorized()
	{
		if ( ! $this->user)
			HTTP::redirect(Route::url('auth', array('action' => 'login')));

		$this->title = __('user.your_profile');

		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
			->set('page_title', $this->title);

		// Data for template
		$this->content = View::factory('user/v_profile_authorized')
			->set('obj', $this->user);
	}

	/**
	 * @param null $username
	 *
	 * @throws HTTP_Exception_404
	 */
	private function _profile_public($username = NULL)
	{
		$obj = ORM::factory('User', array('username' => $username));

		if ( ! $obj->loaded())
			throw new HTTP_Exception_404();

		$this->title = __('user.profile');

		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
			->set('page_title', $this->title);

		// Data for template
		$this->content = View::factory('user/v_profile_public')
			->set('obj', $obj);
	}
}