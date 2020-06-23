<?php defined('SYSPATH') or die('No direct script access.');

echo Form::open('', array('class'=>'form-horizontal'));

	echo TB_Form::actions(__('app.settings_per_page'));

	echo TB_Form::control_group_with_error(
		Form::label('per_page_home', __('app.settings_for_home_page')),
		Form::input('per_page_home',  $data['per_page_home']),
		TB_Form::block_error('per_page_home', $errors),
		TB_Form::inline_help(__('app.settings_per_page_h'))
	);

	echo TB_Form::control_group_with_error(
		Form::label('per_page_frontend', __('app.settings_for_frontend')),
		Form::input('per_page_frontend',  $data['per_page_frontend']),
		TB_Form::block_error('per_page_frontend', $errors),
		TB_Form::inline_help(__('app.settings_per_page_h'))
	);

	echo TB_Form::control_group_with_error(
		Form::label('per_page_backend', __('app.settings_for_backend')),
		Form::input('per_page_backend',  $data['per_page_backend']),
		TB_Form::block_error('per_page_backend', $errors),
		TB_Form::inline_help(__('app.settings_per_page_h'))
	);

	echo TB_Helpers::btn_save();

echo Form::close();

