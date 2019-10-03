<?php
/**
 * Site health
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay\Admin;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Class SiteHealth
 *
 * @link https://make.wordpress.org/core/2019/04/25/site-health-check-in-5-2/
 *
 * @author  Reüel van der Steege
 * @version 2.2.4
 * @since   2.2.4
 */
class AdminHealth {
	/**
	 * Plugin.
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Site health constructor.
	 *
	 * @param Plugin $plugin Plugin.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Filters.
		add_filter( 'debug_information', array( $this, 'debug_information' ) );
		add_filter( 'site_status_tests', array( $this, 'status_tests' ) );
	}

	/**
	 * Debug information.
	 *
	 * @param array $debug_information Debug information.
	 *
	 * @return array
	 */
	public function debug_information( $debug_information ) {
		$fields = array();

		// License key.
		$fields['pronamic_pay_license_key'] = array(
			'label'   => __( 'Support license key', 'pronamic_ideal' ),
			'value'   => esc_html( get_option( 'pronamic_pay_license_key', __( 'No license key found', 'pronamic_ideal' ) ) ),
			'private' => true,
		);

		// License status.
		$fields['pronamic_pay_license_status'] = array(
			'label' => __( 'License status', 'pronamic_ideal' ),
			'value' => esc_html( $this->plugin->license_manager->get_formatted_license_status() ),
		);

		// Next scheduled license check.
		$fields['pronamic_pay_next_license_check'] = array(
			'label' => __( 'Next scheduled license check', 'pronamic_ideal' ),
			'value' => esc_html( $this->plugin->license_manager->get_formatted_next_license_check() ),
		);

		// Time.
		$fields['pronamic_pay_time'] = array(
			'label' => __( 'Time', 'pronamic_ideal' ),
			'value' => esc_html( date( __( 'Y/m/d g:i:s A', 'pronamic_ideal' ) ) ),
		);

		// OpenSSL version.
		$openssl_version = __( 'Not available', 'pronamic_ideal' );

		if ( defined( 'OPENSSL_VERSION_TEXT' ) ) {
			$openssl_version = OPENSSL_VERSION_TEXT;
		}

		$fields['pronamic_pay_openssl_version'] = array(
			'label' => __( 'OpenSSL version', 'pronamic_ideal' ),
			'value' => esc_html( $openssl_version ),
		);

		// Add debug information section.
		$debug_information['pronamic-pay'] = array(
			'label'  => __( 'Pronamic Pay', 'pronamic_ideal' ),
			'fields' => $fields,
		);

		return $debug_information;
	}

	/**
	 * Status tests.
	 *
	 * @param array $status_tests Status tests.
	 *
	 * @return array
	 */
	public function status_tests( $status_tests ) {
		// Test valid license.
		$status_tests['direct']['pronamic_pay_valid_license'] = array(
			'label' => __( 'Pronamic Pay support license key test' ),
			'test'  => array( $this, 'test_valid_license' ),
		);

		// Test minimum required PHP version.
		$status_tests['direct']['pronamic_pay_php_version'] = array(
			'label' => __( 'Pronamic Pay PHP version test' ),
			'test'  => array( $this, 'test_php_version' ),
		);

		// Test minimum required MySQL version.
		$status_tests['direct']['pronamic_pay_mysql_version'] = array(
			'label' => __( 'Pronamic Pay MySQL version test' ),
			'test'  => array( $this, 'test_mysql_version' ),
		);

		// Test minimum required WordPress version.
		$status_tests['direct']['pronamic_pay_wordpress_version'] = array(
			'label' => __( 'Pronamic Pay WordPress version test' ),
			'test'  => array( $this, 'test_wordpress_version' ),
		);

		// Test OpenSSL version.
		$status_tests['direct']['pronamic_pay_openssl_version'] = array(
			'label' => __( 'Pronamic Pay OpenSSL version test' ),
			'test'  => array( $this, 'test_openssl_version' ),
		);

		// Test memory limit.
		$status_tests['direct']['pronamic_pay_memory_limit'] = array(
			'label' => __( 'Pronamic Pay memory limit test' ),
			'test'  => array( $this, 'test_memory_limit' ),
		);

		// Test character set.
		$status_tests['direct']['pronamic_pay_character_set'] = array(
			'label' => __( 'Pronamic Pay UTF-8 character set test' ),
			'test'  => array( $this, 'test_character_set' ),
		);

		// Test hashing algorithms.
		$status_tests['direct']['pronamic_pay_hashing_algorithms'] = array(
			'label' => __( 'Pronamic Pay hashing algorithms test' ),
			'test'  => array( $this, 'test_hashing_algorithms' ),
		);

		return $status_tests;
	}

	/**
	 * Test if configuration exists.
	 */
	public function test_valid_license() {
		// Good.
		$result = array(
			'test'        => 'pronamic_pay_valid_license',
			'label'       => __( 'Pronamic Pay license key is valid', 'pronamic_ideal' ),
			'description' => sprintf(
				'<p>%s</p>',
				__( 'A valid license is required for technical support and continued plugin updates.', 'pronamic_ideal' )
			),
			'badge'       => array(
				'label' => __( 'Security' ),
				'color' => 'blue',
			),
			'status'      => 'good',
			'actions'     => '',
		);

		// Recommendation.
		if ( 'valid' !== get_option( 'pronamic_pay_license_status' ) ) {
			$result['status'] = 'recommended';
			$result['label']  = __( 'No valid license key for Pronamic Pay', 'pronamic_ideal' );

			$result['actions'] = '<p>';

			if ( '' === get_option( 'pronamic_pay_license_key' ) ) {
				$result['actions'] .= sprintf(
					'<a href="%s">%s</a> - ',
					esc_url( 'https://www.pronamic.eu/plugins/pronamic-ideal/' ),
					__( 'Purchase license' )
				);
			}

			$result['actions'] .= sprintf(
				'<a href="%s">%s</a> - ',
				add_query_arg( 'page', 'pronamic_pay_settings', get_admin_url( null, 'admin.php' ) ),
				__( 'License settings' )
			);

			$result['actions'] .= sprintf(
				'<a href="%s">%s</a>',
				esc_url( 'https://www.pronamic.eu/account/' ),
				__( 'Check existing license' )
			);

			$result['actions'] .= '</p>';
		}

		return $result;
	}

	/**
	 * Test PHP version.
	 *
	 * @return array
	 */
	public function test_php_version() {
		// Good.
		$result = array(
			'test'        => 'pronamic_pay_php_version',
			'label'       => sprintf(
				/* translators: %s: PHP version number */
				__( 'PHP version is supported by Pronamic Pay (%s)', 'pronamic_ideal' ),
				phpversion()
			),
			'description' => sprintf( '<p>%s</p>', __( 'Pronamic Pay requires at least PHP 5.6.20.', 'pronamic_ideal' ) ),
			'badge'       => array(
				'label' => __( 'Payments', 'pronamic_ideal' ),
				'color' => 'blue',
			),
			'status'      => 'good',
			'actions'     => '',
		);

		// Recommendation.
		if ( version_compare( phpversion(), '5.6.20', '<' ) ) {
			$result['status'] = 'recommended';
			$result['label']  = __( 'Pronamic Pay requires at least PHP 5.6.20', 'pronamic_ideal' );
		}

		return $result;
	}

	/**
	 * Test MySQL version.
	 *
	 * @return array
	 */
	public function test_mysql_version() {
		global $wpdb;

		// Good.
		$result = array(
			'test'        => 'pronamic_pay_mysql_version',
			'label'       => sprintf(
				/* translators: %s: MySQL version number */
				__( 'MySQL version is supported by Pronamic Pay (%s)', 'pronamic_ideal' ),
				$wpdb->db_version()
			),
			'description' => sprintf( '<p>%s</p>', __( 'Pronamic Pay requires at least MySQL 5.', 'pronamic_ideal' ) ),
			'badge'       => array(
				'label' => __( 'Payments', 'pronamic_ideal' ),
				'color' => 'blue',
			),
			'status'      => 'good',
			'actions'     => '',
		);

		// Recommendation.
		if ( version_compare( $wpdb->db_version(), '5', '<' ) ) {
			$result['status'] = 'recommended';
			$result['label']  = __( 'Pronamic Pay requires at least MySQL 5', 'pronamic_ideal' );
		}

		return $result;
	}

	/**
	 * Test WordPress version.
	 *
	 * @return array
	 */
	public function test_wordpress_version() {
		// Good.
		$result = array(
			'test'        => 'pronamic_pay_wordpress_version',
			'label'       => sprintf(
				/* translators: %s: WordPress version number */
				__( 'WordPress version is supported by Pronamic Pay (%s)', 'pronamic_ideal' ),
				get_bloginfo( 'version' )
			),
			'description' => sprintf( '<p>%s</p>', __( 'Pronamic Pay requires at least WordPress 4.7.', 'pronamic_ideal' ) ),
			'badge'       => array(
				'label' => __( 'Payments', 'pronamic_ideal' ),
				'color' => 'blue',
			),
			'status'      => 'good',
			'actions'     => '',
		);

		// Recommendation.
		if ( version_compare( get_bloginfo( 'version' ), '4.7', '<' ) ) {
			$result['status'] = 'recommended';
			$result['label']  = __( 'Pronamic Pay requires at least WordPress 4.7', 'pronamic_ideal' );
		}

		return $result;
	}

	/**
	 * Test WordPress memory limit.
	 *
	 * @return array
	 */
	public function test_memory_limit() {
		$memory = pronamic_pay_let_to_num( WP_MEMORY_LIMIT );

		// Good.
		$result = array(
			'test'        => 'pronamic_pay_memory_limit',
			'label'       => sprintf(
				/* translators: %s: WordPress memory limit */
				__( 'WordPress memory limit is sufficient (%s)', 'pronamic_ideal' ),
				size_format( $memory )
			),
			'description' => sprintf( '<p>%s</p>', __( 'Pronamic Pay recommends setting the WordPress memory limit to at least 64 MB.', 'pronamic_ideal' ) ),
			'badge'       => array(
				'label' => __( 'Payments', 'pronamic_ideal' ),
				'color' => 'blue',
			),
			'status'      => 'good',
			'actions'     => '',
		);

		// Recommendation.
		if ( $memory < 67108864 ) {
			$result['status'] = 'recommended';

			$result['label'] = sprintf(
				/* translators: %s: WordPress memory limit */
				__( 'Increase WordPress memory limit (%s) to at least 64 MB', 'pronamic_ideal' ),
				size_format( $memory )
			);
		}

		return $result;
	}

	/**
	 * Test UTF-8 character set.
	 *
	 * @return array
	 */
	public function test_character_set() {
		// Good.
		$result = array(
			'test'        => 'pronamic_pay_character_set',
			'label'       => __( 'Character encoding is set to UTF-8', 'pronamic_ideal' ),
			'description' => sprintf( '<p>%s</p>', __( 'Pronamic Pay recommends to use the UTF-8 character encoding for payments.', 'pronamic_ideal' ) ),
			'badge'       => array(
				'label' => __( 'Payments', 'pronamic_ideal' ),
				'color' => 'blue',
			),
			'status'      => 'good',
			'actions'     => '',
		);

		// Recommendation.
		if ( 0 !== strcasecmp( get_bloginfo( 'charset' ), 'UTF-8' ) ) {
			$result['status'] = 'recommended';

			$result['label'] = __( 'Character encoding is not set to UTF-8', 'pronamic_ideal' );
		}

		return $result;
	}

	/**
	 * Test OpenSSL version.
	 *
	 * @return array
	 */
	public function test_openssl_version() {
		$openssl_version_text = '';

		if ( defined( 'OPENSSL_VERSION_TEXT' ) ) {
			$openssl_version_text = \str_replace( 'OpenSSL ', '', OPENSSL_VERSION_TEXT );
		}

		// Good.
		$result = array(
			'test'        => 'pronamic_pay_openssl_version',
			'label'       => sprintf(
				/* translators: %s: OpenSSL version number */
				__( 'OpenSSL version meets the recommendation for Pronamic Pay (%s)', 'pronamic_ideal' ),
				$openssl_version_text
			),
			'description' => sprintf(
				'<p>%s</p>',
				__( 'Pronamic Pay advises OpenSSL 0.9.8 or higher, earlier versions can result in issues with payments.', 'pronamic_ideal' )
			),
			'badge'       => array(
				'label' => __( 'Payments', 'pronamic_ideal' ),
				'color' => 'blue',
			),
			'status'      => 'good',
			'actions'     => '',
		);

		// Recommendation.
		$openssl_version_number = null;

		if ( defined( 'OPENSSL_VERSION_NUMBER' ) ) {
			$openssl_version_number = OPENSSL_VERSION_NUMBER;
		}

		if ( version_compare( $openssl_version_number, 0x000908000, '<' ) ) {
			$result['status'] = 'recommended';

			$result['label'] = sprintf(
				/* translators: %s: OpenSSL version text */
				__( 'OpenSSL version does not meet the recommended version for Pronamic Pay (%s)', 'pronamic_ideal' ),
				$openssl_version_text
			);
		}

		return $result;
	}

	/**
	 * Test registered hashing algorithms.
	 *
	 * @return array
	 */
	public function test_hashing_algorithms() {
		// Good.
		$result = array(
			'test'        => 'pronamic_pay_hashing_algorithms',
			'label'       => __( 'SHA1 hashing algorithm is available', 'pronamic_ideal' ),
			'description' => sprintf( '<p>%s</p>', __( 'Payment gateways often use the SHA1 hashing algorithm, therefore Pronamic Pay advises to enable this hashing algorithm.', 'pronamic_ideal' ) ),
			'badge'       => array(
				'label' => __( 'Payments', 'pronamic_ideal' ),
				'color' => 'blue',
			),
			'status'      => 'good',
			'actions'     => '',
		);

		// Recommendation.
		$algorithms = hash_algos();

		if ( ! in_array( 'sha1', $algorithms, true ) ) {
			$result['status'] = 'recommended';

			$result['label'] = __( 'SHA1 hashing algorithm is not available for Pronamic Pay', 'pronamic_ideal' );
		}

		return $result;
	}
}