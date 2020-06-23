<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Ajax controller
 */
class Controller_Ajax extends Controller_Security
{
	/**
	 * Array data request
	 */
	protected $json = array(
		'success' => FALSE, // Success or failed
		'message' => '',    // Message text
	);

	// Path to config image for upload
	protected $path_to_config;

	/**
	 * Before action
	 */
	public function before()
	{
		parent::before();

		// If not ajax request
		if ( ! $this->request->is_ajax())
		{
			// Error 404
			//throw new HTTP_Exception_404();
		}
	}

	/**
	 * After action
	 */
	public function after()
	{
		if (is_array($this->json))
		{
			$this->json = json_encode($this->json);
		}

		$this->response->body($this->json);
	}


	//==================================================================================================================
	// Change status

	/**
	 * Change status on 0 or 1 for field in model.
	 *
	 * Изменяем статус для записи в таблице.
	 *
	 * @param        $model - model name
	 * @param string $field - field name for change status (default: enabled)
	 */
	protected function _change_enabled($model, $field = 'enabled')
	{
		$obj = ORM::factory($model, (int) Arr::get($_POST, 'id'));

		if ($obj->loaded())
		{
			$obj->{$field} = ! $obj->{$field};
			$obj->save();

			$this->json['success'] = TRUE;
			$this->json['enabled'] = (bool) $obj->{$field};
			$this->json['message'] = __('app.status_changed');
		}
		else
			$this->json['message'] = __('app.status_failed');
	}

	/**
	 * Change status on 0 or 1 for field in model.
	 *
	 * Изменяем статус записи, если статус зависит от родительской таблицы.
	 *
	 * Например, статус статьи от статуса рубрики в которой находится статья.
	 * Если рубрика выключена - нельзя поменять статус статьи на включенный.
	 *
	 * @param        $model   - model name
	 * @param        $belongs - name belongs_to
	 * @param string $field   - field name for change status (default: enabled)
	 */
	protected function _change_enabled_belongsto($model, $belongs, $field = 'enabled')
	{
		$obj = ORM::factory($model, (int) Arr::get($_POST, 'id'));

		if ($obj->loaded())
		{
			if ($obj->{$belongs}->{$field})
			{
				$obj->{$field} = ! $obj->{$field};
				$obj->save();

				$this->json['success'] = TRUE;
				$this->json['enabled'] = (bool) $obj->{$field};
				$this->json['message'] = __('app.status_changed');
			}
			else
				$this->json['message'] = __('app.status_failed').' '.__('Rubric is disabled.');
		}
		else
			$this->json['message'] = __('app.status_failed');
	}

	/**
	 * Change status on 0 or 1 for field in model.
	 *
	 * Изменяем статус текущей записи и дочерних записей.
	 * Если указана зависимая модель - так же изменяем статусы в зависимой таблице.
	 *
	 * Например, статус рубрики с подрубриками и всеми статьями в этих рубриках.
	 * Если рубрика выключена - нельзя поменять статус подрубрик и зависимых статей на включенный
	 *
	 * @param $model       - model name
	 * @param $model2      - model name for dependent table
	 * @param $foreign_key - foreign key
	 * @param $field       - field name for change status (default: enabled)
	 */
	protected function _change_enabled_onetomany_mptt($model, $model2 = NULL, $foreign_key = NULL, $field = 'enabled')
	{
		$obj = ORM::factory($model, (int) Arr::get($_POST, 'id'));

		if ($obj->loaded() AND $obj->parent_id > 0)
		{
			$current_status = $obj->{$field};

			if ($ids = $obj->mptt_change_enabled())
			{
				if ($model2 AND $foreign_key)
				{
					$obj2 = ORM::factory($model2)->where($foreign_key, 'IN', $ids);
					$obj2->{$field} = ! $current_status;
					$obj2->update_all();
				}

				$this->json['success'] = TRUE;
				$this->json['message'] = __('app.status_changed');
			}
			else
				$this->json['message'] = __('app.status_failed').' '.__('Parent page is disabled.');
		}
		else
			$this->json['message'] = __('app.status_failed');
	}


	//==================================================================================================================
	//  Image (upload, rotate, delete)

	/**
	 * Upload image
	 *
	 * @param null $model_name
	 */
	protected function _upload_image($model_name = NULL)
	{
		$id = (int) $this->request->param('id', 0);

		try
		{
			$image = AjaxImage::factory($this->path_to_config)->validation();

			if ($image->check())
			{
				if ($model_name === NULL)
					$image->upload_for_add();
				else
					$image->upload_for_edit($model_name, $id);

				$this->json = View::factory('app/backend/v_th_image',
					array(
						'fn'  => $image->get_fn(),
						'id'  => $image->get_id(),
						'src' => $image->get_src()
					));
			}
			else
				$this->json = $image->errors();
		}
		catch (Exception $e)
		{
			$this->json = $e->getMessage();
		}
	}

	/**
	 * Rotate image
	 */
	protected function _rotate_image($model_name = NULL)
	{
		$id = (int) $this->request->post('id');
		$direction = $this->request->post('direction');

		try
		{
			$image = AjaxImage::factory($this->path_to_config);

			$result = ($model_name === NULL)
				? $image->rotate_for_add($id, $direction)
				: $image->rotate_for_edit($model_name, $id, $direction);

			if ($result)
			{
				$this->json['success'] = TRUE;
				$this->json['message'] = __('app.image_rotated');
				$this->json['src']     = $result;
			}
			else
				$this->json['message'] = __('app.image_error_rotating');
		}
		catch (Exception $e)
		{
			$this->json = $e->getMessage();
		}
	}

	/**
	 * Delete image
	 */
	protected function _delete_image($model_name = NULL)
	{
		$id = (int) $this->request->post('id');

		try
		{
			$image = AjaxImage::factory($this->path_to_config);

			$result = ($model_name === NULL)
				? $image->delete_for_add($id)
				: $image->delete_for_edit($model_name, $id);

			if ($result)
			{
				$this->json['success'] = TRUE;
				$this->json['message'] = __('app.image_deleted');
			}
			else
				$this->json['message'] = __('app.image_error_deleting');
		}
		catch (Exception $e)
		{
			$this->json = $e->getMessage();
		}
	}
}

