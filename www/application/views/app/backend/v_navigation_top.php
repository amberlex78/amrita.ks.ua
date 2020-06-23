<?php defined('SYSPATH') or die('No direct script access.');

$class = ' class="active"';
?>

<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<?php echo HTML::anchor(ADMIN, $config->sitename, array('class' => 'brand')) ?>
			<div class="nav-collapse collapse">
				<ul class="nav navbar-nav">

					<?php $active = (Request::get('controller') == 'dashboard') ? $class : '' ?>

					<li<?= $active ?>>
						<?= Html::anchor(ADMIN, '<i class="icon-dashboard icon-white"></i> ' . __('dashboard.control_panel')) ?>
					</li>

				</ul>
				<ul class="nav pull-right">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-user"></i>
							<?php echo HTML::chars(__('app.administrator', array(':user' => $user->username))) ?>
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li <?php if (Request::get('controller') == 'dashboard') echo 'class="active"' ?>>
								<?php echo Html::anchor(ADMIN, '<i class="icon-dashboard icon-white"></i> '.__('dashboard.control_panel')) ?>
							</li>
							<?php

							$active = Request::get('controller') == 'settings' ? ' class="active"' : '';
							echo '<li'.$active.'>'.HTML::anchor(ADMIN.'/settings', '<i class="icon-cog"></i> '.__('app.global_settings')).'</li>';

							$active = Request::get('id') == $user->id ? ' class="active"' : '';
							echo '<li'.$active.'>'.HTML::anchor(ADMIN.'/user/edit/'.$user->id, '<i class="icon-user icon-white"></i> '.__('user.change_my_profile')).'</li>';

							?>
							<li class="divider"></li>
							<li><?php echo HTML::anchor('', '<i class="icon-external-link icon-white"></i> '.__('app.go_to_site'), array('target' => '_blank')) ?></li>
							<li><?php echo HTML::anchor(ADMIN.'/logout', '<i class="icon-off icon-white"></i> '.__('user.log_out')) ?></li>
						</ul>
					</li>
					<li class="divider"></li>
					<li><?php echo HTML::anchor('', '<i class="icon-external-link icon-white"></i> '.__('app.go_to_site'), array('target' => '_blank')) ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
