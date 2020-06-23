<?php defined('SYSPATH') or die('No direct script access.') ?>

<span class="pull-right label"><?php echo HTML::chars($config->sitename) ?></span>

<h4 class="form-signin-heading">
	<?php echo __('user.restore_password') ?>
</h4>

<div class="control-group<?php echo Form::if_error('password', $errors) ?>">
	<div class="input-prepend">
		<span class="add-on">
			<i class="icon-key"></i>
		</span>
		<?php echo Form::password('password', '', array('placeholder' => __('user.new_password'), 'class' => 'input-xlarge', 'id' => 'prependedInput')) ?>
	</div>
	<?php echo Form::error('password', $errors); ?>
</div>

<div class="control-group<?php echo Form::if_error('password_confirm', $errors) ?>">
	<div class="input-prepend">
		<span class="add-on">
			<i class="icon-key"></i>
		</span>
		<?php echo Form::password('password_confirm', '', array('placeholder' => __('user.password_confirm'), 'class' => 'input-xlarge', 'id' => 'prependedInput')) ?>
	</div>
	<?php echo Form::error('password_confirm', $errors); ?>
</div>

<div class="control-group clearfix">
	<button class="btn btn-success" type="submit">
		<i class="icon-save icon-white"></i>
		<?php echo __('app.act_save') ?>
	</button>
</div>
