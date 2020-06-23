<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php echo Form::open(Request::current(), array('class' => 'form-signin form-inline')) ?>

	<h3 class="form-signin-heading">
		<?php echo __('user.authorization') ?>
	</h3>

	<div class="control-group">
		<?php echo Form::input('email', '', array('placeholder' => __('user.your_email'), 'class' => 'input-block-level')) ?>
	</div>

	<div class="control-group">
		<?php echo Form::password('password', '', array('placeholder' => __('user.your_password'), 'class' => 'input-block-level')) ?>
	</div>

	<button class="btn btn-success" type="submit">
		<i class="icon-unlock"></i>
		<?php echo __('user.log_in') ?>
	</button>

	<label class="checkbox">
		<?php echo Form::checkbox('remember', true) . __('user.remember_me') ?>
	</label>

	<br/><br/>
	<?php echo HTML::anchor(
		Route::url('auth', array('action' => 'signup')),
		'<i class="icon-user"></i> '.__('user.registration'),
		array('class' => 'btn')
	) ?>

	<hr>
	<p>
		<i class="icon-question-sign"></i>
		<?php echo HTML::anchor(Route::url('auth', array('action' => 'restore')), __('user.forgot_your_password')) ?>
	</p>

<?php echo Form::close() ?>