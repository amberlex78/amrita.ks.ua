<?php defined('SYSPATH') or die('No direct script access.') ?>

<script type="text/javascript" src="//vk.com/js/api/openapi.js?101"></script>

<script type="text/javascript">
	VK.init({apiId: <?php echo $config_comments['vk_key'] ?>, onlyWidgets: true});
</script>

<div id="vk_comments"></div>
<script type="text/javascript">
	VK.Widgets.Comments("vk_comments", {
		limit: <?php echo $config_comments['vk_messages'] ?>
		, attach: false
		, autoPublish: <?php echo $config_comments['vk_auto_publish'] ?>
		, norealtime: <?php echo $config_comments['vk_norealtime'] ?>
		<?php if ($config_comments['vk_width']) echo ', width: '.$config_comments['vk_width'] ?>
	});
</script>