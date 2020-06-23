<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php echo Form::open(Request::current(), array('class' => 'form-signin form-inline')) ?>

	<h3 class="form-signin-heading">
		<?php echo __('user.restore_password') ?>
	</h3>

	<div class="control-group<?php echo Form::if_error('password', $errors) ?>">
		<?php echo Form::password('password', '', array('placeholder' => __('user.new_password'), 'class' => 'input-block-level')) ?>
		<?php echo Form::error('password', $errors); ?>
	</div>

	<div class="control-group<?php echo Form::if_error('password_confirm', $errors) ?>">
		<?php echo Form::password('password_confirm', '', array('placeholder' => __('user.password_confirm'), 'class' => 'input-block-level')) ?>
		<?php echo Form::error('password_confirm', $errors); ?>
	</div>

	<button class="btn btn-success" type="submit">
		<i class="icon-save"></i>
		<?php echo __('app.act_save') ?>
	</button>

<?php echo Form::close() ?>