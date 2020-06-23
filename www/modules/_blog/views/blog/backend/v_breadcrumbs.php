<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php if (Request::get('action') != 'edit'): ?>
<li>
	<?php echo HTML::anchor(ADMIN.'/posts/list', __('app.all')) ?>
	<span class="divider"><i class="icon-angle-right"></i></span>
</li>
<?php endif ?>

<?php foreach ($breadcrumbs as $i => $breadcrumb): ?>
	<?php if ($i < count($breadcrumbs) - 1): ?>
		<li>
			<?php echo HTML::anchor(ADMIN.'/'.Request::get('controller').'/'.Request::get('action').'/'.$breadcrumb->id, $breadcrumb->title) ?>
			<span class="divider"><i class="icon-angle-right"></i></span>
		</li>
	<?php else: ?>
		<li class="active"><?php echo $breadcrumb->title ?></li>
	<?php endif ?>
<?php endforeach ?>
