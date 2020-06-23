<?php defined('SYSPATH') or die('No direct script access.') ?>

<span class="pull-right label"><?php echo HTML::chars($config->sitename) ?></span>

<h4 class="form-signin-heading">
	<?php echo __('user.restore_password') ?>
</h4>

<div class="control-group">
	<div class="input-prepend">
		<span class="add-on">@</span>
		<?php echo Form::input('email', '', array('placeholder' => __('user.your_email'), 'class' => 'input-xlarge', 'id' => 'prependedInput')) ?>
	</div>
</div>

<div class="control-group clearfix">
	<button class="btn btn-success" type="submit">
	    <i class="icon-envelope icon-white"></i>
		<?php echo __('user.restore') ?>
	</button>
</div>

<hr/>
<p>
    <i class="icon-circle-arrow-left"></i>
	<?php echo HTML::anchor(Route::url('backend_auth', array('action' => 'login')), __('user.back_to_auth')) ?>
</p>
