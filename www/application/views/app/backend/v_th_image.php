<?php defined('SYSPATH') or die('No direct script access.') ?>

<div id="<?php echo $id.'-'.$fn ?>">
	<?php echo HTML::image($src, array('rel' => $id.'-'.$id.'-'.$fn, 'class' => 'img-polaroid')) ?>
	<div class="img-btn-actions">
		<button class="<?php echo $fn ?>-rotate-left btn btn-mini" type="button">
			<i class="icon-undo"></i>
		</button>
		<button class="<?php echo $fn ?>-rotate-right btn btn-mini" type="button">
			<i class="icon-repeat"></i>
		</button>
		<button class="<?php echo $fn ?>-delete-image btn btn-mini btn-danger" type="button">
			<i class="icon-remove"></i>
		</button>
	</div>
</div>
