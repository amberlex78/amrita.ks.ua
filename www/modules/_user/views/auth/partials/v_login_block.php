<?php defined('SYSPATH') or die('No direct script access.') ?>

<div class="well">
	<?php echo Form::open(Route::url('auth', array('action' => 'login')), array('class' => 'form-horizontal')) ?>
		<div class="control-group">
			<?php echo Form::input('email', '', array('placeholder' => 'Email')) ?>
		</div>
		<div class="control-group">
			<?php echo Form::password('password', '', array('placeholder' => 'user.password')) ?>
		</div>
		<div class="form-inline control-group">
			<?php echo Form::button('login', '<i class="icon-unlock"></i> ' . __('user.log_in'), array('class' => 'btn btn-success')); ?>
			<label class="checkbox">
				<?php echo Form::checkbox('remember', true) ?>
				<?php echo __('user.remember_me') ?>
			</label>
		</div>
	<?php echo Form::close() ?>
</div>
