<?php defined('SYSPATH') or die('No direct script access.') ?>

<span class="label label-info">
	<?php echo HTML::chars(__($block_title)) ?>
</span>
<?php if ($title): ?>
	- <span class="label label-info"><?php echo HTML::chars($title) ?></span>
<?php endif ?>
