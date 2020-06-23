<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @var $cart Cart
 */
?>

<header>

	<div class="row">
		<div class="col-md-4">
			<?= HTML::anchor('', HTML::image('media/img/amrita_logo.gif', ['style' => 'float:left', 'width' => 186])) ?>
		</div>
		<div class="col-md-4">
			<div id="content-cart-top" style="margin-top: 15px">
				<?= View::factory('shop/v_cart_top') ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="top-info">
				<?= HTML::image('uploads/user/avatar/th_29d51df1d188ef664c85b4adb389e41ca52b2a95.jpg', ['class' => 'img-thumbnail']) ?>
				<address>
					<div class="text-success">
					<b>Девятаева Наталья&nbsp;Ивановна</b><br>
					Украина, Голая&nbsp;Пристань<br>
					<abbr title="Телефон">Тел:</abbr>&nbsp;(050)&nbsp;728-16-09
					</div>
				</address>
				<p>Независимый&nbsp;дистрибьютор компании&nbsp;«Амрита»</p>
			</div>
		</div>
	</div>

	<nav class="navbar navbar-default" role="navigation">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?= HTML::anchor('', '<i class="fa fa-home fa-fw"></i> ' . $config->sitename, ['class' => 'navbar-brand']) ?>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">

				<?php
				$class = (Request::get('route') == 'blog_rubric' AND Request::get('slug') == 'news')
					? ' class="active"'
					: '';
				echo '<li' . $class . '>' . HTML::anchor(Route::url('blog_rubric', ['slug' => 'news']),
						'<i class="fa fa-file-text-o fa-fw"></i> ' . __('Новости')) . '</li>';

				$class = (Request::get('route') == 'blog_rubric' AND Request::get('slug') == 'articles')
					? ' class="active"'
					: '';
				echo '<li' . $class . '>' . HTML::anchor(Route::url('blog_rubric', ['slug' => 'articles']),
						'<i class="fa fa-folder-open-o fa-fw"></i> ' . __('Статьи')) . '</li>';

				$class = (Request::get('route') == 'shop_category' AND Request::get('slug') == 'katalog')
					? ' class="active"'
					: '';
				echo '<li' . $class . '>' . HTML::anchor(Route::url('shop_category', ['slug' => 'katalog']),
						'<i class="fa fa-book fa-fw"></i> ' . __('Каталог')) . '</li>';

				$class = (Request::get('route') == 'shop_category' AND Request::get('slug') == 'price')
					? ' class="active"'
					: '';
				echo '<li' . $class . '>' . HTML::anchor(Route::url('shop_category', ['slug' => 'price']),
						'<i class="fa fa-list-ul fa-fw"></i> ' . __('Прайс')) . '</li>';

				if (isset($modules['_static'])) {
					// Переменная $menu_static определяется в Frontend.php
					foreach ($menu_static as $menu) {
						$class = Request::get('slug') == $menu->slug ? ' class="active"' : '';
						echo '<li' . $class . '>' . HTML::anchor(Route::url('static', ['slug' => $menu->slug]),
								$menu->title_menu) . '</li>';
					}
				}
				if (isset($modules['_contact'])) {
					$class = Request::get('route') == 'contact' ? ' class="active"' : '';
					echo '<li' . $class . '>' . HTML::anchor(Route::url('contact'),
							'<i class="fa fa-envelope-o"></i> ' . __('contact.contact')) . '</li>';
				}
				?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php

				if (isset($modules['_feed'])) {
					$class = Request::get('route') == 'feed' ? ' class="active"' : '';
					echo '<li' . $class . '>';
					echo HTML::anchor(
						Route::url('feed'),
						'<i class="fa fa-rss"></i> ' . __('app.module_title_feed'), ['target' => '_blank']
					);
					echo '<li>';
				}

				?>
			</ul>
		</div>
		<!-- /.navbar-collapse -->

	</nav>

</header>