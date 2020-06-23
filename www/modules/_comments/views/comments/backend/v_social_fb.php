<?php defined('SYSPATH') or die('No direct script access.');

echo TB_Form::horizontal_open('');

	echo TB_Form::control_group(
		Form::label('fb_enabled', __('comments.enabled')),
		Form::select('fb_enabled',
			array(
				'0' => __('comments.enabled_off'),
				'1' => __('comments.enabled_on')
			)
			, $data['fb_enabled']
		)
	);

	echo TB_Form::control_group_with_error(
		Form::label('fb_key', __('comments.fb_key')),
		Form::input('fb_key', $data['fb_key']),
		TB_Form::block_error('fb_key', $errors),
		TB_Form::inline_help(__('comments.fb_key_h'))
	);
	
	
	echo TB_Form::control_group_with_error(
		Form::label('fb_num_posts', __('comments.fb_num_posts')),
		Form::input('fb_num_posts', $data['fb_num_posts']),
		TB_Form::block_error('fb_num_posts', $errors),
		TB_Form::inline_help(__('comments.fb_num_posts_h'))
	);

	echo TB_Form::control_group_with_error(
		Form::label('fb_width', __('comments.fb_width')),
		Form::input('fb_width', $data['fb_width']),
		TB_Form::block_error('fb_width', $errors),
		TB_Form::inline_help(__('comments.fb_width_h'))
	);

	echo TB_Helpers::btn_save();

echo TB_Form::close();
