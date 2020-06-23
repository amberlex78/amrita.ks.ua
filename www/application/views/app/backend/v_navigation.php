<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php if ($config->type_backend_menu == 'accordion'):  // Menu accordion ?>

	<div class="accordion" id="accordion2">
		<?php foreach ($blocks as $block => $name): ?>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"
					   href="#collapse_<?php echo $block ?>">
						<strong>
							<?php echo (isset($name['icon']) ? '<i class="icon-'.$name['icon'].'"></i> ' : '') . __($name['heading']) ?>
						</strong>
					</a>
				</div>
				<div id="collapse_<?php echo $block ?>" class="accordion-body collapse<?php if ($curr_block == $block) echo ' in' ?>">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<?php
							foreach ($name['menu'] as $menu)
							{
								if ($menu['title'] == 'hr')
									echo '<li class="divider"></li>';
								else
								{
									if (isset($menu['id']))
									{
										$id = '/'.$menu['id'];

										echo (Request::get('controller') == $menu['controller'] AND Request::get('action') == $menu['action'] AND Request::get('id') == $menu['id'])
											? '<li class="active">'
											: '<li>';
									}
									else
									{
										$id = '';

										echo (Request::get('controller') == $menu['controller'] AND Request::get('action') == $menu['action'])
											? '<li class="active">'
											: '<li>';
									}

									$menu_icon = isset($menu['icon'])
										? '<i class="icon-'.$menu['icon'].'"></i> '
										: '';

									echo Html::anchor(ADMIN.'/'.$menu['controller'].'/'.$menu['action'].$id, $menu_icon.__($menu['title']));

									echo '</li>';
								}
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>

<?php else:  // Menu simple ?>

	<div class="well" style="padding: 8px 0; background-color: #ffffff">
		<ul class="nav nav-list">
			<?php foreach ($blocks as $block => $name): ?>

				<li class="nav-header">
					<?php echo (isset($name['icon']) ? '<i class="icon-'.$name['icon'].'"></i> ' : '') . __($name['heading']) ?>
				</li>

				<?php
				foreach ($name['menu'] as $menu)
				{
					if ($menu['title'] == 'hr')
						echo '<li class="divider"></li>';
					else
					{
						if (isset($menu['id']))
						{
							$id = '/'.$menu['id'];

							echo (Request::get('controller') == $menu['controller'] AND Request::get('action') == $menu['action'] AND Request::get('id') == $menu['id'])
								? '<li class="active">'
								: '<li>';
						}
						else
						{
							$id = '';

							echo (Request::get('controller') == $menu['controller'] AND Request::get('action') == $menu['action'])
								? '<li class="active">'
								: '<li>';
						}

						$menu_icon = isset($menu['icon'])
							? '<i class="icon-'.$menu['icon'].'"></i> '
							: '';

						echo Html::anchor(ADMIN.'/'.$menu['controller'].'/'.$menu['action'].$id, $menu_icon.__($menu['title']));

						echo '</li>';
					}
				}
				?>

			<?php endforeach ?>
		</ul>
	</div>

<?php endif ?>