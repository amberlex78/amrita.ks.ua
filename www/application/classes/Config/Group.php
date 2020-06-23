<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Config_Group
 */
class Config_Group extends Kohana_Config_Group
{
	/**
	 * Write to config file
	 */
	public function save()
	{
		if ($files = Kohana::find_file('config', $this->_group_name))
		{
			$content = Kohana::FILE_SECURITY.PHP_EOL.'return '.var_export($this->getArrayCopy(), true).';';

			// Modifiers for adjusting appearance
			$replace = array(
				"=> \n"    => '=>',
				'array ('  => 'array(',
				'  '       => "\t",
				' false,'  => ' FALSE,',
				' true,'   => ' TRUE,',
				' null,'   => ' NULL,'
			);

			$content = strtr($content, $replace);

			// TODO: заменить эту жесть на регулярку или ХЗ !!!??
			$replace = array(
				"=>	\tarray"         => '=> array',
				"=>	\t\tarray"       => '=> array',
				"=>	\t\t\tarray"     => '=> array',
				"=>	\t\t\t\tarray"   => '=> array',
				"=>	\t\t\t\t\tarray" => '=> array',
			);

			$content = strtr($content, $replace);

			file_put_contents(array_pop($files), $content);

			if (function_exists('opcache_reset')) {
				opcache_reset();
			}
		}
	}
}