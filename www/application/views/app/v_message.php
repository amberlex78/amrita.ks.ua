<?php defined('SYSPATH') or die('No direct script access.');
if ($message['type'] == 'error') $message['type'] = 'danger';
?>

<div class="alert alert-<?php echo $message['type'] ?>">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo $message['text'] ?>
</div>