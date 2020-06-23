<?php defined('SYSPATH') or die('No direct script access.') ?>

<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
	<meta charset="utf-8">
	<title><?php echo HTML::chars($title) ?></title>
	<link href="<?php echo URL::base() ?>favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo HTML::chars($description) ?>" />
	<meta name="keywords" content="<?php echo HTML::chars($keywords) ?>" />
	<?php echo $scripts ?>
	<?php echo $styles  ?>
</head>
<body>

<div id="wrap">
	<div class="container">

		<?php echo $v_header ?>

		<div class="row">
			<div class="col-md-8">
				<?php if (Request::get('route') != 'home'): ?>
					<ol class="breadcrumb">
						<li><small><?php echo HTML::anchor(Route::url('home'), __('home.home')) ?></small></li>
						<?php echo $breadcrumbs ?>
					</ol>
				<?php endif ?>
				<?php echo Message::get() ?>
				<?php echo $content ?>
			</div>
			<div class="col-md-4">
				<?php foreach ($blocks_lft as $block_lft) echo $block_lft ?>
			</div>
		</div>
		<br>

	</div> <!-- /container -->
</div> <!-- /wrap -->

<?php echo $v_footer ?>

<?php echo $profiler ?>

</body>
</html>