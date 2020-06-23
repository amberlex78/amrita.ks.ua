<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend
 */
class Controller_Backend extends Controller_Template
{
	// Default template
	public $template = 'app/backend/layout/v_default';

	/**
	 * Before action
	 */
	public function before()
	{
		parent::before();

		// If not admin
		if ( ! $this->is_admin)
		{
			// Redirect to log in
			HTTP::redirect(Route::url('backend_auth', array('action' => 'login')));
		}
	}

	/**
	 * After action
	 */
	public function after()
	{
		// Top admin menu
		$this->template->v_navigation_top = View::factory('app/backend/v_navigation_top');

		// Block name - Page name
		$this->template->v_legend = View::factory('app/backend/v_legend')
			->set('block_title', $this->block_title)
			->set('title', $this->title);

		$this->template->v_footer = View::factory('app/backend/v_footer');

		$this->template->v_navigation = View::factory('app/backend/v_navigation')
			->set('blocks', $this->_menu_modules())
			->set('curr_block', $this->module);

		parent::after();
	}

	/**
	 * Edit module interface
	 * (internationalization module)
	 */
	protected function module_i18n()
	{
		if ( ! isset($this->modules['_'.$this->module]))
			return FALSE;

		$data = Arr::get($_POST, 'data', array());

		if ($this->request->is_post())
		{
			$path = MODPATH.'_'.$this->module.DS.'i18n'.DS.$this->language.EXT;
			File::var_export($data, $path);

			Message::set('success', __('app.changes_saved'));
			HTTP::redirect(ADMIN.'/'.$this->module.'/i18n');
		}

		foreach(I18n::load($this->language) as $key => $val)
		{
			if (preg_match('/^'.$this->module.'\./', $key))
				$data[$key] = $val;
		}

		// Data for template
		$this->title   = __('app.i18n_edit_iface');
		$this->content = View::factory('app/backend/v_i18n')
			->bind('data', $data);
	}

	/**
	 * Array of admin menu
	 *
	 * @return array
	 */
	private function _menu_modules()
	{
		// Hidden menu for module
		$arr_hidden = array(
//			'_home'
//			, '_dashboard'
//			, '_contact'
		);

		// Move _home to end array
		$first = array_shift($this->modules);
		$this->modules['_home'] = $first;

		$arr_menu = array();
		foreach ($this->modules as $module => $path)
		{
			if ( ! in_array($module, $arr_hidden))
			{
				$m = trim($module, '_');

				if (is_file($path.'classes/Controller/Widgets/Menu'.ucfirst($m).EXT))
				{
					$arr_menu[$m] = json_decode(Widget::load('Menu'.ucfirst($m)), TRUE);
				}
			}
		}

		return array_reverse($arr_menu);
	}
}