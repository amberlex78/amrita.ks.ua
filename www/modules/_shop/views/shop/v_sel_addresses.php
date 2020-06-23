<?php defined('SYSPATH') or die('No direct script access.')
/**
 * @var $sel_addresses  array
 */
?>

<label for="np_address_ref" class="col-sm-2 control-label">Адрес</label>
<div class="col-sm-10">
	<?= Form::select('np_address_ref', $sel_addresses, '',
		['class' => 'form-control', 'id' => 'np_address_ref', 'data-live-search' => 'true', 'required']
	) ?>
</div>

<script>
	$(function () {
		$('#np_address_ref').selectpicker({
			size: 5
		});
	});
</script>