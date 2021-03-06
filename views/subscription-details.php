<?php
/**
 * Subscription info.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Util;

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! isset( $subscription ) ) {
	return;
}

/*
 * Subscription details.
 */
$details = array(
	array(
		'term'        => __( 'Description', 'pronamic_ideal' ),
		'description' => $subscription->get_description(),
	),
);

// Current phase.
$phase = $subscription->get_current_phase();

$recurrence = '—';

if ( null !== $phase ) {
	// Amount.
	$details[] = array(
		'term'        => __( 'Amount', 'pronamic_ideal' ),
		'description' => $phase->get_amount()->format_i18n(),
	);

	// Recurrence.
	if ( $phase->is_infinite() ) :
		// Infinite.
		$recurrence = Util::format_recurrences( $phase->get_interval() );

	elseif ( 1 !== $phase->get_total_periods() ) :
		// Fixed number of recurrences.
		$recurrence = sprintf(
			'%s (%s)',
			Util::format_recurrences( $phase->get_interval() ),
			Util::format_frequency( $phase->get_total_periods() )
		);

	endif;
}

// Payment method.
$method = $subscription->payment_method;

if ( ! empty( $method ) ) {
	$details[] = array(
		'term'        => __( 'Payment method', 'pronamic_ideal' ),
		'description' => PaymentMethods::get_name( $method ),
	);
}

// Recurrence.
$details[] = array(
	'term'        => __( 'Recurrence', 'pronamic_ideal' ),
	'description' => $recurrence,
);

// Expiry date.
$expiry_date = $subscription->get_expiry_date();

if ( null !== $expiry_date ) {
	$details[] = array(
		'term'        => __( 'Expiry Date', 'pronamic_ideal' ),
		'description' => $expiry_date->format_i18n(),
	);
}

?>

<h2><?php esc_html_e( 'Subscription', 'pronamic_ideal' ); ?></h2>

<dl>
	<?php foreach ( $details as $detail ) : ?>

		<?php if ( array_key_exists( 'term', $detail ) ) : ?>

			<dt><?php echo esc_html( $detail['term'] ); ?></dt>

		<?php endif; ?>

		<?php if ( array_key_exists( 'description', $detail ) ) : ?>

			<dd><?php echo esc_html( $detail['description'] ); ?></dd>

		<?php endif; ?>

	<?php endforeach; ?>
</dl>
