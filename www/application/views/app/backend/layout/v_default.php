<?php defined('SYSPATH') or die('No direct script access.') ?>

<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
	<title><?php echo HTML::chars($title) ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $styles  ?>
	<?php echo $scripts ?>
</head>

<body>
	<?php echo $v_navigation_top ?>
	<div class="container-fluid">
		<div class="row-fluid">

			<?php if ($config['pos_backend_menu'] == 'left'): ?>
				<div class="span3">
					<?php echo $v_navigation ?>
				</div>
				<div class="span9">
					<div class="notifications top-right">
						<?php echo Message::get() ?>
					</div>
					<ul class="breadcrumb">
						<li><?php echo $v_legend ?></li>
						<?php if ($breadcrumbs): ?>
							<li></li>
							<?php echo $breadcrumbs ?>
						<?php endif ?>
					</ul>
					<?php echo $content ?>
				</div>
			<?php else: ?>
				<div class="span9">
					<div class="notifications top-right">
						<?php echo Message::get() ?>
					</div>
					<ul class="breadcrumb">
						<li><?php echo $v_legend ?></li>
						<?php if ($breadcrumbs): ?>
							<li></li>
							<?php echo $breadcrumbs ?>
						<?php endif ?>
					</ul>
					<?php echo $content ?>
				</div>
				<div class="span3">
					<?php echo $v_navigation ?>
				</div>
			<?php endif ?>

		</div>
		<?php echo $v_footer ?>
	</div>
	<?php echo $profiler ?>
</body>
</html>