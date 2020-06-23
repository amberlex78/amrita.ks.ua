<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1>
	<i class="icon-file-text"></i>
	<?= $product->title ?>
</h1>

<div class="row row-fluid">
	<div class="col-lg-4">
		<?php if ($product->show_image)
			echo HTML::if_image(
				UPLOAD_URL . $config_image['path_to_img'] . $product->fimage_product,
				['class' => 'img-thumbnail img-item']
			) ?>
	</div>
	<div class="col-md-8">
		<?php if ($product->show_preview)
			echo $product->preview
		?>
		<p>Розничная цена:&nbsp;<strong><?= Format::price($product->price) ?></strong></p>
		<button class="btn-add-to-cart btn btn-success" data-id="<?= $product->id ?>">
			<i class="fa fa-plus fa-fw"></i>
			Добавить в корзину
		</button>
		<?= HTML::anchor(Route::url('shop_cart'), '<i class="fa fa-shopping-cart fa-fw"></i> Моя корзина', ['class' => 'btn btn-default']) ?>
	</div>
</div>

<br><p class="ch"><b>Описание</b></p>
<?= $product->text ?>

<div class="both"></div><hr>

<?= View::factory('app/partials/v_go_back') ?>

<hr>
<div id="mc-container"></div>
<script type="text/javascript">
	cackle_widget = window.cackle_widget || [];
	cackle_widget.push({widget: 'Comment', id: 27285});
	(function () {
		var mc = document.createElement('script');
		mc.type = 'text/javascript';
		mc.async = true;
		mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(mc, s.nextSibling);
	})();
</script>
<a id="mc-link" href="http://cackle.me">Social comments <b style="color:#4FA3DA">Cackl</b><b style="color:#F65077">e</b></a>