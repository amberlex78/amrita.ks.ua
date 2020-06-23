<?php defined('SYSPATH') or die('No direct script access.');

class File extends Kohana_File
{
	/**
	 * Create a directory specified in the path
	 *
	 * @param   string   $path  Path to directory
	 * @param   integer  $mode  Directory permissions
	 * @return  bool
	 */
	public static function mkdir($path, $mode = 0755)
	{
		return is_dir($path) ? TRUE : mkdir($path, $mode, TRUE);
	}

	/**
	 * Save varible in file
	 *
	 * @param   string $var Varible
	 * @param   string $file Path to file
	 * @param   bool $delete_empty Delete empty fields (for arrays)
	 * @return  bool
	 */
	public static function var_export($var, $file, $delete_empty = false)
	{
		// Create path directories if no exists
		if (!File::mkdir(dirname($file))) {
			return false;
		}

		// Write in file
		if (!$h = fopen($file, 'w+')) {
			return false;
		}

		// Block access to the file
		if (flock($h, LOCK_EX)) {

			// Delete empty elements from variable
			if ($delete_empty AND is_array($var)) {
				foreach ($var as $key => $value) {
					$var[$key] = array_filter($value);
				}
			}

			// File content
			$content = Kohana::FILE_SECURITY . PHP_EOL . PHP_EOL . 'return ' . var_export($var, true) . ';';

			// Modifiers for adjusting appearance
			$replace = [
				"=> \n"   => '=>',
				'array (' => 'array(',
				'  '      => "\t",
			];

			$content = strtr($content, $replace);

			// TODO: заменить эту жесть на регулярку или ХЗ !!!??
			$replace = [
				"=>	\tarray"         => '=> array',
				"=>	\t\tarray"       => '=> array',
				"=>	\t\t\tarray"     => '=> array',
				"=>	\t\t\t\tarray"   => '=> array',
				"=>	\t\t\t\t\tarray" => '=> array',
			];

			$content = strtr($content, $replace);

			// Write var content
			$result = fwrite($h, $content);
			flock($h, LOCK_UN);
		}

		fclose($h);

		if (function_exists('opcache_reset')) {
			opcache_reset();
		}

		return (bool)$result;
	}

	/**
	 * Delete folder with all sub folders and files
	 *
	 * @static
	 * @param $dir
	 */
	public static function remove_dir_rec($dir)
	{
		if ($objs = glob($dir . '/*'))
		{
			foreach($objs as $obj)
			{
				is_dir($obj) ? self::remove_dir_rec($obj) : @unlink($obj);
			}
		}
		@rmdir($dir);
	}

	/**
	 * Delete file(s)
	 *
	 * @param $full_path
	 *
	 * @return bool
	 */
	public static function remove_file($full_path)
	{
		// Если массив файлов
		if (is_array($full_path))
		{
			// Перебираем все
			foreach ($full_path as $path)
			{
				// Если файл существует и доступен для записи (удаления)
				if (is_file($path) AND is_writable($path))
				{
					// Удаляем файл
					unlink($path);
				}
			}
		}

		// Если один файл
		else
		{
			// Если файл существует и доступен для записи (удаления)
			if (is_file($full_path) AND is_writable($full_path))
			{
				// Удаляем файл
				unlink($full_path);

				return TRUE;
			}
		}
	}

	/**
	 * Create new filename
	 *
	 * @param $filename
	 * @param $generate_uniq_filename
	 *
	 * @return string
	 */
	public static function create_new_filename($filename, $generate_uniq_filename = FALSE)
	{
		$path_parts = pathinfo($filename);

		if ($path_parts['extension'])
			$path_parts['extension'] = '.' . $path_parts['extension'];

		if ($generate_uniq_filename)
		{
			return sha1(uniqid(NULL, TRUE)) . $path_parts['extension'];
		}

		$filename = Inflector::slug($path_parts['filename'], '_');
		$filename = $filename . '_' . uniqid();
		$filename = UTF8::substr($filename, 0, 250);

		return $filename . $path_parts['extension'];
	}

	/**
	 * Re array from $_FILES
	 *
	 * From:
	 * Array(
	 *     [name] => Array(
	 *         [0] => foo.txt
	 *         [1] => bar.txt
	 *     )
	 *     [type] => Array(
	 *         [0] => text/plain
	 *         [1] => text/plain
	 *     )
	 *     [tmp_name] => Array(
	 *         [0] => /tmp/phpYzdqkD
	 *         [1] => /tmp/phpeEwEWG
	 *     )
	 *     [error] => Array(
	 *         [0] => 0
	 *         [1] => 0
	 *     )
	 *     [size] => Array(
	 *         [0] => 123
	 *         [1] => 456
	 *     ))
	 *
	 * To:
	 * Array(
	 *     [0] => Array(
	 *         [name] => foo.txt
	 *         [type] => text/plain
	 *         [tmp_name] => /tmp/phpYzdqkD
	 *         [error] => 0
	 *         [size] => 123
	 *     )
	 *     [1] => Array(
	 *         [name] => bar.txt
	 *         [type] => text/plain
	 *         [tmp_name] => /tmp/phpeEwEWG
	 *         [error] => 0
	 *         [size] => 456
	 *     )
	 * )
	 *
	 * @param $file_post
	 *
	 * @return array
	 */
	public static function re_array_files(&$file_post)
	{
		$file_array = array();
		$file_count = count($file_post['name']);
		$file_keys  = array_keys($file_post);

		for ($i = 0; $i < $file_count; $i++)
		{
			foreach ($file_keys as $key)
			{
				$file_array[$i][$key] = $file_post[$key][$i];
			}
		}

		return $file_array;
	}
}