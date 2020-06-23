<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1><?php echo $title ?></h1>
<hr/>
<?php foreach ($obj as $o): ?>
	<span class="label label-tag">
		<?php echo HTML::anchor(Route::url('tags', array('slug' => $o->slug)),
			'<i class="icon-tag"></i> '.$o->name.' <sup>('.$o->count.')</sup>'
		) ?>
	</span>
<?php endforeach ?>