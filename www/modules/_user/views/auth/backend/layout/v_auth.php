<?php defined('SYSPATH') or die('No direct script access.') ?>

<!DOCTYPE html>
<html lang="<?php echo $language ?>">
<head>
    <title><?php HTML::chars($title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $styles ?>
	<?php echo $scripts ?>
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
<div class="container">
	<?php
	echo Form::open(Request::current(), array('class' => 'form-signin form-inline'));
	echo '<div class="notifications top-right">' . Message::get() . '</div>';
	echo $content;
	echo TB_Icon::home() . ' ' . HTML::anchor('', __('app.go_to_home_page'));
	echo Form::close();
	?>
</div><?php echo $profiler ?>
</body>
</html>
