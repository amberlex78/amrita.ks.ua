<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1><?php echo __('user.change_profile') ?></h1>
<hr/>

<form class="form-horizontal form-horizontal" accept-charset="utf-8" method="post" action="">

	<div class="control-group">
		<label class="control-label" for="username"><?php echo Form::label('username', __('user.login')) ?></label>
		<div class="controls">
			<div class="input-append">
				<?php echo Form::input('username', $obj->username, array('disabled')) ?>
				<span class="add-on"><i class="icon-user"></i></span>
			</div>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="email"><?php echo Form::label('email', __('Email')) ?></label>
		<div class="controls">
			<div class="input-append">
				<?php echo Form::input('email', $obj->email, array('disabled')) ?>
				<span class="add-on">@</span>
			</div>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="first_name"><?php echo Form::label('first_name', __('user.first_name')) ?></label>
		<div class="controls">
			<div class="input-append">
				<?php echo Form::input('first_name', $obj->first_name) ?>
				<span class="add-on"><i class="icon-keyboard"></i></span>
			</div>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="last_name"><?php echo Form::label('last_name', __('user.last_name')) ?></label>
		<div class="controls">
			<div class="input-append">
				<?php echo Form::input('last_name', $obj->last_name) ?>
				<span class="add-on"><i class="icon-keyboard"></i></span>
			</div>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="gender"><?php echo Form::label('gender', __('user.gender')) ?></label>
		<div class="controls">
			<div class="input-append">
				<?php
				echo Form::select('gender',
					array (
						''  => '',
						'm' => __('user.gender_male'),
						'f' => __('user.gender_female'),
					),
					$obj->gender
				)
				?>
				<span class="add-on">
					<?php echo $obj->gender ? ($obj->gender == 'f' ? '<i class="icon-female"></i>' : '<i class="icon-male"></i>') : '<i class="icon-question"></i>' ?>
				</span>
			</div>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="dob"><?php echo Form::label('dob', __('user.date_of_birth')) ?></label>
		<div class="controls">
			<div class="input-append">
				<?php echo Form::input('dob', $obj->dob ?: date('Y-m-d')) ?>
				<span class="add-on"><i class="icon-calendar"></i></span>
			</div>
		</div>
	</div>


	<div class="form-actions"><?php echo __('user.change_password') ?></div>

	<div class="control-group<?php echo Form::if_error('password_current', $errors) ?>">
		<label class="control-label" for="password_current"><?php echo __('user.current_password') ?></label>
		<div class="controls">
			<div class="input-append">
				<?php echo Form::password('password_current') ?>
				<span class="add-on"><i class="icon-key"></i></span>
			</div>
			<?php echo Form::error_inline('password_current', $errors) ?>
		</div>
	</div>

	<div class="control-group<?php echo Form::if_error('password', $errors) ?>">
		<label class="control-label" for="password"><?php echo __('user.new_password') ?></label>
		<div class="controls">
			<div class="input-append">
				<?php echo Form::password('password') ?>
				<span class="add-on"><i class="icon-key"></i></span>
			</div>
			<small>
				<span class="help-inline"><?php echo __('user.a_minimum_characters', array(':num' => 8)) ?></span>
			</small>
			<?php echo Form::error_block('password', $errors) ?>
		</div>
	</div>

	<div class="control-group<?php echo Form::if_error('password_confirm', $errors) ?>">
		<label class="control-label" for="password_confirm"><?php echo __('user.password_confirm') ?></label>
		<div class="controls">
			<div class="input-append">
				<?php echo Form::password('password_confirm') ?>
				<span class="add-on"><i class="icon-key"></i></span>
			</div>
			<small>
				<span class="help-inline"><?php echo __('user.password_confirm_h') ?></span>
			</small>
			<?php echo Form::error_block('password_confirm', $errors) ?>
		</div>
	</div>

	<div class="form-actions">
		<button class="btn" type="submit">
			<i class="icon-save icon-white"></i>
			<?php echo __('app.act_save') ?>
		</button>
	</div>

</form>
