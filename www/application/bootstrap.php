<?php defined('SYSPATH') or die('No direct script access.');

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

// App extends the core
require APPPATH.'classes/Kohana'.EXT;

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('America/Chicago');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');

/**
 * Cookie salt
 *
 * https://www.grc.com/passwords.htm
 */
Cookie::$salt = 'gE2KzhnzET4SqM8HyNzmd2wBoXlc3FOvxIhGpw047dqFXQTjeBbwcVt8ISnmAsa';

/**
 * Cookie expiration
 */
Cookie::$expiration = Date::MONTH * 3;

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
else
	if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
		Kohana::$environment = Kohana::DEVELOPMENT;
	else
		Kohana::$environment = Kohana::PRODUCTION;

// Выключаем уведомления и строгие ошибки
if (Kohana::$environment === Kohana::PRODUCTION)
	error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_DEPRECATED);

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
	'base_url'   => $_SERVER['REMOTE_ADDR'] == '127.0.0.1'
		? $_SERVER['BASE_URL_DEVELOPMENT']
		: $_SERVER['BASE_URL_PRODUCTION'],
	'index_file' => FALSE,
	'cache_life' => Date::MINUTE*10,
	'errors'     => Kohana::$environment !== Kohana::PRODUCTION,
	'profile'    => Kohana::$environment !== Kohana::PRODUCTION,
	'caching'    => Kohana::$environment === Kohana::PRODUCTION,
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Common routes
 */
require_once(APPPATH.'routes'.EXT);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(

	// CMS modules
	'_home'      => MODPATH.'_home',      // Home page module (required, must be first)

	// This modules names reverse in admin menu
	'_dashboard' => MODPATH.'_dashboard', // Admin dashboard  (required, for backend)
	'_user'      => MODPATH.'_user',      // User module (required)

	'_contact'   => MODPATH.'_contact',   // Module Contact form
	//'_tags'      => MODPATH.'_tags',      // Tag module
	//'_comments'  => MODPATH.'_comments',  // Comments
	'_blog'      => MODPATH.'_blog',      // Blog module
	'_shop'      => MODPATH.'_shop',      // Blog module
	//'_feed'      => MODPATH.'_feed',      // Feed RSS
	//'_static'    => MODPATH.'_static',    // Static pages module (must be last)

	// Kohana modules
	'cache'      => MODPATH.'cache',      // Caching with multiple backends
	'database'   => MODPATH.'database',   // Database access
	'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	'pagination' => MODPATH.'pagination', // Pagination
	'tb'         => MODPATH.'tb',         // Kohana Twitter Bootstrap module
	'ulogin'     => MODPATH.'ulogin'      // uLogin (https://github.com/ulogin/ulogin-Kohana)

	));

if ( ! Route::cache())
{
	Route::cache(Kohana::$environment === Kohana::PRODUCTION);
}

require_once(APPPATH.'vendor/PhpThumb/ThumbLib.inc'.EXT);
//require_once(APPPATH.'vendor/EMT'.EXT);

/**
 * Use PSR-0 autoloader to load compatible code from the vendor directory.
 */
spl_autoload_register(
	function ($class) {
		$directories = [
			//'vendor/DiDOM',
			//'vendor/NP',
		];

		foreach ($directories as $directory) {
			if (Kohana::auto_load($class, $directory)) {
				return;
			}
		}
	}
);


//======================================================================================================================
// Helpers (shorthand)

/**
 * print_r
 *
 * @param $var
 */
function pr($var)
{
	echo '<pre>' . print_r($var, true) . '</pre>';
}

/**
 * Die print_r
 *
 * @param $var
 */
function dpr($var)
{
	die('<pre>' . print_r($var, true) . '</pre>');
}

/**
 * Dump
 *
 * @param $var
 */
function d($var)
{
	echo Debug::vars($var);
}

/**
 * Die dump
 *
 * @param $var
 */
function dd($var)
{
	die(Debug::vars($var));
}
