<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Shop
 */
class Controller_Backend_Shop extends Controller_Backend
{
	public $module = 'shop';

	// Block title on page
	public $block_title = 'shop.shop';

	/**
	 * Settings module
	 */
	public function action_settings()
	{
		// Fields for save
		$for_extract = array(
			'per_page_home',
			'per_page_frontend',
			'per_page_backend',
		);

		$config = Config::get($this->module);

		$data = Arr::extract($config, $for_extract);

		if ($this->request->is_post())
		{
			$data = Validation::factory(array_map('trim', $_POST))
				->rules('per_page_home',     array(array('not_empty'), array('digit')))
				->rules('per_page_frontend', array(array('not_empty'), array('digit')))
				->rules('per_page_backend',  array(array('not_empty'), array('digit')))
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