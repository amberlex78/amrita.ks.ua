<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1><?php echo __('user.hello_username', array(':username' => $obj->get_authorized_username())) ?></h1>
<hr/>

<dl class="dl-horizontal">

	<dt><?php echo __('user.login') ?>:</dt>
	<dd><?php echo HTML::chars($obj->username) ?></dd>

	<dt><?php echo __('user.Email') ?>:</dt>
	<dd><?php echo HTML::chars($obj->email) ?></dd>

	<dt><?php echo __('user.profile_created') ?>:</dt>
	<dd><?php echo HTML::chars(date('Y-m-d', $obj->created)) ?></dd>

	<dt><?php echo __('user.first_name') ?>:</dt>
	<dd><?php echo $obj->first_name ? HTML::chars($obj->first_name) : MDASH_NBSP ?></dd>

	<dt><?php echo __('user.last_name') ?>:</dt>
	<dd><?php echo $obj->last_name ? HTML::chars($obj->last_name) : MDASH_NBSP ?></dd>

	<dt><?php echo __('user.gender') ?>:</dt>
	<dd><?php echo $obj->get_gender() ?></dd>

	<dt></dt>
	<dd>
		<br/>
		<?php echo HTML::anchor(
			Route::url('user', array('action' => 'settings')),
			'<i class="icon-pencil"></i> '.__('user.change_profile'), array('class' => 'btn')
		) ?>
	</dd>

</dl>
