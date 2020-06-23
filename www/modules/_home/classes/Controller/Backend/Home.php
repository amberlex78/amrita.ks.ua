<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Home
 */
class Controller_Backend_Home extends Controller_Backend
{
	public $module = 'home';

	// Block title on page
	public $block_title = 'home.page';

	/**
	 * Settings
	 */
	public function action_edit()
	{
		$this->title = __('home.page_edit');

		// Fields for save
		$for_extract = array(
			'text',
			'meta_t',
			'meta_d',
			'meta_k',
		);

		$config = Config::get('home');
		$data   = Arr::extract($config, $for_extract);

		if ($this->request->is_post())
		{
			$data = Validation::factory(array_map('trim', $_POST))
				->rule('meta_t', 'not_empty')
				->rule('meta_d', 'not_empty')
				->rule('meta_k', 'not_empty')
			;

			if ($data->check())
			{
				foreach ($for_extract as $field)
					$config[$field] = $data[$field];

				$config->save();

				Message::set('success', __('app.changes_saved'));
				HTTP::redirect(ADMIN.'/home/edit');
			}
			else
			{
				Message::set('error', __('app.error_saving'));
				$errors = $data->errors('validation');
			}
		}

		// Data for template
		$this->content = View::factory('home/backend/v_index')
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
