<?php
/**
 * License Manager
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

/**
 * License Manager
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class LicenseManager {
	/**
	 * Plugin.
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initalize an license manager object.
	 *
	 * @param Plugin $plugin Plugin.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions.
		add_action( 'pronamic_pay_license_check', array( $this, 'license_check_event' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Filters.
		add_filter( sprintf( 'pre_update_option_%s', 'pronamic_pay_license_key' ), array( $this, 'pre_update_option_license_key' ), 10, 2 );
	}

	/**
	 * Admin notices.
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.2.4/wp-admin/options.php#L205-L218
	 * @see https://github.com/easydigitaldownloads/Easy-Digital-Downloads/blob/2.4.2/includes/class-edd-license-handler.php#L309-L369
	 */
	public function admin_notices() {
		$data = get_transient( 'pronamic_pay_license_data' );

		if ( $data ) {
			include $this->plugin->get_plugin_dir_path() . 'admin/notice-license.php';

			delete_transient( 'pronamic_pay_license_data' );
		}
	}

	/**
	 * Pre update option 'pronamic_pay_license_key'.
	 *
	 * @param string $newvalue New value.
	 * @param string $oldvalue Old value.
	 * @return string
	 */
	public function pre_update_option_license_key( $newvalue, $oldvalue ) {
		$newvalue = trim( $newvalue );

		if ( $newvalue !== $oldvalue ) {
			delete_option( 'pronamic_pay_license_status' );

			if ( ! empty( $oldvalue ) ) {
				$this->deactivate_license( $oldvalue );
			}
		}

		delete_transient( 'pronamic_pay_license_data' );

		if ( ! empty( $newvalue ) ) {
			// Always try to activate the new license, it could be deactivated.
			$this->activate_license( $newvalue );
		}

		// Shedule daily license check.
		$time = time() + DAY_IN_SECONDS;

		wp_clear_scheduled_hook( 'pronamic_pay_license_check' );

		wp_schedule_event( $time, 'daily', 'pronamic_pay_license_check' );

		// Get and update license status.
		$this->check_license( $newvalue );

		return $newvalue;
	}

	/**
	 * License check event.
	 */
	public function license_check_event() {
		$license = get_option( 'pronamic_pay_license_key' );

		$this->check_license( $license );
	}

	/**
	 * Request license status.
	 *
	 * @param string $license License.
	 * @return string
	 */
	private function request_license_status( $license ) {
		if ( empty( $license ) ) {
			return 'invalid';
		}

		// Request.
		$args = array(
			'license' => $license,
			'name'    => 'Pronamic iDEAL',
			'url'     => home_url(),
		);

		$args = urlencode_deep( $args );

		$response = wp_remote_get(
			add_query_arg( $args, 'https://api.pronamic.eu/licenses/check/1.0/' ),
			array(
				'timeout' => 20,
			)
		);

		// On errors we give benefit of the doubt.
		if ( $response instanceof WP_Error ) {
			return 'valid';
		}

		$data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( is_object( $data ) && isset( $data->license ) ) {
			return $data->license;
		}

		return 'valid';
	}

	/**
	 * Check license.
	 *
	 * @param string|boolean $license License.
	 */
	public function check_license( $license ) {
		$status = $this->request_license_status( $license );

		update_option( 'pronamic_pay_license_status', $status );
	}

	/**
	 * Deactivate license.
	 *
	 * @param string $license License to deactivate.
	 */
	public function deactivate_license( $license ) {
		$args = array(
			'license' => $license,
			'name'    => 'Pronamic iDEAL',
			'url'     => home_url(),
		);

		$args = urlencode_deep( $args );

		$response = wp_remote_get(
			add_query_arg( $args, 'https://api.pronamic.eu/licenses/deactivate/1.0/' ),
			array(
				'timeout' => 20,
			)
		);
	}

	/**
	 * Activate license.
	 *
	 * @param string $license License to activate.
	 */
	public function activate_license( $license ) {
		// Request.
		$args = array(
			'license' => $license,
			'name'    => 'Pronamic iDEAL',
			'url'     => home_url(),
		);

		$args = urlencode_deep( $args );

		$response = wp_remote_get(
			add_query_arg( $args, 'https://api.pronamic.eu/licenses/activate/1.0/' ),
			array(
				'timeout' => 20,
			)
		);

		$data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $data ) {
			set_transient( 'pronamic_pay_license_data', $data, 30 );
		}
	}
}
