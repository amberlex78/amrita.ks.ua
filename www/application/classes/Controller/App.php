<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class Controller_App
 */
class Controller_App extends Controller
{
	protected $config;
	protected $session;
	protected $language;
	protected $modules;

	public function before()
	{
		$this->config = Config::get('app');
		$this->session = Session::instance();
		$this->language = $this->config->language;

		// CMS modules
		foreach (Kohana::modules() as $module => $path) {
			if (stripos($module, '_') === 0) {
				$this->modules[$module] = $path;
			}
		}

		/**
		 * Sets the default timezone used by all date/time functions
		 *
		 * @link http://kohanaframework.org/guide/using.configuration
		 * @link http://www.php.net/manual/timezones
		 */
		date_default_timezone_set($this->config->timezone);

		/**
		 * Set locale information
		 *
		 * @link http://kohanaframework.org/guide/using.configuration
		 * @link http://www.php.net/manual/function.setlocale
		 */
		setlocale(LC_ALL, $this->config->languages[$this->language] . '.' . Kohana::$charset);

		/**
		 * Set app language
		 * Provides loading of language and translation
		 */
		I18n::lang($this->language);
	}

	/**
	 * Redirect to saved session referer
	 *
	 * @param        $event
	 * @param string $default_uri
	 * @param int $code
	 */
	protected function redirect_referer($event, $default_uri = '', $code = 302)
	{
		$uri = $this->session->get_once($event, $default_uri);
		HTTP::redirect($uri, $code);
	}

	/**
	 * Save referer to session
	 *
	 * @param      $name
	 * @param bool $referer
	 */
	protected function save_referer($name, $referer = true)
	{
		if ($referer === true) {
			$referer = $this->request->uri();
		} else {
			$referer = (string)$referer;
		}

		$referer = URL::base() . $referer . URL::query();

		$this->session->set($name, $referer);
	}

	/**
	 * Save referer initial to session
	 *
	 * @param      $name
	 * @param bool $referer
	 */
	protected function save_referer_initial($name, $referer = true)
	{
		if ($referer === true) {
			$referer = $this->request->initial()->uri();
		} else {
			$referer = (string)$referer;
		}

		$referer = URL::base() . $referer . URL::query();

		$this->session->set($name, $referer);
	}
}
