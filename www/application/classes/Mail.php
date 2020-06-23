<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Mail
 */
class Mail
{
	/**
	 * Sends mail using the PHP mail() function.
	 * @param $to
	 * @param $from
	 * @param $subject
	 * @param $body
	 * @return bool
	 */
	public static function sendmail($to, $from, $subject, $body)
	{
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'From: ' . $from . "\r\n";

		return mail($to, $subject, $body, $headers);
	}

	public static function order($email, $order_id)
	{
		$email_admin = Config::get('app.email_admin');
		$sitename_subject = Config::get('app.sitename_subject');

		$obj_order = ORM::factory('Shop_Order', $order_id);
		if ($obj_order->loaded()) {
			$subject = $sitename_subject . ' - ' . 'Поступил заказ №' . $obj_order->number;

			$message = '<h1>Заказ №' . $obj_order->number . '</h1>';

			$message .= '<p>';
			$message .= '<br><b>Клиент:</b> '  . $obj_order->customer->fio;
			$message .= '<br><b>Телефон:</b> ' . Format::mobile($obj_order->customer->phone, false);
			$message .= '<br><b>Email:</b> '   . $obj_order->customer->email;
			$message .= '<br><b>Дата:</b> '    . Date::format($obj_order->created, Date::FULL);
			$message .= '</p>';
			$message .= '<p><a href="' . 'http://amrita.ks.ua/admin/orders/edit/' . $obj_order->id . '">Смотреть заказ</a></p>';

			return Mail::sendmail($email_admin, 'mail.amrita.ks.ua', $subject, $message);
		} else {
			return false;
		}
	}
}