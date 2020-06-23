<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class URL - extends Kohana class URL
 */
class URL extends Kohana_URL
{
	public static function query(array $params = NULL, $use_get = TRUE)
	{
		// Fix for ukraine.com.ua
		// This parameter for RewriteRule in .htaccess
		unset($_GET['kohana_uri']);

		return parent::query($params, $use_get);
	}
}