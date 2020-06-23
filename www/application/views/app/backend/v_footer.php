<?php defined('SYSPATH') or die('No direct script access.') ?>

<footer>
	<div class="row-fluid muted">
		<hr>
		<div class="pull-right text-right">
			<small>
				<small>
					<?php echo __('app.developed_by').' '.Kohana::get_company_url() ?>
					<br>
					<?php echo __('app.powered_by').' '.Kohana::version(TRUE) ?>
				</small>
			</small>
		</div>
		<small>
			<small>
				<?php echo $config['year_creation_site'] == date('Y')
					? $config->copyright.', '.date('Y').'г.'
					: $config->copyright.', '.$config['year_creation_site'].'-'.date('Y').'г.';
				?>
				<br>
				<?php echo Kohana::get_usage_time_and_memory() ?>
			</small>
		</small>
		<br>
	</div>
</footer>
