<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php echo Form::open(Request::current(), array('class' => 'form-signin')) ?>

	<h3 class="form-signin-heading">
		<?php echo __('user.restore_password') ?>
	</h3>

	<div class="control-group">
		<?php echo Form::input('email', '', array('placeholder' => __('user.your_email'), 'class' => 'input-block-level')) ?>
	</div>

	<button class="btn btn-success" type="submit">
		<i class="icon-envelope-alt"></i>
		<?php echo __('user.restore') ?>
	</button>

	<hr>
	<p>
		<i class="icon-circle-arrow-left"></i>
		<?php echo HTML::anchor(Route::url('auth', array('action' => 'login')), __('user.back_to_auth')) ?>
	</p>

<?php echo Form::close() ?>