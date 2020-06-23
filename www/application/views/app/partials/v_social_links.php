<?php defined('SYSPATH') or die('No direct script access.') ?>

<div class="panel panel-default">
	<div class="panel-heading">
		<i class="fa fa-users fa-fw"></i>
		Соцсети
	</div>
	<div class="panel-body">
		<div style="padding: 10px; text-align: center;">
			<?= HTML::anchor(
				'https://www.youtube.com/channel/UCLXZmb9iN8ag1Em1HLeepxw',
				'<i class="fa fa-youtube fa-2x"></i>',
				['class' => 'btn btn-default', 'target' => '_blank', 'title' => 'YouTube']
			) ?>
			<?= HTML::anchor(
				'https://www.facebook.com/groups/209319549245462/',
				'<i class="fa fa-facebook-official fa-2x"></i>',
				['class' => 'btn btn-default', 'target' => '_blank', 'title' => 'Facebook']
			) ?>
		</div>
	</div>
</div>
