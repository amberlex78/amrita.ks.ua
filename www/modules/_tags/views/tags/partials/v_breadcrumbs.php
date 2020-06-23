<?php defined('SYSPATH') or die('No direct script access.') ?>

<li>
	<small>
		<?php echo HTML::anchor(Route::url('tags'), __('tags.all_tags'))?>
		<span class="divider"><i class="icon-angle-right"></i></span>
	</small>
</li>
<li class="active">
	<small>
		<?php echo $page_title ?>
	</small>
</li>