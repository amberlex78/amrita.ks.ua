<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Backend_Categories
 */
class Controller_Backend_Categories extends Controller_Backend
{
	public $module = 'shop';

	// Block title on page
	public $block_title = 'shop.shop';

	/**
	 * List of categories
	 */
	public function action_list()
	{
		$obj = ORM::factory('Shop_Category', 1)
			->fulltree(FALSE);

		// Data for template
		$this->title   = __('shop.category_manager');
		$this->content = View::factory('shop/backend/categories/v_list')
			->bind('id', $id)
			->bind('obj', $obj);
	}

	/**
	 * Add new category
	 */
	public function action_add()
	{
		$obj  = ORM::factory('Shop_Category');

		if ($this->request->is_post())
		{
			$obj->pre_post();
			$obj->values($_POST);

			$parent = ORM::factory('Shop_Category', $this->request->post('id'));

			try
			{
				if ($parent->loaded())
					$obj->insert_as_last_child($parent->id);
				else
					$obj->insert_as_last_child(1);

				Message::set('success', __('shop.category_added'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}

		// Data for template
		$this->title   = __('shop.category_add');
		$this->content = View::factory('shop/backend/categories/v_form',
			array(
				'for_select' => Model_Shop_Category::for_select(1, __('shop.category_none')),
				'selected'   => $obj->id,
				'obj'        => $obj,
			))
			->bind('errors', $errors);
	}

	/**
	 * Edit category
	 */
	public function action_edit()
	{
		$obj = ORM::factory('Shop_Category', $this->request->param('id'));

		if ( ! $obj->loaded())
			throw new HTTP_Exception_404;

		$this->breadcrumbs = View::factory('shop/backend/v_breadcrumbs')
			->set('breadcrumbs', $obj->parents(FALSE, TRUE));

		if ($this->request->is_post())
		{
			$obj->pre_post();
			$obj->values($_POST);

			$parent = ORM::factory('Shop_Category', $this->request->post('id'));

			try
			{
				$obj->save();

				// If parent category was changed
				if ($obj->parent_id != $parent->id)
				{
					if ($parent->loaded())
						$obj->move_to_last_child($parent->id);
					else
						$obj->move_to_last_child(1);
				}

				Message::set('success', __('app.changes_saved'));
				$this->_redirect($obj->id);
			}
			catch (ORM_Validation_Exception $e)
			{
				Message::set('error', __('app.error_saving'));
				$errors = $e->errors('validation');
			}
		}

		// Data for template
		$this->title   = __('shop.category_edit');
		$this->content = View::factory('shop/backend/categories/v_form',
			array(
				'for_select' => Model_Shop_Category::for_select(1, __('shop.category_none')),
				'selected'   => $obj->parent_id,
				'obj'        => $obj,
			))
			->bind('errors', $errors);
	}

	/**
	 * Delete category
	 */
	public function action_delete()
	{
		$id = $this->request->param('id');

		$obj = ORM::factory('Shop_Category', $id);

		if ($obj->loaded() AND $obj->allow_delete)
		{
			$obj->delete();
			$obj->move_to_uncategorized();

			Message::set('success', __('shop.category_deleted'));
		}
		else
			Message::set('error', __('app.error_deleting'));

		$this->_redirect();
	}

	/**
	 * Move Up
	 */
	public function action_up()
	{
		$id = (int) $this->request->param('id');

		if ($id <= 1)
			HTTP::redirect(ADMIN.'/categories/list');

		$curr_obj = ORM::factory('Shop_Category', $id);

		if ( ! $curr_obj->loaded())
			HTTP::redirect(ADMIN.'/categories/list');

		if ($sibling = $curr_obj->get_prev_sibling())
		{
			$curr_obj->move_to_prev_sibling($sibling->id);
			Message::set('success', __('app.act_moved_up'));
		}
		else
			Message::set('error', __('app.error_moving_up'));

		$this->_redirect();
	}

	/**
	 * Move Down
	 */
	public function action_down()
	{
		$id = (int) $this->request->param('id');

		if ($id <= 1)
			HTTP::redirect(ADMIN.'/categories/list');

		$curr_obj = ORM::factory('Shop_Category', $id);

		if ( ! $curr_obj->loaded())
			HTTP::redirect(ADMIN.'/categories/list');

		if ($sibling = $curr_obj->get_next_sibling())
		{
			$curr_obj->move_to_next_sibling($sibling->id);
			Message::set('success', __('app.act_moved_down'));
		}
		else
			Message::set('error', __('app.error_moving_down'));

		$this->_redirect();
	}


	//==================================================================================================================
	//  Private methods

	/**
	 * Redirect to saved referer or editing
	 *
	 * @param string $id
	 */
	private function _redirect($id = NULL)
	{
		if (Arr::get($_POST, 'save_and_stay') == 'ok')
			if ($id !== NULL)
				HTTP::redirect(ADMIN.'/categories/edit/'.$id);

		HTTP::redirect(ADMIN.'/categories/list');
	}

	/**
	 * Init categories
	 * Exrcute this method if table `shop_categories` is empty
	 *
	 * Result in DB:
	 *   id = 1, parent_id = 0, lft = 1, rgt = 4, lvl = 1, scope = 1, allow_delete = 0, title = ROOT
	 *   id = 2, parent_id = 1, lft = 2, rgt = 3, lvl = 2, scope = 1, allow_delete = 0, title = Uncategorized
	 */
	private function _init_categories()
	{
		$cat = ORM::factory('Shop_Category');

		$cat->id           = 1;
		$cat->parent_id    = 0;
		$cat->allow_delete = 0;
		$cat->title        = 'ROOT';

		$cat->make_root()->clear();

		$cat->id           = 2;
		$cat->parent_id    = 1;
		$cat->allow_delete = 0;
		$cat->title        = 'Uncategorized';  // for en
//		$cat->title        = 'Без рубрики';    // for ru
		$cat->slug         = 'uncategorized';  // for en

		$cat->insert_as_last_child(1);
	}
}