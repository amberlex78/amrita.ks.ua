<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1>Компания «Амрита» в Голой Пристани</h1>
<p>«Амрита» – компания №1 среди украинских компаний прямых продаж.</p>
<p>	Группа Компаний «Амрита» <b>создана в 2001 году</b>. <b>За 14 лет ведения бизнеса</b>,
	основанного на принципах последовательности, добросовестности и качества в работе и отношениях,
	наша команда завоевала доверие у более миллиона потребителей,
	140 тысяч дистрибьюторов по всей Украине и за ее пределами.
</p>


<h2>Новые публикации</h2>
<?php foreach ($last_posts as $item): ?>
	<small><i class="fa fa-calendar"></i> <?= $item->pubdate ?></small>
	<?= HTML::anchor(Route::url('blog_post', array('slug' => $item->slug)), $item->title) ?>
	<br>
<?php endforeach ?>


<h2>Добавлены новые товары</h2>
<?php foreach ($last_products as $product): ?>
	<div class="row row-fluid">
		<div class="col-lg-3">
			<?= HTML::image(
				UPLOAD_URL.$config_image['path_to_img'].$config_image['thumbnails'][0]['prefix'].$product->fimage_product,
				array('class' => 'img-thumbnail img-item')
			) ?>
		</div>
		<div class="col-lg-9">
			<p><i class="fa fa-check-square-o ch"></i>
			<b><?= HTML::anchor(Route::url('shop_product', array('slug' => $product->slug)), $product->title) ?></b></p>
			<?= $product->preview ?>
			<?= 'Розничная цена:&nbsp;<span class="label label-success">' . Format::price($product->price) . '</span>' ?>
		</div>
	</div>
	<hr>
<?php endforeach ?>

<?= $text ?>
