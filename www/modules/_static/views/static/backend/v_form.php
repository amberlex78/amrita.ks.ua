<?php defined('SYSPATH') or die('No direct script access.') ?>

<ul id="myTab" class="nav nav-tabs">
	<li class="active">
		<a href="#static"><?php echo __('static.page') ?></a>
	</li>
	<li>
		<a href="#seo"><?php echo __('app.seo') ?></a>
	</li>
</ul>

<?php echo TB_Form::horizontal_open('') ?>
	<div class="tab-content">
		<div id="static" class="tab-pane fade active in">
			<?php

			// Used by js
			echo Form::hidden('current_action', Request::get('action'));

			echo TB_Form::control_group_with_error(
				Form::label('title', __('static.page_title'), array('required')),
				Form::input('title', $obj->title, array('class' => 'span10')),
				TB_Form::inline_error('title', $errors)
			);

			echo TB_Form::control_group(
				Form::label('text', __('static.page_text')),
				Editor::ck('text', $obj->text)
			);

			/*
			echo TB_Form::control_group(
				Form::label('title_menu', __('static.item_menu_title')),
				Form::input('title_menu', $obj->title_menu, array('class' => 'span10')),
				TB_Form::block_help(__('static.item_menu_title_h'))
			);
			*/

			?>
			<div class="control-group">
				<label class="control-label" for="fimage_static"><?php echo __('static.image') ?></label>
				<div class="controls clearfix">
					<div class="responses">
						<div id="response"><?php echo $v_th_image?></div>
					</div>
					<span class="btn fileinput-button">
						<i class="icon-folder-open"></i>
						<span><?php echo __('static.image_select') ?></span>
						<?php echo Form::file('fimage_static', array('rel' => $obj->id, 'id' => 'fimage_static')) ?>
					</span>
				</div>
			</div>
		</div>
		<div id="seo" class="tab-pane fade">
			<?php

			echo View::factory('app/backend/v_seo', array(
				'slug'   => $obj->slug,   'slug_h'   => __('static.slug_h'),
				'meta_t' => $obj->meta_t, 'meta_t_h' => __('static.meta_t_h'),
				'meta_d' => $obj->meta_d, 'meta_d_h' => __('static.meta_d_h'),
				'meta_k' => $obj->meta_k, 'meta_k_h' => __('static.meta_k_h'),
			))->bind('errors', $errors);

			?>
		</div>
	</div>
	<?php echo TB_Helpers::btns_save(); ?>
<?php echo TB_Form::close() ?>
