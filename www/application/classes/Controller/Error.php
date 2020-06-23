<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Error controller
 */
class Controller_Error extends Controller_Frontend
{
	/**
	 * Before action
	 */
	public function before()
	{
		parent::before();

		// Получаем статус ошибки
		$status = (int) $this->request->action();

		// Если вызов из строки браузера (http://example.com/error/500)
		if (Request::$initial === Request::$current) {
			$status = 404;
			$this->response->status($status);
			$this->request->action($status);
		}
		else {
			// Если кода ошибки нет в списке обрабатываемых
			if (!in_array($status, array(403, 404, 500, 503))) {
				$status = 404;
				$this->response->status($status);
				$this->request->action($status);
			}
			else {
				$this->response->status($status);
				$message = $this->request->param('message');

				// Если стандартное сообщение 404
				if (UTF8::strpos($message, 'Unable to find a route to match the URI') !== FALSE) {
					// Не будем выводить message
					//$message = '';
				}
			}
		}

		$this->content = View::factory('errors/' . $status)
			->bind('message', $message);
	}

	/**
	 * Forbidden
	 */
	public function action_403()
	{
		$this->title = __('Error 403. Forbidden!');
	}

	/**
	 * Page not found
	 */
	public function action_404()
	{
		$this->title = __('Error 404. Page not found!');
	}

	/**
	 * Internal server error
	 */
	public function action_500()
	{
		$this->title = __('Error 500. Internal server error!');
	}

	/**
	 * Service unavailable
	 */
	public function action_503()
	{
		$this->title = __('Error 503. Service unavailable!');
	}
}