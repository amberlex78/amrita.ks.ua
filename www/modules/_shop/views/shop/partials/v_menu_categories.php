<?php defined('SYSPATH') or die('No direct script access.') ?>

<div class="panel panel-default">
	<div class="panel-heading">
		<i class="fa fa-book fa-fw"></i>
		Каталог продукции
	</div>
	<div class="panel-body">
		<?php echo Menu::v_menu_ul(Model_Shop_Category::arr_tree_category(), 'shop_category', 'title') ?>
	</div>
</div>
