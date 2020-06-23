<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Model_User_Tmpimage
 */
class Model_User_Tmpimage extends ORM
{
	protected $_belongs_to = array
	(
		'user' => array(
			'model'       => 'User',
			'foreign_key' => 'user_id',
		),
	);

	protected $_table_columns = array(
		'id'         => array('type'=>'int'),
		'user_id'    => array('type'=>'int'),
		'fimage'     => array('type'=>'string'),
		'is_main'    => array('type'=>'int'),
		'field_name' => array('type'=>'string'),
	);

	/**
	 * Move uploaded image (and thumbnails) from tmp dir to destination dir
	 * and return image filename for save to DB
	 *
	 * @param $path_to_config
	 * @param $obj_id
	 *
	 * @return mixed|string
	 */
	public static function get_filename($path_to_config, $obj_id)
	{
		$config = Config::get($path_to_config);

		$user_id = Auth::instance()->get_user()->id;

		// Получаем изображения загруженные пользователем
		$o_image = ORM::factory('User_Tmpimage', array('user_id' => $user_id, 'field_name' => $config['field_name']));

		if ($o_image->loaded())
		{
			// Filename image for return
			$filename = $o_image->fimage;

			$destination_dir = $config['generate_subdir']
				? UPLOAD_DIR . $config['upload_dir'] . DS . $obj_id . DS
				: UPLOAD_DIR . $config['upload_dir'] . DS;

			File::mkdir($destination_dir);

			// Move from tmp dir to destination dir
			@rename(IMG_TMP_DIR . $o_image->fimage, $destination_dir . $o_image->fimage);

			// Move thumbnails images from tmp dir
			if (isset($config['thumbnails']) AND $config['thumbnails'])
			{
				foreach ($config['thumbnails'] as $thumbnail)
				{
					@rename(
						IMG_TMP_DIR . $thumbnail['prefix'] . $o_image->fimage,
						$destination_dir . $thumbnail['prefix'] . $o_image->fimage
					);
				}
			}

			// Delete tmp image record from DB
			$o_image->delete();

			return $filename;
		}

		return '';
	}

	/**
	 * Remove image files of object
	 *
	 * @param $path_to_config
	 * @param $obj_id
	 * @param $obj_fimage
	 */
	public static function remove_obj_images($path_to_config, $obj_id, $obj_fimage)
	{
		$config = Config::get($path_to_config);

		$upload_dir = UPLOAD_DIR . $config['upload_dir'] . DS;

		if ($config['generate_subdir'])
		{
			File::remove_dir_rec($upload_dir . $obj_id);
		}
		else
		{
			@unlink($upload_dir . $obj_fimage);

			if (isset($config['thumbnails']) AND $config['thumbnails'])
			{
				foreach ($config['thumbnails'] as $thumbnail)
				{
					@unlink($upload_dir . $thumbnail['prefix'] . $obj_fimage);
				}
			}
		}
	}
}