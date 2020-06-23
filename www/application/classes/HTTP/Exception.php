<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Обработчик ошибок
 */
class HTTP_Exception extends Kohana_HTTP_Exception
{
	/**
	 * Generate a Response for all Exceptions without a more specific override
	 * The user should see a nice error page, however, if we are in development
	 * mode we should show the normal Kohana error page.
	 *
	 * @return Response
	 */
	public function get_response()
	{
		// Lets log the Exception, Just in case it's important!
		Kohana_Exception::log($this);

		if (Kohana::$environment >= Kohana::DEVELOPMENT) {
			// Show the normal Kohana error page.
			return parent::get_response();
		}
		else {
			$attributes = array('action' => 500);

			// Get error code as action name
			if ($this instanceof HTTP_Exception) {
				$attributes['action']  = $this->getCode();
				$attributes['message'] = $this->getMessage();
			}

			// Execute the query, addressed to the router for error handling
			return Request::factory(Route::get('error')
				->uri($attributes))
				->execute();
		}
	}
}
