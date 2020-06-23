<?php defined('SYSPATH') or die('No direct script access.') ?>

<div class="well" style="padding: 15px">
	<p><?php echo HTML::anchor(Route::url('tags'), __('tags.tags')) ?></p>
	<?php foreach ($obj as $o): ?>
		<?php echo HTML::anchor('/tags/'.$o->slug, $o->name) ?><sup class="muted">(<?php echo $o->count ?>)</sup>
	<?php endforeach ?>
</div>