<?php defined('SYSPATH') or die('No direct script access.') ?>

<form class="form-horizontal" accept-charset="utf-8" method="post" action="">
	<table class="table-striped table-condensed table">
		<cols>
			<col>
			<col width="100%">
		</cols>
		<tr>
			<th><?php echo __('app.i18n_key') ?></th>
			<th><?php echo __('app.i18n_val') ?></th>
		</tr>
		<?php foreach ($data as $k => $v): ?>
			<tr>
				<td><?php echo ltrim(strstr($k, '.'), '.') ?></td>
				<td><?php echo Form::input('data['.$k.']', $v, array('class' => 'span12')) ?></td>
			</tr>
		<?php endforeach ?>
		<tr>
			<td><br><?php echo TB_Badge::info(__('app.i18n_total').': '.count($data)) ?></td>
			<td><br><?php echo TB_Button::submit_primary(__('app.act_save')) ?></td>
		</tr>
	</table>
</form>