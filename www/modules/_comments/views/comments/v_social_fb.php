<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php if ( ! $config_comments['fb_width']): ?>
	<style>
		.fb-comments,
		.fb-comments span,
		.fb-comments iframe {
			width: 100% !important;
		}
	</style>
<?php endif ?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=<?php echo $config_comments['fb_key'] ?>";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
<div class="fb-comments"
	data-href="<?php echo $url ?>"
    data-num-posts="<?php echo $config_comments['fb_num_posts'] ?>"
	<?php if ($config_comments['fb_width']) echo ', data-width="'.$config_comments['fb_width'].'"' ?>"
>
</div>