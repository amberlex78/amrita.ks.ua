<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Contact
 */
class Controller_Backend_Contact extends Controller_Backend
{
	public $module = 'contact';

	// Block title on page
	public $block_title = 'contact.contact';

	/**
	 * Settings
	 */
	public function action_edit()
	{
		$this->title = __('contact.page_edit');

		// Fields for save
		$for_extract = array(
			'text',
			'meta_t',
			'meta_d',
			'meta_k',
		);

		$config = Config::get('contact');
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
				HTTP::redirect(ADMIN.'/contact/edit');
			}
			else
			{
				Message::set('error', __('app.error_saving'));
				$errors = $data->errors('validation');
			}
		}

		// Data for template
		$this->content = View::factory('contact/backend/v_index')
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
