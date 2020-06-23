<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1><i class="icon-file-text"></i> <?php echo $post->title ?></h1>

<?php
if ($post->show_image)
	echo HTML::if_image(
		UPLOAD_URL.$config_image['path_to_img'].$post->fimage_post,
		array('class' => 'img-thumbnail img-item')
	)
?>

<?php if ($post->show_preview): ?>
	<?php echo $post->preview ?>
<?php endif ?>

<div>
<?php echo $post->text ?>
</div>

<div class="both"></div><hr>

<?php echo View::factory('app/partials/v_go_back') ?>

<?php if (isset($modules['_comments']))
	echo View::factory('comments/v_comments')
		->set('url', Route::url('blog_post', array('slug' => $post->slug)));
?>
<hr>
<div id="mc-container"></div>
<script type="text/javascript">
	cackle_widget = window.cackle_widget || [];
	cackle_widget.push({widget: 'Comment', id: 27285});
	(function() {
		var mc = document.createElement('script');
		mc.type = 'text/javascript';
		mc.async = true;
		mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
	})();
</script>
<a id="mc-link" href="http://cackle.me">Social comments <b style="color:#4FA3DA">Cackl</b><b style="color:#F65077">e</b></a>