<?php
/**
 * Customer helper
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

use Pronamic\WordPress\Pay\Core\Util as Core_Util;

/**
 * Customer helper
 *
 * @author  Remco Tolsma
 * @version 2.0.8
 * @since   2.0.8
 */
class CustomerHelper {
	/**
	 * Complement customer.
	 *
	 * @param Customer $customer Customer to complement.
	 */
	public static function complement_customer( Customer $customer ) {
		// Locale.
		if ( null === $customer->get_locale() && is_user_logged_in() ) {
			$locale = get_user_locale();

			$customer->set_locale( $locale );
		}

		// Language.
		if ( null === $customer->get_language() && null !== $customer->get_locale() ) {
			$language = substr( $customer->get_locale(), 0, 2 );

			$customer->set_language( $language );
		}

		// User Agent.
		if ( null === $customer->get_user_agent() ) {
			// User Agent (@see https://github.com/WordPress/WordPress/blob/4.9.4/wp-includes/comment.php#L1962-L1965).
			$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : null; // WPCS: input var ok.

			$customer->set_user_agent( $user_agent );
		}

		// User IP.
		if ( null === $customer->get_ip_address() ) {
			// IP (@see https://github.com/WordPress/WordPress/blob/4.9.4/wp-includes/comment.php#L1957-L1960).
			$remote_address = Core_Util::get_remote_address();

			if ( ! empty( $remote_address ) ) {
				$ip_address = sanitize_text_field( wp_unslash( $remote_address ) );

				$customer->set_ip_address( $ip_address );
			}
		}
	}
}
