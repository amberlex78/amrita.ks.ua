<?php

define('DS', DIRECTORY_SEPARATOR);
define('SL', '/');
define('EXT', '.php');

$application = 'application';
$modules     = 'modules';
$system      = 'system';

error_reporting(E_ALL & ~E_DEPRECATED);


// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__)).DS);

if ( ! is_dir($application) AND is_dir(DOCROOT.$application))
	$application = DOCROOT.$application;

if ( ! is_dir($modules) AND is_dir(DOCROOT.$modules))
	$modules = DOCROOT.$modules;

if ( ! is_dir($system) AND is_dir(DOCROOT.$system))
	$system = DOCROOT.$system;

// Define the absolute paths for configured directories
define('APPPATH', realpath($application).DS);
define('MODPATH', realpath($modules).DS);
define('SYSPATH', realpath($system).DS);

// Clean up the configuration vars
unset($system, $application, $modules);

if ( ! defined('KOHANA_START_TIME'))
	define('KOHANA_START_TIME', microtime(TRUE));

if ( ! defined('KOHANA_START_MEMORY'))
	define('KOHANA_START_MEMORY', memory_get_usage());


// Название пути в URL к админке
// В PRODUCTION лучше изменить на секретное
define('ADMIN', 'admin');

// Разделитель в заголовке окна браузера
define('SEPARATOR', ' - ');

// Тире для отступа в <select>
define('NDASH_NBSP', '&ndash;&nbsp;');
define('MDASH_NBSP', '&mdash;&nbsp;');

// Папка для загрузки
define('UPLOAD_DIR', DOCROOT . 'uploads' . DS);
define('UPLOAD_URL', '/uploads/');

// Временная папка
define('IMG_TMP_DIR', UPLOAD_DIR . 'tmp' . DS);
define('IMG_TMP_URL', UPLOAD_URL . 'tmp/');


// Bootstrap the application
require APPPATH.'bootstrap'.EXT;


if (PHP_SAPI == 'cli') // Try and load minion
{
	class_exists('Minion_Task') OR die('Please enable the Minion module for CLI support.');
	set_exception_handler(array('Minion_Exception', 'handler'));

	Minion_Task::factory(Minion_CLI::options())->execute();
}
else
{
	/**
	 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
	 * If no source is specified, the URI will be automatically detected.
	 */
	echo Request::factory(TRUE, array(), FALSE)
		->execute()
		->send_headers(TRUE)
		->body();
}