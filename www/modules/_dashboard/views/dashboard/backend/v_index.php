<?php defined('SYSPATH') or die('No direct script access.') ?>

<h1><?= __('dashboard.control_panel') ?></h1>

<div class="row-fluid">
	<div class="span4">
		<div class="alert alert-info">
			<h4>
				<i class="icon icon-shopping-cart"></i>
				Всего заказов: <?= $total_orders ?>
			</h4>
			<?= HTML::anchor(
				Route::url('backend', ['controller' => 'orders', 'action' => 'list']),
				'Смотреть все...'
			) ?>
		</div>
	</div>
	<div class="span4">
		<div class="alert alert-info">
			<h4>
				<i class="icon icon-user"></i>
				Всего клиентов: <?= $total_customers ?>
			</h4>
			<?= HTML::anchor(
				Route::url('backend', ['controller' => 'customers', 'action' => 'list']),
				'Смотреть все...'
			) ?>
		</div>
	</div>
	<div class="span4">
		<div class="alert alert-info">
			<h4>
				<i class="icon icon-credit-card"></i>
				Сумма: <?= Format::price($total_sum) ?>
			</h4>
			<?= HTML::anchor(
				Route::url('backend', ['controller' => 'orders', 'action' => 'list']),
				'Смотреть все...'
			) ?>
		</div>
	</div>
</div>


<h3>
	<i class="icon icon-shopping-cart"></i>
	Последние заказы
</h3>

<?php if (count($obj_orders)): ?>

	<table class="table">
		<tr>
			<th>Номер</th>
			<th>Сумма</th>
			<th>Дата заказа</th>
			<th>Статус</th>
			<th>Клиент</th>
			<th>Телефон</th>
			<th>Действия</th>
		</tr>
		<?php foreach ($obj_orders as $order): ?>
			<tr>
				<td>
					<?= HTML::anchor(
						Route::url('backend', ['controller' => 'orders', 'action' => 'edit', 'id' => $order->id]),
						'№' . $order->number, [
							'rel' => 'tooltip',
							'data-original-title' => 'Редактировать заказ',
						]
					) ?>
				</td>
				<td><?= Format::price($order->total) ?></td>
				<td><?= Date::format($order->created, Date::FULL) ?></td>
				<td><?= $order->status->get_title() ?></td>
				<td>
					<?= HTML::anchor(
						Route::url('backend', ['controller' => 'customers', 'action' => 'view', 'id' => $order->customer->id]),
						$order->customer->fio, [
							'rel' => 'tooltip',
							'data-original-title' => 'Профиль и заказы клиента',
						]
					) ?>
				</td>
				<td><?= Format::mobile($order->customer->phone) ?></td>
				<td><?= TB_Helpers::btn_actions('orders', $order->id) ?></td>
			</tr>
		<?php endforeach ?>
	</table>

<?php else: ?>

	<div class="alert alert-block alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Заказов нет.
	</div>

<?php endif ?>

<h3>
	<i class="icon-random"></i>
	Ссылки
</h3>
<p>
	<?= HTML::anchor(
		'https://mail.google.com/mail/u/0/#inbox',
		'Перейти на: Email amrita.ks.ua@google.com',
		['target' => '_blank']
	) . ' <i class="icon-share-alt muted"></i>' ?>
</p>
<p>
	<?= HTML::anchor(
		'https://www.facebook.com/groups/209319549245462/',
		'Перейти на: Facebook Дары Амриты',
		['target' => '_blank']
	) . ' <i class="icon-share-alt muted"></i>' ?>
</p>
<p>
	<?= HTML::anchor(
		'https://www.youtube.com/channel/UCLXZmb9iN8ag1Em1HLeepxw',
		'Перейти на: YouTube Дары Амриты',
		['target' => '_blank']
	) . ' <i class="icon-share-alt muted"></i>' ?>
</p>

<h3>
	<i class="icon-user"></i>
	Пользователь <?= $user->first_name . ' ' . $user->last_name ?>
</h3>
<table class="table table-bordered">
	<tr>
		<td>Полученная информация</td>
		<td>
			<?php foreach (Request::user_agent(['browser', 'version', 'mobile', 'platform']) as $type => $info) {
				if ($info) {
					switch ($type) {
						case 'browser':
							$type = 'Браузер:&nbsp;';
							break;
						case 'version':
							$type = ', v&nbsp;';
							break;
						case 'mobile':
							$type = '<br>ОС:&nbsp;';
							break;
						case 'platform':
							$type = '<br>ОС:&nbsp;';
							break;
					}
					echo $type . $info;
				}
			} ?>
		</td>
	</tr>
	<tr>
		<td>Строка агента пользователя</td>
		<td><?= Request::$user_agent ?></td>
	</tr>
	<tr>
		<td>Ваш IP</td>
		<td><?= Request::$client_ip ?></td>
	</tr>
	<tr>
		<td>Версия PHP на сервере</td>
		<td><?= phpversion() ?></td>
	</tr>
</table>
