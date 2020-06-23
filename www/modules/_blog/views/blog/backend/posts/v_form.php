<?php defined('SYSPATH') or die('No direct script access.') ?>

<ul id="myTab" class="nav nav-tabs">
	<li class="active">
		<a href="#post"><?php echo __('blog.post') ?></a>
	</li>
	<li>
		<a href="#rubrics"><?php echo __('blog.rubrics') ?></a>
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
		<div id="post" class="tab-pane fade active in">
			<?php

			// Used by js
			echo Form::hidden('current_action', Request::get('action'));

			echo TB_Form::control_group_with_error(
				Form::label('title', __('blog.post_title'), array('required')),
				Form::input('title', $obj->title, array('class' => 'span10')),
				TB_Form::inline_error('title', $errors)
			);

			echo TB_Form::control_group(
				Form::label('preview', __('blog.post_preview')),
				Editor::ck('preview',  $obj->preview, array('height' => 100))
			);

			echo TB_Form::control_group(
				Form::label('text', __('blog.post_text')),
				Editor::ck('text',  $obj->text)
			);

			echo TB_Form::control_group(
				Form::label('pubdate', __('blog.post_pubdate')),
				TB_Form::append(
					Form::input('pubdate', $obj->pubdate ? $obj->pubdate : date('Y-m-d')),
					TB_Icon::calendar()
				)
			);

			if (isset($modules['_tags']))
			{
				echo TB_Form::control_group(
					Form::label('arr_tags', __('tags.tags')),
					TB_Form::append(
						Form::tm('arr_tags', 'post', $arr_tags, array('placeholder' => __('tags.tag_name'))),
						TB_Icon::tag()
					)
				);
			}

			?>
			<div class="control-group">
				<label class="control-label" for="fimage_post"><?php echo __('blog.post_image') ?></label>
				<div class="controls clearfix">
					<div class="responses">
						<div id="response"><?php echo $v_th_image?></div>
					</div>
					<span class="btn fileinput-button">
						<i class="icon-folder-open"></i>
						<span><?php echo __('blog.post_image_select') ?></span>
						<?php echo Form::file('fimage_post', array('rel' => $obj->id, 'id' => 'fimage_post')) ?>
					</span>
				</div>
			</div>
		</div>

		<div id="rubrics" class="tab-pane fade">
			<?php

			echo TB_Form::control_group(
				Form::label('text', __('blog.rubric_select')),
				$rubric_chboxes
			);

			?>
		</div>

		<div id="seo" class="tab-pane fade">
			<?php

			echo View::factory('app/backend/v_seo', array(
				'slug'   => $obj->slug,   'slug_h'   => __('blog.post_slug_h'),
				'meta_t' => $obj->meta_t, 'meta_t_h' => __('blog.post_meta_t_h'),
				'meta_d' => $obj->meta_d, 'meta_d_h' => __('blog.post_meta_d_h'),
				'meta_k' => $obj->meta_k, 'meta_k_h' => __('blog.post_meta_k_h'),
			))->bind('errors', $errors);

			?>
		</div>

		<div id="settings" class="tab-pane fade">
			<?php

			echo TB_Form::control_group(
				Form::label('show_description', __('blog.post')),
				TB_Form::labelled_checkbox('show_preview', __('blog.post_show_preview'), 1, isset($obj->show_preview) ? $obj->show_preview == 1 : FALSE).
				TB_Form::labelled_checkbox('show_image',   __('blog.post_show_image'),   1, isset($obj->show_image)   ? $obj->show_image == 1   : TRUE)
			);

			?>
		</div>
	</div>
	<?php echo TB_Helpers::btns_save(); ?>
<?php echo TB_Form::close() ?>
