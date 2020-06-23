<?php defined('SYSPATH') or die('No direct script access.');

echo TB_Form::horizontal_open('');

	// Used by js
	echo Form::hidden('current_action', Request::get('action'));

	echo TB_Form::control_group_with_error(
		Form::label('username', __('user.login'), array('required')),
		TB_Form::append(Form::input('username', $obj->username), TB_Icon::user()),
		TB_Form::inline_error('username', $errors)
	);

	echo TB_Form::control_group_with_error(
		Form::label('email', __('Email'), array('required')),
		TB_Form::append(Form::input('email', $obj->email), '@'),
		TB_Form::inline_error('email', $errors)
	);

	echo TB_Form::control_group(
		Form::label('first_name', __('user.first_name')),
			TB_Form::append(Form::input('first_name', $obj->first_name), TB_Icon::keyboard())
	);

	echo TB_Form::control_group(
		Form::label('last_name', __('user.last_name')),
		TB_Form::append(Form::input('last_name', $obj->last_name), TB_Icon::keyboard())
	);

	echo TB_Form::control_group(
		Form::label('gender', __('user.gender')),
		TB_Form::append(
			Form::select('gender', array (
				'' => '',
				'm' => __('male'),
				'f' => __('female'),
			), $obj->gender),
			$obj->gender ? ($obj->gender == 'f' ? TB_Icon::female() : TB_Icon::male()) : TB_Icon::question()
		)
	);

	echo TB_Form::control_group_with_error(
		Form::label('dob', __('user.date_of_birth')),
		TB_Form::append(Form::input('dob', $obj->dob ?: date('Y-m-d')), TB_Icon::calendar()),
		TB_Form::inline_error('dob', $errors)
	);

	echo TB_Form::actions(__('app.images'));
	?>

	<div class="control-group">
		<label class="control-label" for="fimage_avatar"><?php echo __('user.avatar') ?></label>
		<div class="controls clearfix">
			<div class="responses">
				<div id="response"><?php echo $v_th_avatar?></div>
			</div>
			<span class="btn fileinput-button">
				<i class="icon-folder-open"></i>
				<span><?php echo __('app.image_select').'...' ?></span>
				<?php echo Form::file('fimage_avatar', array('rel' => $obj->id, 'id' => 'fimage_avatar')) ?>
			</span>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="fimage_logo"><?php echo __('user.logo') ?></label>
		<div class="controls clearfix">
			<div class="responses">
				<div id="response_logo"><?php echo $v_th_logo?></div>
			</div>
			<span class="btn fileinput-button">
				<i class="icon-folder-open"></i>
				<span><?php echo __('app.image_select').'...' ?></span>
				<?php echo Form::file('fimage_logo', array('rel' => $obj->id, 'id' => 'fimage_logo')) ?>
			</span>
		</div>
	</div>

	<?php
	echo TB_Form::actions(__('user.create_password'));

	echo TB_Form::control_group_with_error(
		Form::label('password', __('user.password'), array('required')),
		TB_Form::append(Form::password('password'), TB_Icon::key()),
		TB_Form::block_error('password', $errors),
		TB_Form::inline_help(__('user.a_minimum_characters', array(':num' => 8)))
	);

	echo TB_Form::control_group_with_error(
		Form::label('password_confirm', __('user.password_confirm'), array('required')),
		TB_Form::append(Form::password('password_confirm'), TB_Icon::key()),
		TB_Form::block_error('password_confirm', $errors),
		TB_Form::inline_help(__('user.password_confirm_h', array(':num' => 8)))
	);

	echo TB_Helpers::btns_save();

echo TB_Form::close();
