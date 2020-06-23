<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Shopping Cart Class
 *
 * @package      CodeIgniter
 * @subpackage   Libraries
 * @category     Shopping Cart
 * @author       EllisLab Dev Team
 * @link         http://codeigniter.com/user_guide/libraries/cart.html
 */
class Cart
{
	/**
	 * @var array
	 */
	public $words = array('товар', 'товара', 'товаров');

	/**
	 * These are the regular expression rules that we use to validate the product ID and product name
	 * alpha-numeric, dashes, underscores, or periods
	 *
	 * @var string
	 */
	public $product_id_rules = '\.a-z0-9_-';

	/**
	 * These are the regular expression rules that we use to validate the product ID and product name
	 * alpha-numeric, dashes, underscores, colons or periods
	 *
	 * @var string
	 */
	public $product_name_rules = '\w \-\.\:';

	/**
	 * only allow safe product names
	 * @var bool
	 */
	public $product_name_safe = false;

	/**
	 * Contents of the cart
	 * @var array
	 */
	protected $_cart_contents = [];

	/**
	 * Shopping Class Constructor
	 * The constructor loads the Session class, used to store the shopping cart contents.
	 */
	public function __construct()
	{
		$this->_cart_contents = Session::instance()->get('cart_contents');
		if ($this->_cart_contents === null) {
			$this->_cart_contents = ['cart_total' => 0, 'total_items' => 0];
		}
	}

	/**
	 * Insert items into the cart and save it to the session table
	 * @param    array
	 * @return    bool
	 */
	public function insert($items = [])
	{
		if (!is_array($items) OR count($items) === 0) {
			return false;
		}

		$save_cart = false;
		if (isset($items['id'])) {
			if (($rowid = $this->_insert($items))) {
				$save_cart = true;
			}
		} else {
			foreach ($items as $val) {
				if (is_array($val) && isset($val['id'])) {
					if ($this->_insert($val)) {
						$save_cart = true;
					}
				}
			}
		}

		// Save the cart data if the insert was successful
		if ($save_cart === true) {
			$this->_save_cart();
			return isset($rowid) ? $rowid : true;
		}

		return false;
	}

	/**
	 * Insert
	 *
	 * @param    array
	 * @return    bool
	 */
	protected function _insert($items = [])
	{
		// Was any cart data passed? No? Bah...
		if (!is_array($items) OR count($items) === 0) {
			return false;
		}

		// Does the $items array contain an id, quantity, price, and name?  These are required
		if (!isset($items['id'], $items['qty'], $items['price'], $items['name'])) {
			return false;
		}

		// Prep the quantity. It can only be a number.  Duh... also trim any leading zeros
		$items['qty'] = (float)$items['qty'];

		// If the quantity is zero or blank there's nothing for us to do
		if ($items['qty'] == 0) {
			return false;
		}

		// Validate the product ID. It can only be alpha-numeric, dashes, underscores or periods
		if (!preg_match('/^[' . $this->product_id_rules . ']+$/i', $items['id'])) {
			return false;
		}

		// Validate the product name. It can only be alpha-numeric, dashes, underscores, colons or periods.
		if ($this->product_name_safe && !preg_match('/^[' . $this->product_name_rules . ']+$/iu', $items['name'])) {
			return false;
		}

		// Prep the price. Remove leading zeros and anything that isn't a number or decimal point.
		$items['price'] = (float)$items['price'];

		if (isset($items['options']) && count($items['options']) > 0) {
			$rowid = md5($items['id'] . serialize($items['options']));
		} else {
			$rowid = md5($items['id']);
		}

		// Now that we have our unique "row ID", we'll add our cart items to the master array
		// grab quantity if it's already there and add it on
		$old_quantity = isset($this->_cart_contents[$rowid]['qty']) ? (int)$this->_cart_contents[$rowid]['qty'] : 0;

		// Re-create the entry, just to make sure our index contains only the data from this submission
		$items['rowid'] = $rowid;
		$items['qty'] += $old_quantity;
		$this->_cart_contents[$rowid] = $items;

		return $rowid;
	}

	/**
	 * Update the cart
	 *
	 * This function permits the quantity of a given item to be changed.
	 * Typically it is called from the "view cart" page if a user makes
	 * changes to the quantity before checkout. That array must contain the
	 * product ID and quantity for each item.
	 *
	 * @param   array
	 * @return  bool
	 */
	public function update($items = [])
	{
		// Was any cart data passed?
		if (!is_array($items) OR count($items) === 0) {
			return false;
		}

		// You can either update a single product using a one-dimensional array,
		// or multiple products using a multi-dimensional one.  The way we
		// determine the array type is by looking for a required array key named "rowid".
		// If it's not found we assume it's a multi-dimensional array
		$save_cart = false;
		if (isset($items['rowid'])) {
			if ($this->_update($items) === true) {
				$save_cart = true;
			}
		} else {
			foreach ($items as $val) {
				if (is_array($val) && isset($val['rowid'])) {
					if ($this->_update($val) === true) {
						$save_cart = true;
					}
				}
			}
		}

		// Save the cart data if the insert was successful
		if ($save_cart === true) {
			$this->_save_cart();
			return true;
		}

		return false;
	}

	/**
	 * Update the cart
	 *
	 * This function permits changing item properties.
	 * Typically it is called from the "view cart" page if a user makes
	 * changes to the quantity before checkout. That array must contain the
	 * rowid and quantity for each item.
	 *
	 * @param   array
	 * @return  bool
	 */
	protected function _update($items = [])
	{
		// Without these array indexes there is nothing we can do
		if (!isset($items['rowid'], $this->_cart_contents[$items['rowid']])) {
			return false;
		}

		// Prep the quantity
		if (isset($items['qty'])) {
			$items['qty'] = (float)$items['qty'];
			// Is the quantity zero?  If so we will remove the item from the cart.
			// If the quantity is greater than zero we are updating
			if ($items['qty'] == 0) {
				unset($this->_cart_contents[$items['rowid']]);
				return true;
			}
		}

		// find updatable keys
		$keys = array_intersect(array_keys($this->_cart_contents[$items['rowid']]), array_keys($items));
		// if a price was passed, make sure it contains valid data
		if (isset($items['price'])) {
			$items['price'] = (float)$items['price'];
		}

		// product id & name shouldn't be changed
		foreach (array_diff($keys, ['id', 'name']) as $key) {
			$this->_cart_contents[$items['rowid']][$key] = $items[$key];
		}

		return true;
	}

	/**
	 * Save the cart array to the session DB
	 *
	 * @return  bool
	 */
	protected function _save_cart()
	{
		// Let's add up the individual prices and set the cart sub-total
		$this->_cart_contents['total_items'] = $this->_cart_contents['cart_total'] = 0;

		foreach ($this->_cart_contents as $key => $val) {

			// We make sure the array contains the proper indexes
			if (!is_array($val) OR !isset($val['price'], $val['qty'])) {
				continue;
			}

			$this->_cart_contents['cart_total'] += ($val['price'] * $val['qty']);
			$this->_cart_contents['total_items'] += $val['qty'];
			$this->_cart_contents[$key]['subtotal'] = ($this->_cart_contents[$key]['price'] * $this->_cart_contents[$key]['qty']);
		}

		// Is our cart empty? If so we delete it from the session
		if (count($this->_cart_contents) <= 2) {
			Session::instance()->delete('cart_contents');

			// Nothing more to do... coffee time!
			return false;
		}

		// If we made it this far it means that our cart has data.
		// Let's pass it to the Session class so it can be stored
		Session::instance()->set('cart_contents', $this->_cart_contents);

		// Woot!
		return true;
	}

	/**
	 * Cart Total
	 *
	 * @return  int
	 */
	public function total()
	{
		return $this->_cart_contents['cart_total'];
	}

	/**
	 * Remove Item
	 *
	 * Removes an item from the cart
	 *
	 * @param   int
	 * @return  bool
	 */
	public function remove($rowid)
	{
		// unset & save
		unset($this->_cart_contents[$rowid]);
		$this->_save_cart();
		return true;
	}

	/**
	 * Total Items
	 *
	 * Returns the total item count
	 *
	 * @return  int
	 */
	public function total_items()
	{
		return $this->_cart_contents['total_items'];
	}

	/**
	 * Cart Contents
	 *
	 * Returns the entire cart array
	 *
	 * @param   bool
	 * @return  array
	 */
	public function contents($newest_first = false)
	{
		// do we want the newest first?
		$cart = ($newest_first) ? array_reverse($this->_cart_contents) : $this->_cart_contents;

		// Remove these so they don't create a problem when showing the cart table
		unset($cart['total_items']);
		unset($cart['cart_total']);

		return $cart;
	}

	/**
	 * Get cart item
	 *
	 * Returns the details of a specific item in the cart
	 *
	 * @param   string $row_id
	 * @return  array
	 */
	public function get_item($row_id)
	{
		return (in_array($row_id, ['total_items', 'cart_total'], true) OR !isset($this->_cart_contents[$row_id]))
			? false
			: $this->_cart_contents[$row_id];
	}

	/**
	 * Has options
	 *
	 * Returns TRUE if the rowid passed to this function correlates to an item
	 * that has options associated with it.
	 *
	 * @param   string $row_id = ''
	 * @return  bool
	 */
	public function has_options($row_id = '')
	{
		return (isset($this->_cart_contents[$row_id]['options']) && count($this->_cart_contents[$row_id]['options']) !== 0);
	}

	/**
	 * Product options
	 *
	 * Returns the an array of options, for a particular product row ID
	 *
	 * @param   string $row_id = ''
	 * @return  array
	 */
	public function product_options($row_id = '')
	{
		return isset($this->_cart_contents[$row_id]['options']) ? $this->_cart_contents[$row_id]['options'] : [];
	}

	/**
	 * Format Number
	 *
	 * Returns the supplied number with commas and a decimal point.
	 *
	 * @param   string $n
	 * @return  string
	 */
	public function format_number($n = '')
	{
		return ($n === '') ? '' : number_format((float)$n, 2, ',', '.');
	}

	/**
	 * Destroy the cart
	 *
	 * Empties the cart and kills the session
	 *
	 * @return  void
	 */
	public function destroy()
	{
		$this->_cart_contents = ['cart_total' => 0, 'total_items' => 0];
		Session::instance()->delete('cart_contents');
	}
}
