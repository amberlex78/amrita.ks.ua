<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1><?php echo $obj->title ?></h1>

<?php
if ($obj->show_image)
	echo HTML::if_image(
		UPLOAD_URL.$config_image['path_to_img'].$obj->fimage_static,
		array('class' => 'img-polaroid img-item')
	)
?>

<?php echo $obj->text ?>
