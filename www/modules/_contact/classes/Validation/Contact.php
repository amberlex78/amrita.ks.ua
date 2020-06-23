<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Model_Contact
 */
class Validation_Contact extends Validation_Template
{
	/**
	 * Sets the rules for Contact form
	 *
	 * @return array
	 */
	protected function _rules()
	{
		return array(
			'your_name' => array(
				array('not_empty'),
			),
			'your_email' => array(
				array('not_empty'),
				array('email'),
			),
			'your_message' => array(
				array('not_empty'),
			),

			// Antispam
			'your_subject_contact' => array(
				array('equals', array(':value', '')),
			),
			'ns' => array(
				array('equals', array(':value', 'ok')),
			),
		);
	}

	/**
	 * Sets the labels for Contact form
	 *
	 * @return array
	 */
	protected function _labels()
	{
		return array(
			'your_name'    => __('Your name'),
			'your_email'   => __('user.your_email'),
			'your_message' => __('Message'),
		);
	}
}