<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Model_User_Recovery_Password
 */
class Model_User_Recovery_Password extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'user_recovery_password';

	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns = array(
		'id'          => array('type' => 'int'),
		'user_id'     => array('type' => 'int'),
		'token'       => array('type' => 'string'),
		'expires'     => array('type' => 'int'),
	);


	protected $_belongs_to = array(
		'user' => array(
			'model'       => 'User',
			'foreign_key' => 'user_id',
		),
	);

	/**
	 * Save token to DB and return this token
	 *
	 * @param $user_id
	 *
	 * @return string
	 */
	public static function get_token($user_id)
	{
		$o_recovery_password = ORM::factory('User_Recovery_Password', array('user_id' => $user_id));

		$o_recovery_password->user_id = $user_id;
		$o_recovery_password->token   = sha1(uniqid(NULL, TRUE));
		$o_recovery_password->expires = time() + Config::get('auth.token_password_expires');
		$o_recovery_password->save();

		return $o_recovery_password->token;
	}
}