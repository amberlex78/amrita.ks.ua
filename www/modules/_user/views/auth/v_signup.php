<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php echo Form::open(Request::current(), array('class' => 'form-signin form-inline')) ?>

	<h3 class="form-signin-heading">
		<?php echo __('user.registration') ?>
	</h3>

	<div class="control-group<?php echo Form::if_error('username', $errors) ?>">
		<?php echo Form::input('username', $obj->username, array('placeholder' => __('user.login'), 'class' => 'input-block-level')) ?>
		<?php echo Form::error('username', $errors); ?>
	</div>

	<div class="control-group<?php echo Form::if_error('email', $errors) ?>">
		<?php echo Form::input('email', $obj->email, array('placeholder' => __('user.your_email'), 'class' => 'input-block-level')) ?>
		<?php echo Form::error('email', $errors); ?>
	</div>

	<div class="control-group<?php echo Form::if_error('password', $errors) ?>">
		<?php echo Form::password('password', '', array('placeholder' => __('user.password'), 'class' => 'input-block-level')) ?>
		<?php echo Form::error('password', $errors); ?>
	</div>

	<div class="control-group<?php echo Form::if_error('password_confirm', $errors) ?>">
		<?php echo Form::password('password_confirm', '', array('placeholder' => __('user.password_confirm'), 'class' => 'input-block-level')) ?>
		<?php echo Form::error('password_confirm', $errors); ?>
	</div>

	<button class="btn btn-success" type="submit">
		<i class="icon-user"></i>
		<?php echo __('user.create_profile') ?>
	</button>

<?php echo Form::close() ?>