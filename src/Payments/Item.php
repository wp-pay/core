<?php
/**
 * Item
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Money\Money;
use stdClass;

/**
 * Item.
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Item {
	/**
	 * The ID.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * The description.
	 *
	 * @var string
	 */
	private $description;

	/**
	 * The quantity.
	 *
	 * @var int
	 */
	private $quantity;

	/**
	 * The price.
	 *
	 * @var float
	 */
	private $price;

	/**
	 * Constructs and initialize a iDEAL basic item.
	 */
	public function __construct() {
		$this->id          = '';
		$this->description = '';
		$this->quantity    = 1;
		$this->price       = 0;
	}

	/**
	 * Call.
	 *
	 * @link http://php.net/manual/de/language.oop5.magic.php
	 *
	 * @param string $name      Method name.
	 * @param array  $arguments Method arguments.
	 * @return string|int|float
	 */
	public function __call( $name, $arguments ) {
		$map = array(
			'getNumber'      => 'get_id',
			'setNumber'      => 'set_id',
			'setDescription' => 'set_description',
			'getQuantity'    => 'get_quantity',
			'setQuantity'    => 'set_quantity',
			'getPrice'       => 'get_price',
			'setPrice'       => 'set_price',
		);

		if ( isset( $map[ $name ] ) ) {
			$old_method = $name;
			$new_method = $map[ $name ];

			_deprecated_function( esc_html( __CLASS__ . '::' . $old_method ), '2.0.1', esc_html( __CLASS__ . '::' . $new_method ) );

			return call_user_func_array( array( $this, $new_method ), $arguments );
		}

		trigger_error( esc_html( 'Call to undefined method ' . __CLASS__ . '::' . $name . '()' ), E_USER_ERROR );
	}

	/**
	 * Get the id / identifier of this item.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set the id / identifier of this item.
	 *
	 * @param string $id Number.
	 */
	public function set_id( $id ) {
		$this->id = strval( $id );
	}

	/**
	 * Set the id / identifier of this item.
	 *
	 * @param string $id Number.
	 *
	 * @deprecated 2.0.8
	 */
	public function set_number( $id ) {
		_deprecated_function( __FUNCTION__, '2.0.8', 'Pronamic\WordPress\Pay\Payments\Item::set_id()' );

		$this->set_id( $id );
	}

	/**
	 * Get the description of this item.
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Set the description of this item.
	 * AN..max32 (AN = Alphanumeric, free text).
	 *
	 * @param string $description Description.
	 */
	public function set_description( $description ) {
		$this->description = substr( $description, 0, 32 );
	}

	/**
	 * Get the quantity of this item.
	 *
	 * @return int
	 */
	public function get_quantity() {
		return $this->quantity;
	}

	/**
	 * Set the quantity of this item
	 *
	 * @param int $quantity Quantity.
	 */
	public function set_quantity( $quantity ) {
		$this->quantity = $quantity;
	}

	/**
	 * Get the price of this item.
	 *
	 * @return float
	 */
	public function get_price() {
		return $this->price;
	}

	/**
	 * Set the price of this item.
	 *
	 * @param float|Money $price Price.
	 */
	public function set_price( $price ) {
		if ( $price instanceof Money ) {
			$price = $price->get_amount();
		}

		$this->price = $price;
	}

	/**
	 * Get the amount.
	 *
	 * @return float
	 */
	public function get_amount() {
		return $this->price * $this->quantity;
	}
}
