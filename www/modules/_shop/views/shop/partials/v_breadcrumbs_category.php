<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php foreach ($breadcrumbs as $i => $breadcrumb): ?>
	<?php if ($i < count($breadcrumbs) - 1): ?>
		<li><small><?php echo HTML::anchor(Route::url('shop_category', array('slug' => $breadcrumb->slug)), $breadcrumb->title) ?></small></li>
	<?php else: ?>
		<li class="active"><small><?php echo $breadcrumb->title ?></small></li>
	<?php endif ?>
<?php endforeach ?>
