<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Static
 */
class Controller_Static extends Controller_Frontend
{
	public $module = 'static';

	public function action_index()
	{
		$obj = ORM::factory('Static')
			->where('slug', '=', $this->request->param('slug'))
			->where('enabled', '=', 1)
			->find();

		if ( ! $obj->loaded())
			throw new HTTP_Exception_404();

		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
			->set('page_title', $obj->title);


		// Data for template
		$this->title       = $obj->meta_t;
		$this->keywords    = $obj->meta_k;
		$this->description = $obj->meta_d;

		$this->content = View::factory('static/v_index',
			array(
				'obj'          => $obj,
				'config_image' => Config::get('static.image'),
			));
	}
}
