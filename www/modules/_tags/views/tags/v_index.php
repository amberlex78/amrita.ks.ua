<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1><i class="icon-tag"></i> <?php echo HTML::chars($tag_name) ?></h1>

<?php if ($pagination->total_items > 0): ?>

	<?php foreach ($obj as $o): ?>
		<div class="item">
			<h3><i class="icon-file-text-alt"></i>
				<?php echo HTML::anchor(Route::url('blog_post', array('slug' => $o->slug)), $o->title)?>
			</h3>
			<?php
			echo HTML::if_image(
				UPLOAD_URL.$config_image_post['path_to_img'].$config_image_post['thumbnails'][0]['prefix'].$o->fimage,
				array('class' => 'img-polaroid img-item')
			)
			?>
			<?php echo $o->preview ?>
		</div>
	<?php endforeach ?>

	<?php echo $pagination ?>

<?php else: ?>

	<div class="alert alert-info">
		<?php echo __('Not found') ?>
	</div>

<?php endif ?>

<?php echo View::factory('app/partials/v_go_back') ?>
