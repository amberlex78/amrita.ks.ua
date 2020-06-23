<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Settings
 */
class Controller_Backend_Settings extends Controller_Backend
{
	public $module = 'settings';

	// Block title on page
	public $block_title = 'Settings';

	/**
	 * List
	 */
	public function action_index()
	{
		$this->title = __('app.global_settings');

		// Fields for save
		$for_extract = array(
			'sitename',
			'sitename_subject',
			'siteslogan',
			'copyright',
			'language',
			'email_admin',
			'per_page_frontend',
			'per_page_backend',
			'type_backend_menu',
			'pos_backend_menu',
		);

		$config = Config::get('app');

		$data = Arr::extract($config, $for_extract);

		if ($this->request->is_post())
		{
			$data = Validation::factory(array_map('trim', $_POST))
				->rule('sitename', 'not_empty')
				->rule('email_admin', 'email')
				->rule('language', 'not_empty')
				->rule('language', 'in_array', array(':value', array_keys($config->languages_for_select)))
				->rules('per_page_frontend', array(array('not_empty'), array('digit')))
				->rules('per_page_backend',  array(array('not_empty'), array('digit')))
				->rule('per_page_frontend', 'min', array(':value', 1))
				->rule('per_page_backend',  'min', array(':value', 1))
			;

			$data->labels(array(
				'sitename'          => __('app.site_name'),
				'sitename'          => __('app.sitename_subject'),
				'email_admin'       => __('app.site_email'),
				'language'          => __('app.site_language'),
				'per_page_frontend' => __('app.settings_for_frontend'),
				'per_page_backend'  => __('app.settings_for_backend'),
			));

			if ($data->check())
			{
				foreach ($for_extract as $field)
					$config[$field] = $data[$field];

				$config->save();

				Message::set('success', __('app.settings_saved'));
				HTTP::redirect(ADMIN.'/settings');
			}
			else
			{
				Message::set('error', __('app.error_saving'));
				$errors = $data->errors('validation');
			}
		}

		// Data for template
		$this->content = View::factory('app/backend/v_settings')
			->set('data', $data)
			->set('languages_for_select', $config->languages_for_select)
			->bind('errors', $errors);
	}
}
