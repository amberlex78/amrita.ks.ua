<?php defined('SYSPATH') or die('No direct script access.');

echo TB_Form::horizontal_open('');

	echo TB_Form::control_group_with_error(
		Form::label('sitename', __('app.site_name'), array('required')),
		Form::input('sitename',  $data['sitename'], array('class' => 'span10')),
		TB_Form::inline_error('sitename', $errors)
	);

	echo TB_Form::control_group(
		Form::label('siteslogan', __('app.slogan')),
		Form::input('siteslogan', $data['siteslogan'], array('class' => 'span10'))
	);

	echo TB_Form::control_group(
		Form::label('copyright', __('Copyright')),
		Form::input('copyright', $data['copyright'], array('class' => 'span10'))
	);

	echo TB_Form::control_group_with_error(
		Form::label('email_admin', __('app.site_email')),
		Form::input('email_admin', $data['email_admin'], array('class' => 'span10')),
		TB_Form::inline_error('email_admin', $errors)
	);

	echo TB_Form::control_group_with_error(
		Form::label('sitename_subject', __('app.sitename_subject')),
		Form::input('sitename_subject',  $data['sitename_subject'], array('class' => 'span10')),
		TB_Form::inline_error('sitename_subject', $errors)
	);

	echo TB_Form::control_group_with_error(
		Form::label('language', __('app.site_language')),
		Form::select('language', $languages_for_select, $data['language']),
		TB_Form::inline_error('language', $errors)
	);

	echo TB_Form::control_group(
		Form::label('type_backend_menu', __('app.type_admin_menu')),
		TB_Form::inline_labelled_radio('type_backend_menu', __('app.menu_simple'),    'simple',    $data['type_backend_menu'] == 'simple') .
		TB_Form::inline_labelled_radio('type_backend_menu', __('app.menu_accordion'), 'accordion', $data['type_backend_menu'] == 'accordion')
	);

	echo TB_Form::control_group(
		Form::label('pos_backend_menu', __('app.pos_admin_menu')),
		TB_Form::inline_labelled_radio('pos_backend_menu', __('app.menu_left'),  'left',  $data['pos_backend_menu'] == 'left') .
		TB_Form::inline_labelled_radio('pos_backend_menu', __('app.menu_right'), 'right', $data['pos_backend_menu'] == 'right')
	);

	echo TB_Form::actions(__('app.settings_per_page'));

	echo TB_Form::control_group_with_error(
		Form::label('per_page_frontend', __('app.settings_for_frontend'), array('required')),
		Form::input('per_page_frontend', $data['per_page_frontend']),
		TB_Form::inline_error('per_page_frontend', $errors)
	);

	echo TB_Form::control_group_with_error(
		Form::label('per_page_backend', __('app.settings_for_backend'), array('required')),
		Form::input('per_page_backend', $data['per_page_backend']),
		TB_Form::inline_error('per_page_backend', $errors)
	);

	echo TB_Helpers::btn_save();

echo TB_Form::close();