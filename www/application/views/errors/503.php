<?php defined('SYSPATH') or die('No direct script access.') ?>
<div class="alert alert-error">
	<h1><?php echo __('Error 503. Service unavailable!') ?></h1>
	<?php if ($message) echo '<p>' . $message . '</p>' ?>
</div>
<p><i class="icon-chevron-left"></i> <a href="javascript:history.go(-1);"><?php echo __('Go to back page') ?></a></p>
