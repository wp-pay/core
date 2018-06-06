<?php
/**
 * Config provider
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Core
 */

namespace Pronamic\WordPress\Pay\Core;

/**
 * Title: Config provider
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 2.0.0
 * @since 1.0.0
 */
class ConfigProvider {
	/**
	 * Factories.
	 *
	 * @var array
	 */
	private static $factories = array();

	/**
	 * Register a factory class.
	 *
	 * @param string $name       Name of the factory.
	 * @param string $class_name Factory class name.
	 */
	public static function register( $name, $class_name ) {
		self::$factories[ $name ] = $class_name;
	}

	/**
	 * Get config from the specified factory with the specified post ID.
	 *
	 * @param string $name    Name of a factory.
	 * @param int    $post_id Configuration post ID.
	 * @return Gateway|null
	 */
	public static function get_config( $name, $post_id ) {
		$config = null;

		if ( isset( self::$factories[ $name ] ) ) {
			$class_name = self::$factories[ $name ];

			if ( class_exists( $class_name ) ) {
				$factory = new $class_name();

				if ( $factory instanceof GatewayConfigFactory ) {
					$config = $factory->get_config( $post_id );

					$config->id = $post_id;
				}
			}
		}

		return $config;
	}
}