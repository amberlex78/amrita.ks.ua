<?php defined('SYSPATH') or die('No direct script access.') ?>

<div class="row-fluid">
	<div class="col-sm-3">
		<?php echo HTML::image(
			'uploads/user/avatar/29d51df1d188ef664c85b4adb389e41ca52b2a95.jpg',
			array('class' => 'img-thumbnail', 'width' => '180', 'style' => 'float: left;'))
		?>
	</div>
	<div class="col-sm-9">
		<?php echo $text ?>
	</div>
</div>

<?php echo Form::open('', array('id' => 'frm_contact', 'class' => 'form-horizontal', 'role' => 'form')) ?>

		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<h2><?php echo HTML::chars(__('contact.title')) ?></h2>
			</div>
		</div>

		<div class="form-group<?php if (isset($errors['your_name'])) echo ' error' ?>">
			<label for="your_name" class="col-sm-3 control-label">
				<?php echo __('contact.your_name') ?>
				<span class="tbred">*</span>
			</label>
			<div class="col-sm-9">
				<?php echo Form::input('your_name', $form['your_name'], array('class' => 'form-control')) ?>
				<?php echo Form::error('your_name', $errors) ?>
			</div>
		</div>

		<div class="form-group<?php if (isset($errors['your_email'])) echo ' error' ?>">
			<label class="col-sm-3 control-label" for="your_email">
				<?php echo __('contact.your_email') ?>
				<span class="tbred">*</span>
			</label>
			<div class="col-sm-9">
				<?php echo Form::input('your_email', $form['your_email'], array('class' => 'form-control')) ?>
				<small><span class="help-inline">(<?php echo __('contact.but_never_shared') ?>)</span></small>
				<?php echo Form::error('your_email', $errors) ?>
			</div>
		</div>

		<?php // антиспам 1: это поле скрыто средствами css,
		      // при валидации оно должно быть пустым, если его заполнит робот - сообщение не отправится
		?>
		<div class="form-group your_subject_contact">
			<label class="col-sm-3 control-label" for="your_subject_contact"><?php echo __('contact.your_subject') ?></label>
			<div class="col-sm-9">
				<?php echo Form::input('your_subject_contact', $form['your_subject_contact'], array('class' => 'form-control')) ?>
			</div>
		</div>

		<div class="form-group<?php if (isset($errors['your_message'])) echo ' error' ?>">
			<label class="col-sm-3 control-label" for="your_message">
				<?php echo __('contact.your_message') ?>
				<span class="tbred">*</span>
			</label>
			<div class="col-sm-9">
				<?php echo Form::textarea('your_message',$form['your_message'] ,array('class' => 'form-control', 'id' => 'your_message', 'rows' => 5)) ?>
				<?php echo Form::error('your_message', $errors) ?>
			</div>
		</div>

		<?php // антиспам 2: value="ns" заменяется скриптом на value="ok"
		      // при валидации, если поле != ok - сообщение не отправится
		?>
		<input type="hidden" name="ns" value="ns">

		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<input type="button" class="btn btn-success" id="do_submit" value="<?php echo __('contact.send_message') ?>">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<p class="text-muted"><small><?php echo HTML::chars(__('contact.privacy')) ?></small></p>
			</div>
		</div>

<?php echo Form::close() ?>

<?php // антиспам 3: form action подставляется скриптом
      // если action не подставится - форма не отправится
?>
<script>var CONTACT_ACTION = '<?php echo Route::url('contact', array('action' => 'send')) ?>';</script>