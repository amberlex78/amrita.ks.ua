<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Comments
 */
class Controller_Backend_Comments extends Controller_Backend
{
	public $module = 'comments';

	// Block title on page
	public $block_title = 'comments.comments';

	/**
	 * Comments
	 */
	public function action_list()
	{
		$this->title = __('comments.comments_manager');
		$this->content = View::factory('comments/backend/v_list');
	}

	/**
	 * vkontakte
	 */
	public function action_vk()
	{
		// Fields for save
		$for_extract = array(
			'vk_enabled',
			'vk_key',
			'vk_width',
			'vk_messages',
			'vk_auto_publish',
			'vk_norealtime',
		);

		$config = Config::get($this->module);

		$data = Arr::extract($config, $for_extract);

		if ($this->request->is_post())
		{
			$data = Validation::factory(array_map('trim', $_POST))
				->rules('vk_enabled',      array(array('not_empty'), array('digit')))
				->rules('vk_key',          array(array('digit')))
				->rules('vk_width',        array(array('not_empty'), array('digit')))
				->rules('vk_messages',     array(array('not_empty'), array('digit')))
				->rules('vk_auto_publish', array(array('not_empty'), array('digit')))
				->rules('vk_norealtime',   array(array('not_empty'), array('digit')))
			;

			if ($data->check())
			{
				foreach ($for_extract as $field)
					$config[$field] = $data[$field];

				$config->save();

				Message::set('success', __('app.changes_saved'));
				HTTP::redirect(ADMIN.'/'.$this->module.'/vk');
			}
			else
			{
				Message::set('error', __('app.error_saving'));
				$errors = $data->errors('validation');
				//d($errors);
			}
		}

		// Data for template
		$this->title   = __('comments.vk');
		$this->content = View::factory($this->module.'/backend/v_social_vk')
			->set('data', $data)
			->bind('errors', $errors);
	}

	/**
	 * facebook
	 */
	public function action_fb()
	{
		// Fields for save
		$for_extract = array(
			'fb_enabled',
			'fb_key',
			'fb_width',
			'fb_num_posts',
		);

		$config = Config::get($this->module);

		$data = Arr::extract($config, $for_extract);

		if ($this->request->is_post())
		{
			$data = Validation::factory(array_map('trim', $_POST))
				->rules('fb_enabled',   array(array('not_empty'), array('digit')))
				->rules('fb_key',       array(array('digit')))
				->rules('fb_width',     array(array('not_empty'), array('digit')))
				->rules('fb_num_posts', array(array('not_empty'), array('digit')))
			;

			if ($data->check())
			{
				foreach ($for_extract as $field)
					$config[$field] = $data[$field];

				$config->save();

				Message::set('success', __('app.changes_saved'));
				HTTP::redirect(ADMIN.'/'.$this->module.'/fb');
			}
			else
			{
				Message::set('error', __('app.error_saving'));
				$errors = $data->errors('validation');
				//d($errors);
			}
		}

		// Data for template
		$this->title   = __('comments.fb');
		$this->content = View::factory($this->module.'/backend/v_social_fb')
			->set('data', $data)
			->bind('errors', $errors);
	}

	/**
	 * Settings module
	 */
	public function action_settings()
	{
		// Fields for save
		$for_extract = array(
			'show_title',
			'name_title',
		);

		$config = Config::get($this->module);

		$data = Arr::extract($config, $for_extract);

		if ($this->request->is_post())
		{
			$data = Validation::factory(array_map('trim', $_POST))
			;

			if ($data->check())
			{
				foreach ($for_extract as $field)
					$config[$field] = $data[$field];

				$config->save();

				Message::set('success', __('app.changes_saved'));
				HTTP::redirect(ADMIN.'/'.$this->module.'/settings');
			}
			else
			{
				Message::set('error', __('app.error_saving'));
				$errors = $data->errors('validation');
				//d($errors);
			}
		}

		// Data for template
		$this->title   = __('app.settings_module');
		$this->content = View::factory($this->module.'/backend/v_settings')
			->set('data', $data)
			->bind('errors', $errors);
	}

	/**
	 * Edit module interface
	 * (internationalization module)
	 */
	public function action_i18n()
	{
		$this->module_i18n();
	}
}