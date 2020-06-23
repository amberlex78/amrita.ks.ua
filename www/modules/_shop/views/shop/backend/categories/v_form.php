<?php defined('SYSPATH') or die('No direct script access.') ?>

<ul id="myTab" class="nav nav-tabs">
	<li class="active">
		<a href="#category"><?php echo __('shop.category') ?></a>
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
		<div id="category" class="tab-pane fade active in">
			<?php

			echo TB_Form::control_group_with_error(
				Form::label('title', __('shop.category_title'), array('required')),
				Form::input('title', $obj->title, array('class' => 'span10')),
				TB_Form::inline_error('title', $errors),
				TB_Form::block_help(__('shop.category_title_h'))
			);

			echo TB_Form::control_group(
				Form::label('id', __('shop.category_parent')),
				Form::select('id', $for_select, $selected, array('id' => 'id', 'class' => 'span10')),
				TB_Form::block_help(__('shop.category_parent_h'))
			);

			echo TB_Form::control_group(
				Form::label('description', __('shop.category_description')),
				Editor::ck('description', $obj->description, array('height' => 250)),
				TB_Form::block_help(__('shop.category_description_h'))
			);

			?>
		</div>
		<div id="seo" class="tab-pane fade">
			<?php

			echo View::factory('app/backend/v_seo', array(
				'slug'   => $obj->slug,   'slug_h'   => __('shop.category_slug_h'),
				'meta_t' => $obj->meta_t, 'meta_t_h' => __('shop.category_meta_t_h'),
				'meta_d' => $obj->meta_d, 'meta_d_h' => __('shop.category_meta_d_h'),
				'meta_k' => $obj->meta_k, 'meta_k_h' => __('shop.category_meta_k_h'),
			))->bind('errors', $errors);

			?>
		</div>
		<div id="settings" class="tab-pane fade">
			<?php

			echo TB_Form::control_group(
				Form::label('show_description', __('shop.category')),
				TB_Form::labelled_checkbox('show_description',       __('shop.category_show_description'),      1, isset($obj->show_description)       ? $obj->show_description == 1       : TRUE).
				TB_Form::labelled_checkbox('show_subs',              __('shop.category_show_sub'),              1, isset($obj->show_subs)              ? $obj->show_subs == 1              : FALSE).
				TB_Form::labelled_checkbox('show_subs_descriptions', __('shop.category_show_descriptions_sub'), 1, isset($obj->show_subs_descriptions) ? $obj->show_subs_descriptions == 1 : FALSE)
			);

			//echo TB_Form::actions(__('shop.category_settings_products'));

			echo TB_Form::control_group(
				Form::label('show_products', __('shop.category_show_products')),
				TB_Form::labelled_radio('show_products', __('shop.category_only_this_category'), 1, isset($obj->show_products) ? $obj->show_products == 1 : TRUE).
				TB_Form::labelled_radio('show_products', __('shop.category_from_sub_categories'), 0, isset($obj->show_products) ? $obj->show_products == 0 : FALSE)
			);

			echo TB_Form::control_group(
				Form::label('show_description', ''),
				TB_Form::labelled_checkbox('show_products_preview', __('shop.category_show_preview_products'), 1, isset($obj->show_products_preview) ? $obj->show_products_preview == 1 : TRUE).
				TB_Form::labelled_checkbox('show_products_images',  __('shop.category_show_images_products'),  1, isset($obj->show_products_images)  ? $obj->show_products_images == 1  : TRUE)
			);

			echo TB_Form::control_group(
				Form::label('products_orderby', __('app.sort')),
				Form::select('products_orderby',
					array(
						'pubdate' => __('app.date'),
						'title'   => __('app.name')
					)
					, $obj->products_orderby ? $obj->products_orderby : 'created'
					, array('id' => 'products_orderby')
				)
			);

			echo TB_Form::control_group(
				Form::label('products_orderto', __('app.sort_direction')),
				Form::select('products_orderto',
					array(
						'desc' => __('app.sort_desc'),
						'asc'  => __('app.sort_asc')
					)
					, $obj->products_orderto ? $obj->products_orderto : 'desc'
					, array('id' => 'products_orderto')
				)
			);

			?>
		</div>
	</div>
	<?php echo TB_Helpers::btns_save(); ?>
<?php echo TB_Form::close() ?>
