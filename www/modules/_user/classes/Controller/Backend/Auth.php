<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Backend authorization controller (for admin)
 * actions: login, logout, restore
 */
class Controller_Backend_Auth extends Controller_Template
{
	public $module = 'auth';

	// Auth admin template
	public $template = 'auth/backend/layout/v_auth';

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
				HTTP::redirect(Route::url('backend_auth', array('action' => 'login')));
			}
		}

		// If user is admin
		if ($this->auth->logged_in('admin'))
		{
			if ( ! $this->session->get('success'))
				Message::set('success', __('user.hello_username', array(':username' => $this->auth->get_user()->username)));

			HTTP::redirect(Route::url('backend'));
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
		$this->content = View::factory('auth/backend/v_login');
	}

	/**
	 * Restore password for admin
	 */
	public function action_restore()
	{
		if ($this->auth->logged_in())
			HTTP::redirect(Route::url('backend_auth', array('action' => 'login')));

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
				HTTP::redirect(Route::url('backend_auth', array('action' => 'restore')));
			}

			// Get user by email
			$o_user = ORM::factory('User', array('email' => $post['email']));

			// If user not loaded
			if ( ! $o_user->loaded())
			{
				Message::set('error', __('user.no_such_email'));
				HTTP::redirect(Route::url('backend_auth', array('action' => 'restore')));
			}

			// Is admin?
			if ($o_user->has('roles', 2))
			{
				// Create link with token to recovery password
				$url_restore_confirmed = HTML::anchor(
					Route::url('backend_auth', array(
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
				HTTP::redirect(Route::url('backend_auth', array('action' => 'login')));
			}
		}

		// Data for template
		$this->content = View::factory('auth/backend/v_restore');
	}

	/**
	 * Confirmed password change
	 */
	public function action_restore_confirmed()
	{
		$passtoken = $this->request->param('passtoken');

		if ($passtoken === NULL)
			HTTP::redirect(Route::url('backend_auth', array('action' => 'login')));

		// Ищем токен
		$o_recovery = ORM::factory('User_Recovery_Password', array('token' => $passtoken));

		if ( ! $o_recovery->loaded())
			HTTP::redirect(Route::url('backend_auth', array('action' => 'login')));

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
				HTTP::redirect(Route::url('backend'));
			}
			else
				$errors = $o_validation->errors('validation');
		}

		// Data for template
		$this->content = View::factory('auth/backend/v_restore_confirmed')
			->bind('errors', $errors);
	}

	/**
	 * Log out
	 */
	public function action_logout()
	{
		if ($this->auth->logout())
			HTTP::redirect(Route::url('backend_auth', array('action' => 'login')));
	}

	/**
	 * Создание пользователей
	 *
	 * 1. Добавить в роут `backend_auth` для action значение install
	 * 2. Создать пользователей по ссылке http://mysite/admin/auth/install
	 * 3. Удалить для action значение install
	 */
	public function action_install()
	{
		$users = $this->_get_init_users();

		foreach ($users as $user)
		{
			$obj = ORM::factory('User');
			$obj->values($user);
			$obj->save();

			foreach ($user['roles'] as $role)
			{
				$obj->add('roles', ORM::factory('Role', array('name' => $role)));
			}
		}

		// Сообщение
		$message = View::factory('auth/backend/v_installed')
			->set('users', $users)
			->render();

		// Сообщение
		Message::set('success', $message);

		// Редирект
		HTTP::redirect(Route::url('backend_auth', array('action' => 'login')));
	}

	/**
	 * Array of users for init
	 *
	 * @return array
	 */
	private function _get_init_users()
	{
		return array(
			array(
				'username' => 'amberlex',
				'email'    => 'amberlex78@gmail.com',
				'password' => '1q2w3e4r',
				'roles'    => array('login', 'admin')
			),
			array(
				'username' => 'leonatti',
				'email'    => 'leonatti.ks@gmail.com',
				'password' => '1q2w3e4r',
				'roles'    => array('login', 'admin')
			),
		);
	}
}
