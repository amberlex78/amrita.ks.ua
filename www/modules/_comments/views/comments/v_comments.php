<?php defined('SYSPATH') OR die('No direct script access.');

$config_comments = Config::get('comments')
?>

<?php if ($config_comments['vk_enabled'] OR $config_comments['fb_enabled']): ?>

	<?php if ($config_comments['show_title']): ?>
		<div class="page-header">
			<i class="icon-comments"></i>
			<strong><?php echo HTML::chars($config_comments['name_title']) ?></strong>
		</div>
	<?php else: ?>
		<hr/>
	<?php endif ?>


	<?php if ($config_comments['vk_enabled']): ?>
		<div class="page-header text-right">
			<i class="icon-vk"></i>
			<strong><?php echo __('comments.vk') ?></strong>
		</div>
		<?php
		echo View::factory('comments/v_social_vk')
			->bind('config_comments', $config_comments);
		?>
	<?php endif ?>

	<?php if ($config_comments['fb_enabled']): ?>
		<div class="page-header text-right">
			<i class="icon-facebook"></i>
			<strong><?php echo __('comments.fb') ?></strong>
		</div>
		<?php
		echo View::factory('comments/v_social_fb')
			->bind('config_comments', $config_comments)
			->bind('url', $url);
		?>
	<?php endif ?>


<?php endif ?>
