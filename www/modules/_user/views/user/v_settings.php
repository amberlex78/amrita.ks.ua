<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1><?php echo __('Your account settings') ?></h1>
<hr/>

<?php echo Form::open('', array('class' => 'form-horizontal')) ?>


	<!-- Basic information
	-->
	<div class="control-group">
		<label class="control-label"><?php echo __('user.login'); ?></label>
		<div class="controls">
			<?php echo Form::input('username', $user->username, array('disabled')) ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><?php echo __('Email'); ?></label>
		<div class="controls">
			<?php echo Form::input('email', $user->email, array('disabled')) ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="first_name"><?php echo __('user.first_name'); ?></label>
		<div class="controls">
			<?php echo Form::input('first_name', $user->first_name); ?>
			<?php echo Form::error('first_name', $errors); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="last_name"><?php echo __('user.last_name'); ?></label>
		<div class="controls">
			<?php echo Form::input('last_name', $user->last_name); ?>
			<?php echo Form::error('last_name', $errors); ?>
		</div>
	</div>


	<!-- Images
	-->
	<div class="form-actions"><strong><?php echo __('app.images') ?></strong></div>
	<div class="control-group">
		<label class="control-label"><?php echo __('Gravatar'); ?></label>
		<div class="controls">
			<?php echo Gravatar::factory(array('email' => $user->email))
				->size_set(100)
				->https_set_false()
				->rating_set_pg()
				->image(array('class' => 'img-polaroid'));
			?>
			<br><br>
			<p>User photos are currently powered by <a href="https://gravatar.com" target="_blank">Gravatar</a>.</p>
		</div>
	</div>


	<!-- Change password
	-->
	<div class="form-actions"><strong><?php echo __('user.change_password') ?></strong></div>
	<div class="control-group<?php echo Form::if_error('password_current', $errors) ?>">
		<label class="control-label" for="password_current"><?php echo __('user.current_password'); ?></label>
		<div class="controls">
			<?php echo Form::password('password_current', ''); ?>
			<?php echo Form::error('password_current', $errors); ?>
		</div>
	</div>

	<div class="control-group<?php echo Form::if_error('password', $errors) ?>">
		<label class="control-label" for="password"><?php echo __('user.new_password'); ?></label>
		<div class="controls">
			<?php echo Form::password('password', ''); ?>
			<small><span class="help-inline"><?php echo __('user.a_minimum_characters', array(':num' => 8)) ?></span></small>
			<?php echo Form::error('password', $errors); ?>
		</div>
	</div>

	<div class="control-group<?php echo Form::if_error('password_confirm', $errors) ?>">
		<label class="control-label" for="password_confirm"><?php echo __('user.password_confirm'); ?></label>
		<div class="controls">
			<?php echo Form::password('password_confirm', ''); ?>
			<?php echo Form::error('password_confirm', $errors); ?>
		</div>
	</div>


	<!-- Buttons
	-->
	<div class="form-actions">
		<button class="btn btn-primary" type="submit">
			<i class="icon-save icon-white"></i>
			<?php echo __('Save') ?>
		</button>
	</div>

<?php echo Form::close() ?>

