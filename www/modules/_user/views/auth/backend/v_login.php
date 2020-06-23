<?php defined('SYSPATH') or die('No direct script access.') ?>

<span class="pull-right label"><?php echo HTML::chars($config->sitename) ?></span>

<h4 class="form-signin-heading">
	<?php echo __('user.authorization') ?>
</h4>

<div class="control-group">
	<div class="input-prepend">
		<span class="add-on">@</span>
		<?php echo Form::input('email', '', array('placeholder' => __('user.your_email'), 'class' => 'input-xlarge', 'id' => 'prependedInput')) ?>
	</div>
</div>

<div class="control-group">
	<div class="input-prepend">
		<span class="add-on">
			<i class="icon-key"></i>
		</span>
		<?php echo Form::password('password', '', array('placeholder' => __('user.your_password'), 'class' => 'input-xlarge', 'id' => 'prependedInput')) ?>
	</div>
</div>

<div class="control-group clearfix">
	<button class="btn btn-success" type="submit"><i class="icon-unlock icon-white"></i> <?php echo __('user.log_in') ?></button>
	<label class="checkbox">
		<?php echo Form::checkbox('remember', true) . __('user.remember_me') ?>
	</label>
</div>

<hr/>
<p>
	<i class="icon-question-sign"></i>
	<?php echo HTML::anchor(Route::url('backend_auth', array('action' => 'restore')), __('user.forgot_your_password')) ?>
</p>
