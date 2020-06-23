<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Feed
 */
class Controller_Feed extends Controller_App
{
	/**
	 * Before action
	 */
	public function before()
	{
		parent::before();

		// If not exist blog module
		if ( ! isset($this->modules['_blog']))
		{
			Message::set('error', __('app.error_not_found_blog',
				array(
					':module1' => __('app.module_name_feed'),
					':module2' => __('app.module_name_blog'))
				)
			);
			HTTP::redirect(Route::url('home'));
		}
	}

	/**
	 * RSS
	 */
	public function action_index()
	{
		$name = ltrim(rtrim(URL::base(), '/'), 'http://');

		$info = array(
			'title'       => HTML::chars($name).' — '.$this->config['sitename'],
			'description' => HTML::chars(Config::get('home.meta_d')),
		);

		$obj = ORM::factory('Blog_Post')
			->where('enabled', '=', 1)
			->order_by('created', 'DESC')
			->limit(10)
			->find_all();

		$items = array();
		foreach ($obj as $o)
		{
			$items[] = array(
				'title'       => HTML::chars($o->title),
				'description' => HTML::chars($o->preview),
				'link'        => Route::url('blog_post', array('slug' => $o->slug)),
				'pubDate'     => Date::format($o->created, Date::RSS)
			);
		}

		$this->response->body(Feed::create($info, $items));
	}
}