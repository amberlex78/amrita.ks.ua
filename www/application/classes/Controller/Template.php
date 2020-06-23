<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class Controller_Template
 */
class Controller_Template extends Controller_Security
{
	public $auto_render = TRUE;

	protected $breadcrumbs = '';
	protected $title       = '';
	protected $keywords    = '';
	protected $description = '';
	protected $content     = '';

	protected $blocks_lft  = array();
	protected $blocks_rgt  = array();

	protected $user;
	protected $is_admin;

	/**
	 * Loads the template [View] object.
	 */
	public function before()
	{
		parent::before();

		// For use frontend and backend actions
		$this->user     = $this->auth->get_user();
		$this->is_admin = $this->auth->logged_in('admin');

		if ($this->auto_render === TRUE)
		{
			// Load the template
			$this->template = View::factory($this->template);

			$this->template->bind_global('user',     $this->user);
			$this->template->bind_global('is_admin', $this->is_admin);
			$this->template->bind_global('language', $this->language);
			$this->template->bind_global('config',   $this->config);
			$this->template->bind_global('modules',  $this->modules);
		}
	}

	/**
	 * Assigns the template [View] as the request response.
	 */
	public function after()
	{
		if ($this->auto_render === TRUE)
		{
			$this->template->bind_global('breadcrumbs', $this->breadcrumbs);

			$this->template->title       = $this->_get_title();
			$this->template->keywords    = $this->_get_keywords();
			$this->template->description = $this->_get_description();
			$this->template->content     = $this->content;

			$this->template->blocks_lft  = $this->blocks_lft;
			$this->template->blocks_rgt  = $this->blocks_rgt;

			$this->template->styles  = Media::get_styles($this->module);
			$this->template->scripts = Media::get_scripts($this->module);

			if (Kohana::$environment === Kohana::DEVELOPMENT)
				$this->template->profiler = View::factory('profiler/stats');

			$this->response->body($this->template->render());
		}

		parent::after();
	}

	/**
	 * Get title
	 * @return string
	 */
	private function _get_title()
	{
		if ( ! $this->title)
			return $this->config->sitename.SEPARATOR.Config::get('home.meta_t');

		if (Request::get('controller') == 'home')
			return $this->config->sitename.SEPARATOR.$this->title;

		return $this->title.SEPARATOR.$this->config->sitename;
	}

	/**
	 * Get meta keywords
	 * @return string
	 */
	private function _get_keywords()
	{
		if ( ! $this->keywords)
			return Config::get('home.meta_k');

		return $this->keywords;
	}

	/**
	 * Get meta description
	 * @return string
	 */
	private function _get_description()
	{
		if ( ! $this->description)
			return Config::get('home.meta_d');

		return $this->description;
	}
}
