<?php
/**
 * Subscriptions privacy exporters and erasers.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Subscriptions
 */

namespace Pronamic\WordPress\Pay\Subscriptions;

use Pronamic\WordPress\Pay\Core\Statuses;

/**
 * Subscriptions Privacy class.
 *
 * @author  Reüel van der Steege
 * @version 5.2.0
 * @since   5.2.0
 * @package Pronamic\WordPress\Pay\Subscriptions
 */
class SubscriptionsPrivacy {
	/**
	 * Subscriptions privacy constructor.
	 */
	public function __construct() {
		// Register exporters.
		add_action( 'pronamic_pay_privacy_register_exporters', array( $this, 'register_exporters' ) );

		// Register erasers.
		add_action( 'pronamic_pay_privacy_register_erasers', array( $this, 'register_erasers' ) );
	}

	/**
	 * Register privacy exporters.
	 *
	 * @param \Pronamic\WordPress\Pay\PrivacyManager $privacy_manager Privacy manager.
	 *
	 * @return void
	 */
	public function register_exporters( $privacy_manager ) {
		// Subscriptions export.
		$privacy_manager->add_exporter(
			'subscriptions',
			__( 'Subscriptions', 'pronamic_ideal' ),
			array( $this, 'subscriptions_export' )
		);
	}

	/**
	 * Register privacy erasers.
	 *
	 * @param \Pronamic\WordPress\Pay\PrivacyManager $privacy_manager Privacy manager.
	 *
	 * @return void
	 */
	public function register_erasers( $privacy_manager ) {
		// Subscriptions anonymizer.
		$privacy_manager->add_eraser(
			'subscriptions',
			__( 'Subscriptions', 'pronamic_ideal' ),
			array( $this, 'subscriptions_anonymizer' )
		);
	}

	/**
	 * Subscriptions exporter.
	 *
	 * @param string $email_address Email address.
	 * @param int    $page          Page.
	 *
	 * @return array
	 */
	public function subscriptions_export( $email_address, $page = 1 ) {
		// Subscriptions data store.
		$data_store = pronamic_pay_plugin()->subscriptions_data_store;

		// Privacy manager.
		$privacy_manager = pronamic_pay_plugin()->privacy_manager;

		// Get subscriptions.
		// @todo use paging.
		$subscriptions = get_pronamic_subscriptions_by_meta(
			$data_store->meta_key_prefix . 'email',
			$email_address
		);

		// Get registered meta keys for export.
		$meta_keys = wp_list_filter(
			$data_store->get_registered_meta(),
			array(
				'privacy_export' => true,
			)
		);

		$items = array();

		// Loop subscriptions.
		foreach ( $subscriptions as $subscription ) {
			$export_data = array();

			$subscription_meta = get_post_meta( $subscription->get_id() );

			// Get subscription meta.
			foreach ( $meta_keys as $meta_key => $meta_options ) {
				$meta_key = $data_store->meta_key_prefix . $meta_key;

				if ( ! array_key_exists( $meta_key, $subscription_meta ) ) {
					continue;
				}

				// Add export value.
				$export_data[] = $privacy_manager->export_meta( $meta_key, $meta_options, $subscription_meta );
			}

			// Add item to export data.
			if ( ! empty( $export_data ) ) {
				$items[] = array(
					'group_id'    => 'pronamic-pay-subscriptions',
					'group_label' => __( 'Subscriptions', 'pronamic_ideal' ),
					'item_id'     => 'pronamic-pay-subscription-' . $subscription->get_id(),
					'data'        => $export_data,
				);
			}
		}

		$done = true;

		// Return export data.
		return array(
			'data' => $items,
			'done' => $done,
		);
	}

	/**
	 * Subscriptions anonymizer.
	 *
	 * @param string $email_address Email address.
	 * @param int    $page          Page.
	 *
	 * @return array
	 */
	public function subscriptions_anonymizer( $email_address, $page = 1 ) {
		// Subscriptions data store.
		$data_store = pronamic_pay_plugin()->subscriptions_data_store;

		// Privacy manager.
		$privacy_manager = pronamic_pay_plugin()->privacy_manager;

		// Return values.
		$items_removed  = false;
		$items_retained = false;
		$messages       = array();
		$done           = false;

		// Get subscriptions.
		// @todo use paging.
		$subscriptions = get_pronamic_subscriptions_by_meta(
			$data_store->meta_key_prefix . 'email',
			$email_address
		);

		// Get registered meta keys for erasure.
		$meta_keys = wp_list_filter(
			$data_store->get_registered_meta(),
			array(
				'privacy_erasure' => null,
			),
			'NOT'
		);

		// Loop subscriptions.
		foreach ( $subscriptions as $subscription ) {
			$subscription_id = $subscription->get_id();

			$subscription_meta = get_post_meta( $subscription_id );

			$subscription_status = null;

			// Get subscription meta.
			foreach ( $meta_keys as $meta_key => $meta_options ) {
				if ( 'status' === $meta_key ) {
					$subscription_status = $subscription_meta[ $data_store->meta_key_prefix . $meta_key ];
				}

				$meta_key = $data_store->meta_key_prefix . $meta_key;

				if ( ! array_key_exists( $meta_key, $subscription_meta ) ) {
					continue;
				}

				$privacy_manager->erase_meta( $subscription_id, $meta_key, $meta_options['privacy_erasure'] );
			}

			// Add subscription note.
			$subscription->add_note( __( 'Subscription anonymized for personal data erasure request.', 'pronamic_ideal' ) );

			// Add message.
			$messages[] = sprintf( __( 'Subscription ID %s anonymized.', 'pronamic_ideal' ), $subscription_id );

			// Cancel subscription if neccesary.
			if ( isset( $subscription_status ) && ! in_array( $subscription_status, array( Statuses::COMPLETED, Statuses::CANCELLED ), true ) ) {
				$subscription->set_status( Statuses::CANCELLED );
				$subscription->save();
			}

			$items_removed = true;
		}

		$done = true;

		// Return results.
		return array(
			'items_removed'  => $items_removed,
			'items_retained' => $items_retained,
			'messages'       => $messages,
			'done'           => $done,
		);
	}
}