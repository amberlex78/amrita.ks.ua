<?php defined('SYSPATH') or die('No direct script access.');

echo TB_Form::horizontal_open('');

	echo TB_Form::control_group_with_error(
		Form::label('name', __('tags.tag'), array('required')),
		Form::input('name', $obj->name),
		TB_Form::inline_error('name', $errors)
	);

	echo TB_Form::control_group_with_error(
		Form::label('slug', __('app.seo_slug')),
		Form::input('slug', Form::if_error('name', $errors) ? '' : $obj->slug),
		TB_Form::block_error('slug', $errors),
		TB_Form::inline_help(__('tags.slug_h'))
	);

	echo TB_Helpers::btns_save();

echo TB_Form::close();
