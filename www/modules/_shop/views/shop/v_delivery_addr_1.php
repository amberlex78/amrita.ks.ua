<?php defined('SYSPATH') or die('No direct script access.')
/**
 * @var $errors    array
 */
?>

<div class="form-group" id="div_city">
	<label for="np_city_ref" class="col-sm-2 control-label">Город</label>
	<div class="col-sm-10">
		<?= Form::select('np_city_ref',
			Model_Novaposhta_City::get_cities(),
			$obj_order->np_city_ref,
			['class' => 'form-control', 'id' => 'np_city_ref', 'data-live-search' => 'true', 'required']
		) ?>
	</div>
</div>

<div class="form-group" id="div_address">
	<?= $v_addresses ?>
</div>

<div class="form-group" id="city_link">
	<label class="col-sm-2 control-label"></label>

	<div class="col-sm-10">
		<p class="form-control-static">
			<?= HTML::anchor('', '', [
				'id'     => 'a_city',
				'target' => '_blank',
			]) ?>
		</p>
	</div>
</div>


<script>
	$(function () {

		$('#np_city_ref').selectpicker({
			size: 5
		});

		function showLinkToCity() {
			var cityRef = $('#np_city_ref').val();
			if (cityRef) {
				$('#div_address').show();
				$('#a_city').attr('href', 'http://novaposhta.ua/office/list?city=' + $('#np_city_ref option:selected').text());
				$('#a_city').empty().append('Смотреть отделения на сайте Новой Почты <i class="fa fa-external-link"></i>');

				$.post('/ajax/cart/warehouses', {
					city_ref : cityRef
				}, function(data) {
					$('#div_address').html(data.content);
				}, 'json');

			} else {
				$('#div_address').hide();
			}
		}

		showLinkToCity();
		$('#np_city_ref').change(function () {
			showLinkToCity();
		});
	});
</script>