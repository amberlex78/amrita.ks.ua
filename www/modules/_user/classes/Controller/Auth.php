<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Frontend authorization controller
 * actions: login, logout, restore
 */
class Controller_Auth extends Controller_Frontend
{
	public $module = 'auth';
	public $template = 'app/layout/v_one_column';

	/**
	 * Registration
	 */
	public function action_signup()
	{
		$this->title = __('user.registration');

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
				));
				$obj->add('roles', ORM::factory('Role', array('name' => 'login')));

				$this->auth->force_login($obj->username);

				Message::set('success', __('user.user_added'));
				HTTP::redirect(Route::url('user', array('action' => 'profile')));
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('model');
				//d($errors);
			}
		}

		// Data for template
		$this->content = View::factory('auth/v_signup')
			->set('obj', $obj)
			->bind('errors', $errors);;
	}

	/**
	 * Log in
	 */
	public function action_login()
	{
		$this->title = __('user.authorization');

		if ($this->request->is_post())
		{
			// If not logged
			if ( ! $this->auth->login(
				$this->request->post('email'),
				$this->request->post('password'),
				(bool) $this->request->post('remember'))
			)
			{
				Message::set('error', __('user.error_auth'));
				HTTP::redirect(Route::url('auth', array('action' => 'login')));
			}
		}

		// If user as user
		if ($this->auth->logged_in())
		{
			// На фронтенд странице профиля уже есть в названии приветствие,
			// поэтому выводить такое же сообщение приветствия не обязательно
			//Message::set('success', __('user.hello_username', array(':username' => $this->auth->get_user()->username)));
			HTTP::redirect(Route::url('user', array('action' => 'profile')));
		}

		// Data for template
		$this->content = View::factory('auth/v_login');
	}

	/**
	 * Restore password
	 */
	public function action_restore()
	{
		if ($this->auth->logged_in())
			HTTP::redirect(Route::url('user', array('action' => 'profile')));

		$this->title = __('user.restore_password');

		if ($this->request->is_post())
		{
			// Object email validation
			$post = Validation::factory($_POST)
				->rule('email', 'not_empty')
				->rule('email', 'email');

			if ( ! $post->check())
			{
				Message::set('error', __('user.wrong_email_format'));
				HTTP::redirect(Route::url('auth', array('action' => 'restore')));
			}

			// Get user by email
			$o_user = ORM::factory('User', array('email' => $post['email']));

			// If user not loaded
			if ( ! $o_user->loaded())
			{
				Message::set('error', __('user.no_such_email'));
				HTTP::redirect(Route::url('auth', array('action' => 'restore')));
			}

			// Create link with token to recovery password
			$url_restore_confirmed = HTML::anchor(
				Route::url('auth', array(
					'action'    => 'restore_confirmed',
					'passtoken' => Model_User_Recovery_Password::get_token($o_user->id)
				)),
				__('user.restore_url_text')
			);

			// Send new password
			Mail::sendmail(
				$o_user->email,
				$this->config->email_admin,
				$this->config->sitename_subject.__('user.restore_password'),
				__('user.restore_text', array(':url' => $url_restore_confirmed))
			);

			Message::set('success', __('user.restore_instructions', array(':email' => $o_user->email)));
			HTTP::redirect(Route::url('auth', array('action' => 'login')));
		}

		// Data for template
		$this->content = View::factory('auth/v_restore');
	}

	/**
	 * Confirmed password change
	 */
	public function action_restore_confirmed()
	{
		$passtoken = $this->request->param('passtoken');

		if ($passtoken === NULL)
			HTTP::redirect(Route::url('auth', array('action' => 'login')));

		// Ищем токен
		$o_recovery = ORM::factory('User_Recovery_Password', array('token' => $passtoken));

		if ( ! $o_recovery->loaded())
			HTTP::redirect(Route::url('auth', array('action' => 'login')));

		// If current time > time recovery password
		if (time() > $o_recovery->expires)
		{
			$o_recovery->delete();

			Message::set('error', __('user.time_recovery_password_expired'));
			HTTP::redirect(Route::url('auth', array('action' => 'login')));
		}

		if ($this->request->is_post())
		{
			$o_validation = Model_User::get_password_validation($_POST)
				->rule('password', 'not_empty')
				->rule('password_confirm', 'not_empty')
				->labels(array(
					'password'         => __('user.new_password'),
					'password_confirm' => __('user.password_confirm'),
				));

			if ($o_validation->check())
			{
				$o_recovery->user->password = $o_validation['password'];
				$o_recovery->user->save();

				$this->auth->force_login($o_recovery->user->username);

				$o_recovery->delete();

				Message::set('success', __('user.password_changed'));
				HTTP::redirect(Route::url('user', array('action' => 'profile')));
			}
			else
				$errors = $o_validation->errors('validation');
		}

		// Data for template
		$this->content = View::factory('auth/v_restore_confirmed')
			->bind('errors', $errors);
	}

	/**
	 * Log out
	 */
	public function action_logout()
	{
		if ($this->auth->logout())
			HTTP::redirect(Route::url('home'));
	}
}
