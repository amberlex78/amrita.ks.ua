<?php defined('SYSPATH') or die('No direct script access.') ?>

<ul id="myTab" class="nav nav-tabs">
	<li class="active">
		<a href="#product"><?php echo __('shop.product') ?></a>
	</li>
	<li>
		<a href="#categories"><?php echo __('shop.categories') ?></a>
	</li>
	<li>
		<a href="#seo"><?php echo __('app.seo') ?></a>
	</li>
	<li>
		<a href="#settings"><?php echo __('app.settings') ?></a>
	</li>
</ul>

<?php echo TB_Form::horizontal_open('') ?>
	<div class="tab-content">
		<div id="product" class="tab-pane fade active in">
			<?php

			// Used by js
			echo Form::hidden('current_action', Request::get('action'));

			echo TB_Form::control_group_with_error(
				Form::label('title', __('shop.product_title'), array('required')),
				Form::input('title', $obj->title, array('class' => 'span10')),
				TB_Form::inline_error('title', $errors)
			);

			echo TB_Form::control_group_with_error(
				Form::label('price', __('shop.product_price'), array('required')),
				Form::input('price', $obj->price, array('class' => 'span10', 'placeholder' => '0.00')),
				TB_Form::inline_error('price', $errors)
			);

			echo TB_Form::control_group(
				Form::label('preview', __('shop.product_preview')),
				Editor::ck('preview',  $obj->preview, array('height' => 100))
			);

			echo TB_Form::control_group(
				Form::label('text', __('shop.product_text')),
				Editor::ck('text',  $obj->text)
			);

			echo Form::hidden('pubdate', $obj->pubdate ? $obj->pubdate : date('Y-m-d'));

			if (isset($modules['_tags']))
			{
				echo TB_Form::control_group(
					Form::label('arr_tags', __('tags.tags')),
					TB_Form::append(
						Form::tm('arr_tags', 'product', $arr_tags, array('placeholder' => __('tags.tag_name'))),
						TB_Icon::tag()
					)
				);
			}

			?>
			<div class="control-group">
				<label class="control-label" for="fimage_product"><?php echo __('shop.product_image') ?></label>
				<div class="controls clearfix">
					<div class="responses">
						<div id="response"><?php echo $v_th_image?></div>
					</div>
					<span class="btn fileinput-button">
						<i class="icon-folder-open"></i>
						<span><?php echo __('shop.product_image_select') ?></span>
						<?php echo Form::file('fimage_product', array('rel' => $obj->id, 'id' => 'fimage_product')) ?>
					</span>
				</div>
			</div>
		</div>

		<div id="categories" class="tab-pane fade">
			<?php

			echo TB_Form::control_group(
				Form::label('text', __('shop.category_select')),
				$category_chboxes
			);

			?>
		</div>

		<div id="seo" class="tab-pane fade">
			<?php

			echo View::factory('app/backend/v_seo', array(
				'slug'   => $obj->slug,   'slug_h'   => __('shop.product_slug_h'),
				'meta_t' => $obj->meta_t, 'meta_t_h' => __('shop.product_meta_t_h'),
				'meta_d' => $obj->meta_d, 'meta_d_h' => __('shop.product_meta_d_h'),
				'meta_k' => $obj->meta_k, 'meta_k_h' => __('shop.product_meta_k_h'),
			))->bind('errors', $errors);

			?>
		</div>

		<div id="settings" class="tab-pane fade">
			<?php

			echo TB_Form::control_group(
				Form::label('show_description', __('shop.product')),
				TB_Form::labelled_checkbox('show_preview', __('shop.product_show_preview'), 1, isset($obj->show_preview) ? $obj->show_preview == 1 : true).
				TB_Form::labelled_checkbox('show_image',   __('shop.product_show_image'),   1, isset($obj->show_image)   ? $obj->show_image == 1   : true)
			);

			?>
		</div>
	</div>
	<?php echo TB_Helpers::btns_save(); ?>
<?php echo TB_Form::close() ?>
