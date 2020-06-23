<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php // Шаблон для вывода рубрики ?>
<h1><?php echo $category->title ?></h1>

<?php if ($category->show_description AND $category->description): ?>
	<hr>
	<?php echo $category->description ?>
<?php endif ?>


<?php // Шаблон для вывода подрубрик ?>
<?php if ($category->show_subs): ?>
	<?php if (count($subcategories) > 0): ?>
		<hr/>
		<div class="both"></div>
		<?php foreach ($subcategories as $subcategory): ?>
			<p>
				<i class="fa fa-book" style="color: #EE4498"></i>
				<b>
					<?= HTML::anchor(
						Route::url('shop_category', ['slug' => $subcategory->slug]),
						$subcategory->title
					) ?>
				</b>
			</p>
			<div class="item" style="margin-left: 18px;">
				<?php if ($category->show_subs_descriptions)
					echo $subcategory->description
				?>
			</div>
		<?php endforeach ?>
	<?php endif ?>
<?php endif ?>


<?php if ($pagination->total_items > 0): ?>
	<hr/>
	<div class="both"></div>
	<?php foreach ($products as $product): ?>
		<h2>
			<i class="fa fa-check-square-o"></i>
			<?= HTML::anchor(Route::url('shop_product', ['slug' => $product->slug]), $product->title) ?>
		</h2>

		<div class="row row-fluid">
			<div class="col-lg-3">
				<?php
				if ($category->show_products_images AND $product->fimage_product)
					echo HTML::image(
						UPLOAD_URL . $config_image['path_to_img'] . $config_image['thumbnails'][0]['prefix'] . $product->fimage_product,
						['class' => 'img-thumbnail img-item']
					)
				?>
			</div>
			<div class="col-lg-9">
				<?php
				if ($category->show_products_preview) {
					echo $product->preview;
				}
				?>
				<p>
					<?= 'Розничная цена:&nbsp;<strong>' . Format::price($product->price) . '</strong>' ?>
				</p>
				<p>
					<button class="btn-add-to-cart btn btn-xs btn-success" data-id="<?= $product->id ?>">
						<i class="fa fa-plus fa-fw"></i>
						В корзину
					</button>
					<?= HTML::anchor(Route::url('shop_cart'), '<i class="fa fa-shopping-cart fa-fw"></i> Моя корзина', ['class' => 'btn btn-xs btn-default']) ?>
				</p>
				<p>
					<?= HTML::anchor(Route::url('shop_product', ['slug' => $product->slug]), 'Смотреть полное описание...') ?>
				</p>
			</div>
		</div>
		<hr>
	<?php endforeach ?>
	<div class="both"></div>

	<?php echo $pagination ?>

<?php endif ?>
