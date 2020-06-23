<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Security
 *
 * Контроллер отвечает только за права на доступ к контроллерам и экшенам.
 * От него наследуются все остальное контроллеры, и контроллеры, которым не нужен Template, например AJAX
 *
 * Роли:
 * пользователь с ролью 1 - login   (в roles_users имеет role_id = 1)
 * пользователь с ролью 2 - admin   (в roles_users имеет role_id = 1,2)
 *
 */
class Controller_Security extends Controller_App
{
	protected $auth;

	/*
	 * Если в контроллере страницы указан $auth_required, то происходит защита всех action текущего контроллера
	 *
	 * Пример:
	 *
	 * public $auth_required = array('login', 'admin'); // Для доступа необходимы обе роли
	 *
	 */
	public $auth_required  = FALSE;

	/*
	 * При указании $secure_actions указываются права для доступа к каждому action.
	 * Экшены на которые не указаны права, будут доступны для всех.
	 *
	 * Пример:
	 *
	 * public $secure_actions = array(
	 *     'post'   => array('login', 'admin'),
	 *     'edit'   => array('login', 'admin'),
	 *     'delete' => array('login', 'admin')
	 * );
	 *
	 */
	public $secure_actions = FALSE;


	/**
	 * Before action
	 *
	 * @throws HTTP_Exception_403
	 */
	public function before()
	{
		parent::before();

		$this->auth = Auth::instance();

		$action_name = $this->request->action();

		// Проверяем права на доступ к текущей странице
		if
		(
			(
				(
					$this->auth_required !== FALSE
					AND $this->auth->logged_in($this->auth_required) === FALSE
				)
				OR
				(
					is_array($this->secure_actions)
					AND array_key_exists($action_name, $this->secure_actions)
					AND $this->auth->logged_in($this->secure_actions[$action_name]) === FALSE
				)
			)
		)
		{
			// Если нет прав и AJAX запрос, то выдаем эксепшен
			if ($this->auth->logged_in() AND $this->request->is_ajax())
			{
				throw new HTTP_Exception_403('Unauthorised access attempt');
			}
			// Если нет прав и обычный запрос - редирект
			else
			{
				throw new HTTP_Exception_403('Unauthorised access attempt');

				Message::set('error', __('Unauthorised access attempt!'));
				HTTP::redirect(Route::url('home'));
			}
		}
	}
}
