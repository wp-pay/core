<?php
/**
 * Payment info
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use InvalidArgumentException;
use Pronamic\WordPress\Money\Money;
use Pronamic\WordPress\DateTime\DateTime;
use Pronamic\WordPress\Money\TaxedMoney;
use Pronamic\WordPress\Pay\Address;
use Pronamic\WordPress\Pay\Core\Gateway;
use Pronamic\WordPress\Pay\Customer;
use Pronamic\WordPress\Pay\CreditCard;
use Pronamic\WordPress\Pay\Core\Statuses;
use Pronamic\WordPress\Pay\MoneyJsonTransformer;
use Pronamic\WordPress\Pay\Subscriptions\Subscription;
use Pronamic\WordPress\Pay\TaxedMoneyJsonTransformer;
use WP_Post;

/**
 * Payment info
 *
 * @author  Remco Tolsma
 * @version 2.1.0
 * @since   1.0.0
 */
abstract class PaymentInfo {
	/**
	 * The post object.
	 *
	 * @var WP_Post|null
	 */
	public $post;

	/**
	 * The date of this payment info.
	 *
	 * @var DateTime
	 */
	public $date;

	/**
	 * The unique ID of this payment info.
	 *
	 * @var int|null
	 */
	protected $id;

	/**
	 * The title of this payment info.
	 *
	 * @var string|null
	 */
	public $title;

	/**
	 * The configuration ID.
	 *
	 * @var int|null
	 */
	public $config_id;

	/**
	 * The key of this payment info, used in URL's for security.
	 *
	 * @var string|null
	 */
	public $key;

	/**
	 * Identifier for the source which started this payment info.
	 * For example: 'woocommerce', 'gravityforms', 'easydigitaldownloads', etc.
	 *
	 * @var string|null
	 */
	public $source;

	/**
	 * Unique ID at the source which started this payment info, for example:
	 * - WooCommerce order ID.
	 * - Easy Digital Downloads payment ID.
	 * - Gravity Forms entry ID.
	 *
	 * @var string|null
	 */
	public $source_id;

	/**
	 * The order ID of this payment.
	 *
	 * @todo Is this required/used?
	 *
	 * @var string|null
	 */
	public $order_id;

	/**
	 * The total amount of this payment.
	 *
	 * @var TaxedMoney
	 */
	private $total_amount;

	/**
	 * The shipping amount of this payment.
	 *
	 * @var Money|null
	 */
	private $shipping_amount;

	/**
	 * The description of this payment.
	 *
	 * @var string|null
	 */
	public $description;

	/**
	 * The name of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 *
	 * @var string|null
	 */
	public $consumer_name;

	/**
	 * The account number of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 *
	 * @var string|null
	 */
	public $consumer_account_number;

	/**
	 * The IBAN of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 *
	 * @var string|null
	 */
	public $consumer_iban;

	/**
	 * The BIC of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 *
	 * @var string|null
	 */
	public $consumer_bic;

	/**
	 * The city of the consumer of this payment.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 *
	 * @var string|null
	 */
	public $consumer_city;

	/**
	 * The Google Analytics client ID of the user who started this payment.
	 *
	 * @var string|null
	 */
	public $analytics_client_id;

	/**
	 * Google Analytics e-commerce tracked.
	 *
	 * @var bool|null
	 */
	public $ga_tracked;

	/**
	 * The email of the user who started this payment.
	 *
	 * @var string|null
	 */
	public $email;

	/**
	 * The payment method chosen by the user who started this payment.
	 *
	 * @var string|null
	 */
	public $method;

	/**
	 * The issuer chosen by the user who started this payment.
	 *
	 * @var string|null
	 */
	public $issuer;

	/**
	 * Customer.
	 *
	 * @var Customer|null
	 */
	public $customer;

	/**
	 * Billing address.
	 *
	 * @var Address|null
	 */
	public $billing_address;

	/**
	 * Shipping address.
	 *
	 * @var Address|null
	 */
	public $shipping_address;

	/**
	 * Payment lines.
	 *
	 * @var PaymentLines|null
	 */
	public $lines;

	/**
	 * Version.
	 *
	 * @var string|null
	 */
	private $version;

	/**
	 * Mode.
	 *
	 * @var string|null
	 */
	private $mode;

	/**
	 * Is anonymized.
	 *
	 * @var bool|null
	 */
	private $anonymized;

	/**
	 * Construct and initialize payment object.
	 *
	 * @param integer $post_id A payment post ID or null.
	 */
	public function __construct( $post_id = null ) {
		$this->id   = $post_id;
		$this->date = new DateTime();
		$this->meta = array();

		$this->set_total_amount( new TaxedMoney() );
	}

	/**
	 * Get the ID of this payment.
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set the ID of this payment.
	 *
	 * @param int $id Unique ID.
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

	/**
	 * Get payment date.
	 *
	 * @return DateTime
	 */
	public function get_date() {
		return $this->date;
	}

	/**
	 * Set payment date.
	 *
	 * @param DateTime $date Date.
	 */
	public function set_date( $date ) {
		$this->date = $date;
	}

	/**
	 * Get start date.
	 *
	 * @return DateTime
	 */
	public function get_start_date() {
		return $this->start_date;
	}

	/**
	 * Set start date.
	 *
	 * @param DateTime $start_date Start date.
	 */
	public function set_start_date( $start_date ) {
		$this->start_date = $start_date;
	}

	/**
	 * Get end date.
	 *
	 * @return DateTime
	 */
	public function get_end_date() {
		return $this->end_date;
	}

	/**
	 * Set end date.
	 *
	 * @param DateTime $end_date End date.
	 */
	public function set_end_date( $end_date ) {
		$this->end_date = $end_date;
	}

	/**
	 * Get the source identifier of this payment.
	 *
	 * @return string
	 */
	public function get_source() {
		return $this->source;
	}

	/**
	 * Set the source of this payment.
	 *
	 * @param string|null $source Source.
	 */
	public function set_source( $source ) {
		$this->source = $source;
	}

	/**
	 * Get the source ID of this payment.
	 *
	 * @return string|null
	 */
	public function get_source_id() {
		return $this->source_id;
	}

	/**
	 * Set the source ID of this payment.
	 *
	 * @param string|int $source_id Source ID.
	 */
	public function set_source_id( $source_id ) {
		$this->source_id = $source_id;
	}

	/**
	 * Get the config ID of this payment.
	 *
	 * @return int|null
	 */
	public function get_config_id() {
		return $this->config_id;
	}

	/**
	 * Set the config ID of this payment.
	 *
	 * @param int|null $config_id Config ID.
	 */
	public function set_config_id( $config_id ) {
		$this->config_id = $config_id;
	}

	/**
	 * Get the source text of this payment.
	 *
	 * @return string
	 */
	public function get_source_text() {
		$text = $this->get_source() . '<br />' . $this->get_source_id();

		$text = apply_filters( 'pronamic_payment_source_text_' . $this->get_source(), $text, $this );
		$text = apply_filters( 'pronamic_payment_source_text', $text, $this );

		return $text;
	}

	/**
	 * Get customer.
	 *
	 * @return Customer|null
	 */
	public function get_customer() {
		return $this->customer;
	}

	/**
	 * Set customer.
	 *
	 * @param Customer|null $customer Contact.
	 */
	public function set_customer( $customer ) {
		$this->customer = $customer;
	}

	/**
	 * Get billing address.
	 *
	 * @return Address|null
	 */
	public function get_billing_address() {
		return $this->billing_address;
	}

	/**
	 * Set billing address.
	 *
	 * @param Address|null $billing_address Billing address.
	 */
	public function set_billing_address( $billing_address ) {
		$this->billing_address = $billing_address;
	}

	/**
	 * Get shipping address.
	 *
	 * @return Address|null
	 */
	public function get_shipping_address() {
		return $this->shipping_address;
	}

	/**
	 * Set shipping address.
	 *
	 * @param Address|null $shipping_address Shipping address.
	 */
	public function set_shipping_address( $shipping_address ) {
		$this->shipping_address = $shipping_address;
	}

	/**
	 * Get payment lines.
	 *
	 * @return PaymentLines|null
	 */
	public function get_lines() {
		return $this->lines;
	}

	/**
	 * Set payment lines.
	 *
	 * @param PaymentLines|null $lines Payment lines.
	 */
	public function set_lines( PaymentLines $lines = null ) {
		$this->lines = $lines;
	}

	/**
	 * Get the order ID of this payment.
	 *
	 * @return string|null
	 */
	public function get_order_id() {
		return $this->order_id;
	}

	/**
	 * Get total amount.
	 *
	 * @return TaxedMoney
	 */
	public function get_total_amount() {
		return $this->total_amount;
	}

	/**
	 * Set total amount.
	 *
	 * @param TaxedMoney $total_amount Total amount.
	 */
	public function set_total_amount( TaxedMoney $total_amount ) {
		$this->total_amount = $total_amount;
	}

	/**
	 * Get the shipping amount.
	 *
	 * @return Money|null
	 */
	public function get_shipping_amount() {
		return $this->shipping_amount;
	}

	/**
	 * Set the shipping amount.
	 *
	 * @param Money|null $shipping_amount Money object.
	 */
	public function set_shipping_amount( Money $shipping_amount ) {
		$this->shipping_amount = $shipping_amount;
	}

	/**
	 * Get the payment method.
	 *
	 * @todo Constant?
	 *
	 * @return string|null
	 */
	public function get_method() {
		return $this->method;
	}

	/**
	 * Get the payment issuer.
	 *
	 * @return string|null
	 */
	public function get_issuer() {
		return $this->issuer;
	}

	/**
	 * Get the payment description.
	 *
	 * @return string|null
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Get the meta value of this specified meta key.
	 *
	 * @param string $key Meta key.
	 * @return mixed
	 */
	public function get_meta( $key ) {
		$key = '_pronamic_payment_' . $key;

		return get_post_meta( $this->id, $key, true );
	}

	/**
	 * Set meta data.
	 *
	 * @param  string $key   A meta key.
	 * @param  mixed  $value A meta value.
	 *
	 * @return boolean        True on successful update, false on failure.
	 */
	public function set_meta( $key, $value ) {
		$key = '_pronamic_payment_' . $key;

		if ( $value instanceof \DateTime ) {
			$value = $value->format( 'Y-m-d H:i:s' );
		}

		if ( empty( $value ) ) {
			return delete_post_meta( $this->id, $key );
		}

		$result = update_post_meta( $this->id, $key, $value );

		return ( false !== $result );
	}

	/**
	 * Get source description.
	 *
	 * @return string
	 */
	public function get_source_description() {
		$description = $this->source;

		$description = apply_filters( 'pronamic_payment_source_description', $description, $this );
		$description = apply_filters( 'pronamic_payment_source_description_' . $this->source, $description, $this );

		return $description;
	}

	/**
	 * Get the source link for this payment.
	 *
	 * @return string|null
	 */
	public function get_source_link() {
		$url = null;

		$url = apply_filters( 'pronamic_payment_source_url', $url, $this );
		$url = apply_filters( 'pronamic_payment_source_url_' . $this->source, $url, $this );

		return $url;
	}

	/**
	 * Get provider link for this payment.
	 *
	 * @return string|null
	 */
	public function get_provider_link() {
		$url = null;

		$config_id  = get_post_meta( $this->id, '_pronamic_payment_config_id', true );
		$gateway_id = get_post_meta( $config_id, '_pronamic_gateway_id', true );

		$url = apply_filters( 'pronamic_payment_provider_url', $url, $this );
		$url = apply_filters( 'pronamic_payment_provider_url_' . $gateway_id, $url, $this );

		return $url;
	}

	/**
	 * Get subscription.
	 *
	 * @return Subscription|false
	 */
	public function get_subscription() {
		if ( is_object( $this->subscription ) ) {
			return $this->subscription;
		}

		if ( empty( $this->subscription_id ) ) {
			return false;
		}

		$this->subscription = new Subscription( $this->subscription_id );

		return $this->subscription;
	}

	/**
	 * Format string
	 *
	 * @link https://github.com/woocommerce/woocommerce/blob/v2.2.3/includes/abstracts/abstract-wc-email.php#L187-L195
	 *
	 * @param string $string The string to format.
	 * @return string
	 */
	public function format_string( $string ) {
		// Replacements definition.
		$replacements = array(
			'{order_id}'   => $this->get_order_id(),
			'{payment_id}' => $this->get_id(),
		);

		// Find and replace.
		$string = str_replace(
			array_keys( $replacements ),
			array_values( $replacements ),
			$string,
			$count
		);

		// Make sure there is an dynamic part in the order ID.
		// @link https://secure.ogone.com/ncol/param_cookbook.asp.
		if ( 0 === $count ) {
			$string .= $this->get_id();
		}

		return $string;
	}

	/**
	 * Set consumer name.
	 *
	 * @param string|null $name Name.
	 */
	public function set_consumer_name( $name ) {
		$this->consumer_name = $name;
	}

	/**
	 * Set consumer account number.
	 *
	 * @param string|null $account_number Account number.
	 */
	public function set_consumer_account_number( $account_number ) {
		$this->consumer_account_number = $account_number;
	}

	/**
	 * Set consumer IBAN.
	 *
	 * @param string|null $iban IBAN.
	 */
	public function set_consumer_iban( $iban ) {
		$this->consumer_iban = $iban;
	}

	/**
	 * Set consumer BIC.
	 *
	 * @param string|null $bic BIC.
	 */
	public function set_consumer_bic( $bic ) {
		$this->consumer_bic = $bic;
	}

	/**
	 * Set consumer city.
	 *
	 * @param string|null $city City.
	 */
	public function set_consumer_city( $city ) {
		$this->consumer_city = $city;
	}

	/**
	 * Get payment email.
	 *
	 * @return string|null
	 */
	public function get_email() {
		return $this->email;
	}

	/**
	 * Get Google Analytics client ID.
	 *
	 * @return string|null
	 */
	public function get_analytics_client_id() {
		return $this->analytics_client_id;
	}

	/**
	 * Get entrance code.
	 *
	 * @return string|null
	 */
	public function get_entrance_code() {
		return $this->entrance_code;
	}

	/**
	 * Set the credit card to use for this payment.
	 *
	 * @param CreditCard|null $credit_card Credit Card.
	 */
	public function set_credit_card( $credit_card ) {
		$this->credit_card = $credit_card;
	}

	/**
	 * Get the credit card to use for this payment.
	 *
	 * @return CreditCard|null
	 */
	public function get_credit_card() {
		return $this->credit_card;
	}

	/**
	 * Get reucrring.
	 *
	 * @return bool|null
	 */
	public function get_recurring() {
		return $this->recurring;
	}

	/**
	 * Set version.
	 *
	 * @param string|null $version Version.
	 */
	public function set_version( $version ) {
		$this->version = $version;
	}

	/**
	 * Get version.
	 *
	 * @return string|null
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Set mode.
	 *
	 * @param string|null $mode Mode.
	 *
	 * @throws InvalidArgumentException Throws invalid argument exception when mode is not a string or not one of the mode constants.
	 */
	public function set_mode( $mode ) {
		if ( ! is_string( $mode ) ) {
			throw new InvalidArgumentException( 'Mode must be a string.' );
		}

		if ( ! in_array( $mode, array( Gateway::MODE_TEST, Gateway::MODE_LIVE ), true ) ) {
			throw new InvalidArgumentException( 'Invalid mode.' );
		}

		$this->mode = $mode;
	}

	/**
	 * Get mode.
	 *
	 * @return string|null
	 */
	public function get_mode() {
		return $this->mode;
	}

	/**
	 * Is anonymized?
	 *
	 * @return bool
	 */
	public function is_anonymized() {
		return ( true === $this->anonymized );
	}

	/**
	 * Set anonymized.
	 *
	 * @param bool|null $anonymized Anonymized.
	 */
	public function set_anonymized( $anonymized ) {
		$this->anonymized = $anonymized;
	}

	/**
	 * Create payment from object.
	 *
	 * @param mixed       $json JSON.
	 * @param PaymentInfo $info Payment info.
	 * @return Payment
	 * @throws InvalidArgumentException Throws invalid argument exception when JSON is not an object.
	 */
	public static function from_json( $json, $info = null ) {
		if ( ! is_object( $json ) ) {
			throw new InvalidArgumentException( 'JSON value must be an object.' );
		}

		if ( ! $info instanceof self ) {
			throw new InvalidArgumentException( 'Info must be an object.' );
		}

		if ( isset( $json->id ) ) {
			$info->set_id( $json->id );
		}

		if ( isset( $json->status ) ) {
			$info->set_status( $json->status );
		}

		if ( isset( $json->total_amount ) ) {
			$info->set_total_amount( TaxedMoneyJsonTransformer::from_json( $json->total_amount ) );
		}

		if ( isset( $json->shipping_amount ) ) {
			$info->set_shipping_amount( MoneyJsonTransformer::from_json( $json->shipping_amount ) );
		}

		if ( isset( $json->customer ) ) {
			$info->set_customer( Customer::from_json( $json->customer ) );
		}

		if ( isset( $json->billing_address ) ) {
			$info->set_billing_address( Address::from_json( $json->billing_address ) );
		}

		if ( isset( $json->shipping_address ) ) {
			$info->set_shipping_address( Address::from_json( $json->shipping_address ) );
		}

		if ( isset( $json->lines ) ) {
			$info->set_lines( PaymentLines::from_json( $json->lines ) );
		}

		if ( isset( $json->mode ) ) {
			$info->set_mode( $json->mode );
		}

		if ( isset( $json->anonymized ) ) {
			$info->set_anonymized( $json->anonymized );
		}

		return $info;
	}

	/**
	 * Get JSON.
	 *
	 * @return object
	 */
	public function get_json() {
		$object = (object) array();

		if ( null !== $this->get_id() ) {
			$object->id = $this->get_id();
		}

		if ( null !== $this->get_status() ) {
			$object->status = $this->get_status();
		}

		$object->total_amount = TaxedMoneyJsonTransformer::to_json( $this->get_total_amount() );

		if ( null !== $this->get_shipping_amount() ) {
			$object->shipping_amount = MoneyJsonTransformer::to_json( $this->get_shipping_amount() );
		}

		if ( null !== $this->get_customer() ) {
			$object->customer = $this->get_customer()->get_json();
		}

		if ( null !== $this->get_billing_address() ) {
			$object->billing_address = $this->get_billing_address()->get_json();
		}

		if ( null !== $this->get_shipping_address() ) {
			$object->shipping_address = $this->get_shipping_address()->get_json();
		}

		if ( null !== $this->get_lines() ) {
			$object->lines = $this->get_lines()->get_json();
		}

		if ( null !== $this->get_mode() ) {
			$object->mode = $this->get_mode();
		}

		if ( $this->is_anonymized() ) {
			$object->anonymized = $this->is_anonymized();
		}

		return $object;
	}
}
