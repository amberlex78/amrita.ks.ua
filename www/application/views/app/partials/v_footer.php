<?php defined('SYSPATH') or die('No direct script access.') ?>

<footer id="footer" class="text-muted">
	<div class="container">
		<div class="pull-right text-right">
			<small>
				<small>
					<?php echo __('app.developed_by_author').' '.Kohana::get_company_url() ?>
					<br>
					<?php echo __('app.powered_by').' '.Kohana::version(TRUE) ?>
				</small>
			</small>
		</div>
		<small>
			<small>
				Девятаева Наталья - независимый дистрибьютор компании «Амрита»
				<br>
				<?php echo $config['year_creation_site'] == date('Y')
					? $config->copyright.', '.date('Y').'г.'
					: $config->copyright.', '.$config['year_creation_site'].'-'.date('Y').'г.';
				?>
			</small>
		</small>
	</div>
</footer>