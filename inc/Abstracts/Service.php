<?php
/**
 * Service Abstraction.
 *
 * This abstraction defines the base logic from which all
 * Service classes are derived.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Abstracts;

use AiPlusBlockEditor\Core\Converter;
use AiPlusBlockEditor\Interfaces\Kernel;

abstract class Service implements Kernel {
	/**
	 * Service classes.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed[]
	 */
	public static array $services;

	/**
	 * Register Singleton.
	 *
	 * This defines the generic method used by
	 * Service classes.
	 *
	 * @since 1.0.0
	 *
	 * @return static
	 */
	public static function get_instance() {
		$class = get_called_class();

		if ( ! isset( static::$services[ $class ] ) ) {
			static::$services[ $class ] = new static();
		}

		return static::$services[ $class ];
	}

	/**
	 * Register to WP.
	 *
	 * Bind concrete logic to WP here.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function register(): void;
}
