<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php foreach ($users as $user): ?>
	<p>
		<span class="badge badge-success"><?php echo __('user.created') ?></span><br>
		<b><?php echo __('user.Email') ?>:</b> <?php echo $user['email'] ?><br>
		<b><?php echo __('user.password') ?>:</b> <?php echo $user['password'] ?><br>
		<b><?php echo __('user.roles') ?>:</b>
		<?php foreach ($user['roles'] as $role): ?>
			<?php echo $role ?>
		<?php endforeach ?>
	</p>
<?php endforeach ?>