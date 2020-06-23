<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php // Шаблон для вывода рубрики ?>
<h1><?php echo $rubric->title ?></h1>
<?php if ($rubric->show_description AND $rubric->description): ?>
	<hr>
	<?php echo $rubric->description ?>
<?php endif ?>


<?php // Шаблон для вывода подрубрик ?>
<?php if ($rubric->show_subs): ?>
	<?php if (count($subrubrics) > 0): ?>
		<hr/>
		<div class="both"></div>
		<?php foreach ($subrubrics as $subrubric): ?>
			<p>
				<i class="fa fa-book" style="color: #EE4498"></i>
				<b><?php echo HTML::anchor(Route::url('blog_rubric', array('slug' => $subrubric->slug)), $subrubric->title) ?></b>
			</p>
			<div class="item" style="margin-left: 18px;">
				<?php if ($rubric->show_subs_descriptions)
					echo $subrubric->description
				?>
			</div>
		<?php endforeach ?>
	<?php endif ?>
<?php endif ?>


<?php // Шаблон для вывода записей рубрики (рубрик)

	$is_news = FALSE;
	$fa = '<i class="fa fa-check-square-o"></i> ';

	if (Request::get('route') == 'blog_rubric' AND (Request::get('slug') == 'news' OR Request::get('slug') == 'articles'))
	{
		$is_news = TRUE;
		$fa = '<i class="fa fa-file-text-o"></i> ';
	}
?>

<?php if ($pagination->total_items > 0): ?>
	<hr/>
	<div class="both"></div>
	<?php foreach ($posts as $post): ?>
		<div class="item">
			<h2>
				<?php echo $fa.HTML::anchor(Route::url('blog_post', array('slug' => $post->slug)), $post->title) ?>
			</h2>
			<?php
			if ($rubric->show_posts_images AND $post->fimage_post)
				echo HTML::image(
					UPLOAD_URL.$config_image['path_to_img'].$config_image['thumbnails'][0]['prefix'].$post->fimage_post,
					array('class' => 'img-thumbnail img-item')
				)
			?>
			<?php if ($rubric->show_posts_preview)
				echo $post->preview
			?>
			<?php if ($is_news): ?>
				<p class="text-right">
					<small>
						<?php echo Date::d_m_Y($post->pubdate) ?>
						<i class="fa fa-calendar fa-fw"></i>
					</small>
				</p>
			<?php endif ?>
			<div class="both"></div>
			<hr>
		</div>
	<?php endforeach ?>
	<div class="both"></div>
	<?php echo $pagination ?>
<?php endif ?>
