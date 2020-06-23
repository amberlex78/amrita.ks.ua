<?php defined('SYSPATH') or die('No direct script access.');

echo TB_Form::horizontal_open('');

	echo TB_Form::control_group(
		Form::label('text', __('contact.page_text')),
		Editor::ck('text', $data['text'])
	);

	echo TB_Form::actions(__('app.seo_features'));

	echo TB_Form::control_group_with_error(
		Form::label('meta_t', __('app.seo_meta_t'), array('required')),
		Form::input('meta_t', $data['meta_t'], array('class' => 'span10')),
		TB_Form::inline_error('meta_t', $errors),
		TB_Form::block_help(__('contact.meta_t_h'))
	);

	echo TB_Form::control_group_with_error(
		Form::label('meta_d', __('app.seo_meta_d'), array('required')),
		Form::textarea('meta_d', $data['meta_d'], array('class' => 'span10', 'rows' => 2)),
		TB_Form::inline_error('meta_d', $errors),
		TB_Form::block_help(__('contact.meta_d_h').'<br><div id="chars_meta_d"></div>')
	);

	echo TB_Form::control_group_with_error(
		Form::label('meta_k', __('app.seo_meta_k'), array('required')),
		Form::textarea('meta_k', $data['meta_k'], array('class' => 'span10', 'rows' => 2)),
		TB_Form::inline_error('meta_k', $errors),
		TB_Form::block_help(__('contact.meta_k_h').'<br><div id="chars_meta_k"></div>')
	);

	echo TB_Helpers::btn_save();

echo TB_Form::close();