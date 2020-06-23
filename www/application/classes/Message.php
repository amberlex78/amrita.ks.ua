<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Flash message class
 */
class Message
{
	/**
	 * Set flash messages
	 *
	 *     Message::set('success', 'Article added');
	 *
	 * @param  null   $type  available types: success, error, info, warning
	 * @param  string $text  text message to display
	 *
	 * @return string
	 */
	public static function set($type = NULL, $text = '')
	{
		Session::instance()->set('type', $type); // Type message
		Session::instance()->set($type, $text);  // Text message
	}

	/**
	 * Get flash messages (after redirect)
	 *
	 * <code>
	 * <?php
	 *     Message::get();
	 * ?>
	 * </code>
	 *
	 * @return bool|string
	 */
	public static function get()
	{
		// Если есть сообщение
		if ($type = Session::instance()->get_once('type'))
		{
			// Текст сообщения
			$text = Session::instance()->get_once($type);

			$dir = Request::get('directory') ? '' : Request::get('directory').SL;

			// Возвращаем шаблон сообщения
			// Для frontend и backend свои шаблоны сообщений
			return View::factory('app/'.$dir.'v_message')
				->set('message', array('type' => $type, 'text' => $text))
				->render();
		}

		return FALSE;
	}
}
