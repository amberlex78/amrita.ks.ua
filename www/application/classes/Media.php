<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Class Media
 */
class Media
{
	private static $media     = NULL;
	private static $directory = NULL;

	/**
	 * Init vars
	 */
	private static function _init()
	{
		if (self::$media === NULL)
		{
			self::$media = Route::get('media');

			$arr = explode('_', Request::get('directory'));
			self::$directory = $arr[0];
		}
	}

	/**
	 * String included styles
	 *
	 * @param $module
	 *
	 * @return string
	 */
	public static function get_styles($module)
	{
		Media::_init();

		$css = array_unique(array_merge(
			Arr::get(Config::get('app_media.'.self::$directory.'.css'), 'app', array()),
			Media::_module($module, 'css')
		));

		$return = PHP_EOL;
		foreach ($css as $val)
		{
			if (strpos($val, '://') === FALSE)
				$return .= HTML::style(self::$media->uri(array('file' => self::$directory.'/css/'.$val))).PHP_EOL;
			else
				$return .= HTML::style($val).PHP_EOL;
		}

		return $return;
	}

	/**
	 * String included scripts
	 *
	 * @param $module
	 *
	 * @return array
	 */
	public static function get_scripts($module)
	{
		Media::_init();

		$css = array_unique(array_merge(
			Arr::get(Config::get('app_media.'.self::$directory.'.js'), 'app', array()),
			Media::_module($module, 'js')
		));

		$return = PHP_EOL;
		foreach ($css as $val)
		{
			if (strpos($val, '://') === FALSE)
				$return .= HTML::script(self::$media->uri(array('file' => self::$directory.'/js/'.$val))).PHP_EOL;
			else
				$return .= HTML::script($val).PHP_EOL;
		}

		return $return;
	}

	/**
	 * Get an array of media files of the module
	 *
	 * @param $module
	 * @param $type
	 *
	 * @return array
	 */
	private static function _module($module, $type)
	{
		$media      = Config::get($module.'_media.'.self::$directory.'.'.$type);
		$controller = Request::get('controller');
		$action     = Request::get('action');

		// Если нет массива файлов для контроллера
		if ( ! isset($media[$controller]))
			return array();

		$controller_items = array(); // Файлы для контроллера
		$action_items     = array(); // Файлы для экшена

		// Перебираем все значения
		foreach ($media[$controller] as $item_key => $item_val)
		{
			// Если ключ совпадает с экшеном
			if ($item_key === $action)
			{
				// Запоминаем массив значений для этого экшена
				$action_items = $media[$controller][$item_key];
			}
			// Иначе если значение не массив
			elseif ( ! is_array($item_val))
			{
				// Запоминаем в массив значение для этого контроллера
				$controller_items[] = $item_val;
			}
		}

		return array_merge($controller_items, $action_items);
	}
}