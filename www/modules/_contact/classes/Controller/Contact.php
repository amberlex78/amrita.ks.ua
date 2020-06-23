<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Contact
 */
class Controller_Contact extends Controller_Frontend
{
	public $module = 'contact';

	/**
	 * Contact page
	 */
	public function action_index()
	{
		if ($this->request->is_post())
		{
			$form = Validation_Contact::factory($this->request->post());

			if ($form->check())
			{
				$post = $this->_filters();

				Mail::sendmail(
					$this->config['email_admin'],
					$post['your_email'],
					$post['your_subject'],
					$post['your_message']
				);

				Message::set('success', __('contact.message_sent'));
				HTTP::redirect('contact');
			}
			else
			{
				Message::set('error', __('contact.error_message_sending'));
				$errors = $form->errors('validation');
			}
		}

		$obj = Config::get('contact');

		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
			->set('page_title', __('contact.contact'));

		$this->title       = $obj->meta_t;
		$this->keywords    = $obj->meta_k;
		$this->description = $obj->meta_d;

		$this->content = View::factory('contact/v_index')
			->set('text', $obj->text)
			->bind('form', $form)
			->bind('errors', $errors);
	}

	/**
	 * Filters for data
	 */
	private function _filters()
	{
		$post = array_map('trim', array_map('strip_tags', $_POST));

		$post['your_subject'] = $this->config->sitename_subject.' - '.__('contact.contact');
		$post['your_name']    = substr($post['your_name'], 0 , 32);
		$post['your_message'] = substr(nl2br($post['your_message']), 0 , 5000);

		$body  = '<p>'.__('contact.sent_from_name')   .': <b>'.$post['your_name'] .'</b></p>';
		$body .= '<p>'.__('contact.sent_from_email')  .': <b>'.$post['your_email'].'</b></p>';
		$body .= '<p>'.$post['your_message'].'</p>';

		$post['your_message'] = $body;

		return $post;
	}
}