<?php defined('SYSPATH') or die('No direct script access.');

echo TB_Form::horizontal_open('');

	echo TB_Form::control_group(
		Form::label('vk_enabled', __('comments.enabled')),
		Form::select('vk_enabled',
			array(
				'0' => __('comments.enabled_off'),
				'1' => __('comments.enabled_on')
			)
			, $data['vk_enabled']
		)
	);

	echo TB_Form::control_group_with_error(
		Form::label('vk_key', __('comments.vk_key')),
		Form::input('vk_key', $data['vk_key']),
		TB_Form::block_error('vk_key', $errors),
		TB_Form::inline_help(__('comments.vk_key_h'))
	);

	echo TB_Form::control_group_with_error(
		Form::label('vk_width', __('comments.vk_width')),
		Form::input('vk_width', $data['vk_width']),
		TB_Form::block_error('vk_width', $errors),
		TB_Form::inline_help(__('comments.vk_width_h'))
	);

	echo TB_Form::control_group_with_error(
		Form::label('vk_messages', __('comments.vk_messages')),
		Form::input('vk_messages', $data['vk_messages']),
		TB_Form::block_error('vk_messages', $errors),
		TB_Form::inline_help(__('comments.vk_messages_h'))
	);

	echo TB_Form::control_group(
		Form::label('vk_auto_publish', __('comments.vk_auto_publish')),
		TB_Form::inline_labelled_radio('vk_auto_publish', __('app.yes'), 1, $data['vk_auto_publish'] == 1).
		TB_Form::inline_labelled_radio('vk_auto_publish', __('app.no'),  0, $data['vk_auto_publish'] == 0),
		TB_Form::block_help(__('comments.vk_auto_publish_h'))
	);

	echo TB_Form::control_group(
		Form::label('vk_norealtime', __('comments.vk_norealtime')),
		TB_Form::inline_labelled_radio('vk_norealtime', __('app.yes'), 0, $data['vk_norealtime'] == 0).
		TB_Form::inline_labelled_radio('vk_norealtime', __('app.no'),  1, $data['vk_norealtime'] == 1),
		TB_Form::block_help(__('comments.vk_norealtime_h'))
	);

	echo TB_Helpers::btn_save();

echo TB_Form::close();
