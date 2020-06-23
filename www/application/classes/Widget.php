<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Widget class for HMVC
 */
class Widget
{
	protected $_route_name = 'widget';
	protected $_params = array();
	protected $_controller;
	protected $_action;

	/**
	 * Factory method
	 *
	 * @param       $widget_name
	 * @param       $widget_action
	 * @param array $params
	 * @param null  $route_name
	 *
	 * @return Response
	 */
	public static function load($widget_name, $widget_action = 'index', array $params = NULL, $route_name = NULL)
	{
		$widget = new Widget($widget_name, $widget_action, $params, $route_name);

		return $widget->render();
	}

	/**
	 * Constructor for init widget
	 *
	 * @param       $widget_name
	 * @param       $widget_action
	 * @param array $params
	 * @param null  $route_name
	 */
	private function __construct($widget_name, $widget_action = 'index', array $params = NULL, $route_name = NULL)
	{
		if ($params !== NULL) {
			$this->_params = $params;
		}

		if ($route_name !== NULL) {
			$this->_route_name = $route_name;
		}

		$this->_params['controller'] = $widget_name;
		$this->_params['action']     = $widget_action;
	}

	/**
	 * Execute request
	 *
	 * @return Response
	 */
	public function render()
	{
		$url = Route::get($this->_route_name)
			->uri($this->_params);

		return Request::factory($url)
			->execute();
	}
}