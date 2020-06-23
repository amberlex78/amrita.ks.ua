<?php defined('SYSPATH') or die('No direct script access.') ?>

<ul id="myTab" class="nav nav-tabs">
	<li class="active">
		<a href="#rubric"><?php echo __('blog.rubric') ?></a>
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
		<div id="rubric" class="tab-pane fade active in">
			<?php

			echo TB_Form::control_group_with_error(
				Form::label('title', __('blog.rubric_title'), array('required')),
				Form::input('title', $obj->title, array('class' => 'span10')),
				TB_Form::inline_error('title', $errors),
				TB_Form::block_help(__('blog.rubric_title_h'))
			);

			echo TB_Form::control_group(
				Form::label('id', __('blog.rubric_parent')),
				Form::select('id', $for_select, $selected, array('id' => 'id', 'class' => 'span10')),
				TB_Form::block_help(__('blog.rubric_parent_h'))
			);

			echo TB_Form::control_group(
				Form::label('description', __('blog.rubric_description')),
				Editor::ck('description', $obj->description, array('height' => 250)),
				TB_Form::block_help(__('blog.rubric_description_h'))
			);

			?>
		</div>
		<div id="seo" class="tab-pane fade">
			<?php

			echo View::factory('app/backend/v_seo', array(
				'slug'   => $obj->slug,   'slug_h'   => __('blog.rubric_slug_h'),
				'meta_t' => $obj->meta_t, 'meta_t_h' => __('blog.rubric_meta_t_h'),
				'meta_d' => $obj->meta_d, 'meta_d_h' => __('blog.rubric_meta_d_h'),
				'meta_k' => $obj->meta_k, 'meta_k_h' => __('blog.rubric_meta_k_h'),
			))->bind('errors', $errors);

			?>
		</div>
		<div id="settings" class="tab-pane fade">
			<?php

			echo TB_Form::control_group(
				Form::label('show_description', __('blog.rubric')),
				TB_Form::labelled_checkbox('show_description',       __('blog.rubric_show_description'),      1, isset($obj->show_description)       ? $obj->show_description == 1       : TRUE).
				TB_Form::labelled_checkbox('show_subs',              __('blog.rubric_show_sub'),              1, isset($obj->show_subs)              ? $obj->show_subs == 1              : FALSE).
				TB_Form::labelled_checkbox('show_subs_descriptions', __('blog.rubric_show_descriptions_sub'), 1, isset($obj->show_subs_descriptions) ? $obj->show_subs_descriptions == 1 : FALSE)
			);

			//echo TB_Form::actions(__('blog.rubric_settings_posts'));

			echo TB_Form::control_group(
				Form::label('show_posts', __('blog.rubric_show_posts')),
				TB_Form::labelled_radio('show_posts', __('blog.rubric_only_this_rubric'), 1, isset($obj->show_posts) ? $obj->show_posts == 1 : TRUE).
				TB_Form::labelled_radio('show_posts', __('blog.rubric_from_sub_rubrics'), 0, isset($obj->show_posts) ? $obj->show_posts == 0 : FALSE)
			);

			echo TB_Form::control_group(
				Form::label('show_description', ''),
				TB_Form::labelled_checkbox('show_posts_preview', __('blog.rubric_show_preview_posts'), 1, isset($obj->show_posts_preview) ? $obj->show_posts_preview == 1 : TRUE).
				TB_Form::labelled_checkbox('show_posts_images',  __('blog.rubric_show_images_posts'),  1, isset($obj->show_posts_images)  ? $obj->show_posts_images == 1  : TRUE)
			);

			echo TB_Form::control_group(
				Form::label('posts_orderby', __('app.sort')),
				Form::select('posts_orderby',
					array(
						'pubdate' => __('app.date'),
						'title'   => __('app.name')
					)
					, $obj->posts_orderby ? $obj->posts_orderby : 'created'
					, array('id' => 'posts_orderby')
				)
			);

			echo TB_Form::control_group(
				Form::label('posts_orderto', __('app.sort_direction')),
				Form::select('posts_orderto',
					array(
						'desc' => __('app.sort_desc'),
						'asc'  => __('app.sort_asc')
					)
					, $obj->posts_orderto ? $obj->posts_orderto : 'desc'
					, array('id' => 'posts_orderto')
				)
			);

			?>
		</div>
	</div>
	<?php echo TB_Helpers::btns_save(); ?>
<?php echo TB_Form::close() ?>
