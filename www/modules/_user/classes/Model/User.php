<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User model
 */
class Model_User extends Model_Auth_User
{
	/**
	 * Auto-update columns for creation
	 * @var string
	 */
	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE,
	);

	/**
	 * Auto-update columns for updates
	 * @var string
	 */
	protected $_updated_column = array(
		'column' => 'updated',
		'format' => TRUE,
	);

	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns = array(
		'id'            => array('type'=>'int'),
		'email'         => array('type'=>'string'),
		'username'      => array('type'=>'string'),
		'password'      => array('type'=>'string'),
		'logins'        => array('type'=>'int'),
		'last_login'    => array('type'=>'int'),
		'created'       => array('type'=>'int'),
		'updated'       => array('type'=>'int'),
		'first_name'    => array('type'=>'string'),
		'last_name'     => array('type'=>'string'),
		'fimage_avatar' => array('type'=>'string'),
		'fimage_logo'   => array('type'=>'string'),
		'dob'           => array('type'=>'string'),
		'gender'        => array('type'=>'string'),
	);

	/**
	 * A user has many tokens, roles and ulogins
	 *
	 * @var array Relationhips
	 */
	protected $_has_many = array(
		'user_tokens' => array(
			'model' => 'User_Token'
		),
		'roles' => array(
			'model'   => 'Role',
			'through' => 'roles_users'
		),
		'ulogins' => array()
	);

	/**
	 * Validation rules
	 *
	 * @return array
	 */
	public function rules()
	{
        $config = Config::get('auth.name');
		return array(
			'username' => array(
				array('not_empty'),
                array('min_length', array(':value', max((int)$config['length_min'], 1))),
                array('max_length', array(':value', min((int)$config['length_max'], 32))),
                array('regex', array(':value', '/^[' . $config['chars'] . ']+$/ui') ),
				array(array($this, 'unique'), array('username', ':value')),
			),
			'password' => array(
				array('not_empty'),
			),
			'email'    => array(
				array('not_empty'),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),
		);
	}

	/**
	 * Titles fields for used in messages errors
	 *
	 * @return array Labels
	 */
	public function labels()
	{
		return array(
			'first_name' => __('user.first_name'),
			'last_name'  => __('user.last_name'),
		);
	}

	/**
	 * Filters before validation
	 *
	 * @return array
	 */
	public function filters()
	{
		return array(
			TRUE => array(
				array('trim'),
				array('strip_tags'),
			),
			'password' => array(
				array(array(Auth::instance(), 'hash'))
			),
		);
	}

	/**
	 * Create a new user (overridden method)
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('User')->create_user($_POST, array(
	 *    'username',
	 *    'password',
	 *    'email',
	 * );
	 * ~~~
	 *
	 * @param array $values
	 * @param array $expected
	 *
	 * @return ORM_Validation_Exception
	 */
	public function create_user($values, $expected)
	{
		// Validation for passwords
		$extra_validation = Model_User::get_password_validation($values)
			->rule('password', 'not_empty')
			->rule('password_confirm', 'not_empty')
			->labels(array(
				'password'         => __('user.password'),
				'password_confirm' => __('user.password_confirm'),
			));

		return $this
			->values($values, $expected)
			->create($extra_validation);
	}

	/**
	 * Update an existing user (overridden method)
	 *
	 * [!!] We make the assumption that if a user does not supply a password, that they do not wish to update their password.
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('User')
	 *    ->where('username', '=', 'kiall')
	 *    ->find()
	 *    ->update_user($_POST, array(
	 *        'username',
	 *        'password',
	 *        'email',
	 *    );
	 * ~~~
	 *
	 * @param array $values
	 * @param null  $expected
	 *
	 * @return ORM_Validation_Exception
	 */
	public function update_user($values, $expected = NULL)
	{
		if (empty($values['password_current']) AND empty($values['password']))
		{
			unset($values['password_current'], $values['password'], $values['password_confirm']);

			$extra_validation = Model_User::get_password_validation($values);
		}
		else
		{
			if (empty($values['password']))
			{
				// При незаполненном password пароль не меняется
				unset($values['password'], $values['password_confirm']);
			}

			$extra_validation = Model_User::get_password_validation($values)
				->rule('password_current', 'not_empty')
				->rule('password_current', array(Auth::instance(), 'check_password'));
		}

		$extra_validation->labels(array(
			'password_current' => __('user.current_password'),
			'password'         => __('user.new_password'),
			'password_confirm' => __('user.password_confirm'),
		));

		return $this
			->values($values, $expected)
			->update($extra_validation);
	}

	/**
	 * Save ajax uploaded image
	 */
	public function save_image()
	{
		$this->fimage_avatar = Model_User_Tmpimage::get_filename('user.avatar', $this->id);
		$this->fimage_logo   = Model_User_Tmpimage::get_filename('user.logo', $this->id);

		parent::save();
	}

	/**
	 * Delete user
	 *
	 * @return ORM
	 */
	public function delete()
	{
		// Delete image files
		Model_User_Tmpimage::remove_obj_images('user.avatar', $this->id, $this->fimage_avatar);
		Model_User_Tmpimage::remove_obj_images('user.logo', $this->id, $this->fimage_logo);

		return parent::delete();
	}

	/**
	 * Get humanize username for show in public profile
	 *
	 * @return mixed|string
	 */
	public function get_public_username()
	{
		$username = HTML::chars($this->first_name.' '.$this->last_name);

		return ($this->first_name AND $this->last_name)
			? $username.' <small>('.$this->username.')</small>'
			: $this->username;
	}

	/**
	 * Get humanize username for show in authorized profile
	 *
	 * @return mixed|string
	 */
	public function get_authorized_username()
	{
		$username = HTML::chars($this->first_name.' '.$this->last_name);

		return ($this->first_name AND $this->last_name)
			? $username
			: $this->username;
	}

	/**
	 * Get humanize gendeer for show in profile
	 *
	 * @return string
	 */
	public function get_gender()
	{
		if ($this->gender == 'm')
			return HTML::chars(__('user.gender_male'));

		elseif ($this->gender == 'f')
			return HTML::chars(__('user.gender_female'));

		else
			return MDASH_NBSP;
	}
}