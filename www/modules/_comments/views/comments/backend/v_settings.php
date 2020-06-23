<?php defined('SYSPATH') or die('No direct script access.');

echo TB_Form::horizontal_open('');

	echo TB_Form::control_group(
		Form::label('show_description', __('comments.show_title')),
		TB_Form::inline_labelled_radio('show_title', __('app.yes'), 1, $data['show_title'] == 1).
		TB_Form::inline_labelled_radio('show_title', __('app.no'),  0, $data['show_title'] == 0)
	);

	echo TB_Form::control_group(
		Form::label('name_title', __('comments.name_title')),
		Form::input('name_title', $data['name_title'])

	);

	echo TB_Helpers::btn_save();

echo TB_Form::close();
