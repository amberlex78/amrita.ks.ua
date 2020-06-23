<?php defined('SYSPATH') or die('No direct script access.')
/**
 * @var $errors    array
 */
?>

<div class="form-group">
	<label for="oblast" class="col-sm-2 control-label">Область</label>
	<div class="col-sm-10">
		<?= Form::input('oblast', $obj_order->oblast, ['class' => 'form-control', 'placeholder' => 'Область', 'required']) ?>
		<?= Form::error('oblast', $errors) ?>
	</div>
</div>

<div class="form-group">
	<label for="region" class="col-sm-2 control-label">Район</label>
	<div class="col-sm-10">
		<?= Form::input('region', $obj_order->city, ['class' => 'form-control', 'placeholder' => 'Район', 'required']) ?>
		<?= Form::error('region', $errors) ?>
	</div>
</div>

<div class="form-group">
	<label for="city" class="col-sm-2 control-label">Город</label>
	<div class="col-sm-10">
		<?= Form::input('city', $obj_order->city, ['class' => 'form-control', 'placeholder' => 'Населенный пункт', 'required']) ?>
		<?= Form::error('city', $errors) ?>
	</div>
</div>

<div class="form-group">
	<label for="address" class="col-sm-2 control-label">Адрес</label>
	<div class="col-sm-10">
		<?= Form::input('address', $obj_order->address, ['class' => 'form-control', 'placeholder' => 'Адрес', 'required']) ?>
		<?= Form::error('address', $errors) ?>
	</div>
</div>

<div class="form-group">
	<label for="postcode" class="col-sm-2 control-label">Индекс</label>
	<div class="col-sm-10">
		<?= Form::input('postcode', $obj_order->postcode, ['class' => 'form-control', 'placeholder' => 'Индекс', 'required']) ?>
		<?= Form::error('postcode', $errors) ?>
	</div>
</div>
