<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Common helper functions used by Bootstrapper.
 *
 * @category   HTML/UI
 * @package    TB
 * @subpackage Twitter
 * @author     Alexey Abrosimov - <amberlex78@gmail.com>
 * @license    MIT License <http://www.opensource.org/licenses/mit>
 *
 */
class TB_Helpers
{
	private static $class_for_delete = NULL;

	/**
	 * Icon folder for tree with padding (used by backend)
	 *
	 * @param $val
	 * @param $icon
	 *
	 * @return string
	 */
	public static function lvl($val, $icon = 'folder-open-alt')
	{
		$lvl = ($val > 2) ? 22 * ($val - 2) : 0;

		return '<i class="icon-' . $icon . '" style="padding-left: ' . $lvl . 'px; padding-right: 6px;"></i>';
	}

	/**
	 * Create HTML link anchors for edit with level ident (used by backend)
	 *
	 * @param $lvl
	 * @param $uri
	 * @param $title
	 *
	 * @return string
	 */
	public static function anchor_level($lvl, $uri, $title)
	{
		return self::lvl($lvl).
			HTML::anchor($uri, $title, array('rel' => 'tooltip', 'data-original-title' => __('app.act_edit')));
	}

	/**
	 * Dotted line
	 *
	 * @return string
	 */
	public static function dotted()
	{
		return '<span class="muted">...</span>';
	}

	/**
	 * Create HTML link anchors for edit (used by backend)
	 *
	 * @param $uri
	 * @param $title
	 * @param $ico
	 *
	 * @return string
	 */
	public static function anchor_edit($uri, $title, $ico = FALSE)
	{
		if ($ico)
			$ico = '<i class="icon-'.$ico.'" style="padding-left: 0px; padding-right: 6px;"></i>';

		return $ico.HTML::anchor($uri, $title, array('rel' => 'tooltip', 'data-original-title' => __('app.act_edit')));
	}

	/**
	 * Create HTML link anchors blank with icon-external-link (used by backend)
	 *
	 * @param $uri
	 * @param $title
	 *
	 * @return string
	 */
	public static function anchor_blank($uri, $title)
	{
		return HTML::anchor($uri, $title, array(
				'target' => '_blank',
				'rel' => 'tooltip',
				'data-original-title' => __('app.act_view_link'))
		).' <i class="icon-external-link muted"></i>';
	}

	/**
	 * Top buttons (used by backend)
	 *
	 * Example: TB_Helpers::btn_top('static', array('add'=>'Add page', 'reset'=>'Reset sort'));
	 *
	 * Generated html:
	 *
	 * <div class="btns-top">
	 *     <div class="btn-group">
	 *         <a class="btn-primary btn" href="http://domain.dev/admin/static/add">
	 *             <i class="icon-plus icon-white"></i>
	 *             Add page
	 *         </a>
	 *         <a class="btn" href="http://domain.dev/admin/static/index">
	 *             <i class="icon-angle-right"></i>
	 *             Reset sort
	 *         </a>
	 *     </div>
	 * </div>
	 *
	 * @param $controller
	 * @param $id
	 * @param $actions
	 * @param $with_div_tag
	 *
	 * @return string
	 */
	public static function btn_top($controller, $id = NULL, array $actions, $with_div_tag = TRUE)
	{
		$buttons = '';

		foreach ($actions as $action => $title)
		{
			if ($action == 'add')
				$buttons .= TB_Button::link(ADMIN.'/'.$controller.'/add'.($id ? '/'.$id : ''), $title, array('class' => 'btn-small'))
					->with_icon('plus icon-white');

			if ($action == 'edit')
				$buttons .= TB_Button::link(ADMIN.'/'.$controller.'/edit/'.$id, $title, array('class' => 'btn-small'))
					->with_icon('pencil');

			if ($action == 'reset')
				if (Arr::get($_GET, 'order') AND Arr::get($_GET, 'colunm'))
					$buttons .= TB_Button::link(Request::detect_uri(), $title, array('class' => 'btn-small'))
						->with_icon('angle-right');
		}

		$buttons = TB_ButtonGroup::open().$buttons.TB_ButtonGroup::close();

		return $with_div_tag ? '<div class="btns-top">'.$buttons.'</div>' : $buttons;
	}

	/**
	 * Buttons for actions (used by backend)
	 *
	 * Example: TB_Helpers::btn_actions('static', $o->id)
	 *
	 * Generated html:
	 *
	 * <div class="btns-actions">
	 *     <a data-original-title="Edit this item" rel="tooltip" href="http://domain.dev/admin/static/edit/10">
	 *         <i class="icon-pencil icon-large"></i>
	 *     </a>
	 *     <a data-original-title="Remove this item" rel="tooltip" class="delete-static tbred" href="http://blog.dev/domain/static/delete/10">
	 *         <i class="icon-trash icon-large"></i>
	 *     </a>
	 * </div>
	 *
	 * @param $controller
	 * @param $id
	 * @param $actions
	 *
	 * @return $this
	 */
	public static function btn_actions($controller, $id, $actions = array('edit', 'delete'))
	{
		if (self::$class_for_delete === NULL)
		{
			// If the controller integral - take the last part
			// Example: publications_article - take article
			$parts = explode('_', $controller);

			// Class for js confirm the deletion
			self::$class_for_delete = 'delete-'.end($parts).' ';
		}

		$buttons = '';

		foreach ($actions as $action)
		{
			if ($action == 'edit')
			{
				$buttons .= HTML::anchor(ADMIN.'/'.$controller.'/edit/'.$id,
					'<i class="icon-pencil icon-large"></i>',
					array(
						'rel' => 'tooltip',
						'data-original-title' => __('app.act_edit')
					)
				);
			}

			if ($action == 'delete')
			{
				$buttons .= HTML::anchor(ADMIN.'/'.$controller.'/delete/'.$id,
					'<i class="icon-trash icon-large"></i>',
					array(
						'rel' => 'tooltip',
						'data-original-title' => __('app.act_delete'),
						'class' => self::$class_for_delete.'tbred',
					)
				);
			}

			if ($action == 'up')
				$buttons .= HTML::anchor(ADMIN.'/'.$controller.'/up/'.$id,
					'<i class="icon-arrow-up icon-large"></i>',
					array(
						'rel' => 'tooltip',
						'data-original-title' => __('app.act_move_up'),
					)
				);

			if ($action == 'down')
				$buttons .= HTML::anchor(ADMIN.'/'.$controller.'/down/'.$id,
					'<i class="icon-arrow-down icon-large"></i>',
					array(
						'rel' => 'tooltip',
						'data-original-title' => __('app.act_move_down'),
					)
				);
		}

		return '<div class="btns-actions">'.$buttons.'</div>';
	}

	/**
	 * Button for status (used by backend)
	 *
	 * @param $enabled
	 * @param $id
	 *
	 * @return string
	 */
	public static function btn_enabled($enabled, $id)
	{
		return TB_Icon::ok_sign(array(
				'id' => $id,
				'class' => 'change-enabled icon-large text-'.($enabled ? 'success' : 'error'),
				'style' => 'cursor: pointer;',
				'rel' => 'tooltip',
				'data-original-title' => __('app.status_change')
			));
	}

	/**
	 * Save button (used by backend)
	 *
	 * @return string
	 */
	public static function btn_save()
	{
		return '
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">
					<i class="icon-save icon-white"></i>
					' . __('app.act_save') . '
				</button>
			</div>
		';
	}

	/**
	 * Save buttons (used by backend)
	 *
	 * @return string
	 */
	public static function btns_save()
	{
		return '
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">
					<i class="icon-save icon-white"></i>
					' . __('app.act_save') . '
				</button>
				<button type="submit" name="save_and_stay" value="ok" class="btn">
					<i class="icon-ok icon-white"></i>
					' . __('app.act_save_and_continue') . '
				</button>
			</div>
		';
	}
}
