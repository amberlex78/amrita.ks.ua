<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Role model
 */
class Model_Role extends Model_Auth_Role
{
	// Table columns
	protected $_table_columns = array(
		'id'          => array('type' => 'int'),
		'name'        => array('type' => 'string'),
		'description' => array('type' => 'string'),
	);
}