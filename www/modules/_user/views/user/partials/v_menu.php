<?php defined('SYSPATH') or die('No direct script access.') ?>

<div class="well" style="max-width: 340px; padding: 8px 0;">
	<p class="text-center">
		<?php echo Gravatar::factory(array('email' => $user->email))
			->size_set(200)
			->https_set_false()
			->rating_set_pg()
			->image(array('class' => 'img-polaroid'));
		?>
	</p>
	<ul class="nav nav-list">
		<?php
			$active = Request::get('action') == 'profile' ? ' class="active"' : '';
			echo '<li' . $active . '>' . HTML::anchor(Route::url('user', array('action' => 'profile')), '<i class="icon-user"></i> ' . __('user.profile')) . '</li>';

			$active = Request::get('action') == 'settings' ? ' class="active"' : '';
			echo '<li' . $active . '>' . HTML::anchor(Route::url('user', array('action' => 'settings')), '<i class="icon-cog"></i> ' . __('Settings')) . '</li>';

			echo '<li>' . HTML::anchor(Route::url('auth', array('action' => 'logout')), '<i class="icon-lock"></i> ' . __('user.log_out')) . '</li>';

			if ($is_admin)
			{
				echo '<li class="divider"></li>';
				echo '<li>' . HTML::anchor(Route::url('backend'), '<i class="icon-cogs"></i> ' . __('app.admin_panel')) . '</li>';
			}
		?>
	</ul>
</div>
