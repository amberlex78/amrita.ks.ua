<?php defined('SYSPATH') or die('No direct script access.');

echo TB_Form::control_group_with_error(
	Form::label('slug', __('app.seo_slug')),
	Form::input('slug', $slug, array('class' => 'span10')),
	TB_Form::block_error('slug', $errors),
	TB_Form::block_help(__('app.seo_slug_h').(isset($slug_h) ? '<br>'.$slug_h : ''))
);

echo TB_Form::control_group(
	Form::label('meta_t', __('app.seo_meta_t')),
	Form::input('meta_t', $meta_t, array('class' => 'span10')),
	isset($meta_t_h) ? TB_Form::block_help($meta_t_h) : ''
);

echo TB_Form::control_group(
	Form::label('meta_d', __('app.seo_meta_d')),
	Form::textarea('meta_d', $meta_d, array('class' => 'span10', 'rows' => 2)),
	TB_Form::block_help((isset($meta_d_h) ? $meta_d_h : '').'<br><div id="chars_meta_d"></div>')
);

echo TB_Form::control_group(
	Form::label('meta_k', __('app.seo_meta_k')),
	Form::textarea('meta_k', $meta_k, array('class' => 'span10', 'rows' => 2)),
	TB_Form::block_help((isset($meta_k_h) ? $meta_k_h : '').'<br><div id="chars_meta_k"></div>')
);
