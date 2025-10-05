<?php
/**
 * Provider abstraction.
 *
 * This abstract class defines the base class methods
 * for provider concrete classes.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Abstracts;

use AiPlusBlockEditor\Interfaces\Provider as ProviderInterface;

/**
 * Provider class.
 */
abstract class Provider implements ProviderInterface {
	/**
	 * Get Default Args.
	 *
	 * Define arguments that will most likely
	 * be passed to the request body.
	 *
	 * @since 1.8.0
	 *
	 * @return mixed[]
	 */
	abstract protected function get_default_args();

	/**
	 * Run AI Prompt.
	 *
	 * Perform API call and get a response we
	 * can proceed with.
	 *
	 * @since 1.8.0
	 *
	 * @param mixed[] $payload JSON Payload.
	 * @return string|\WP_Error
	 */
	abstract public function run( $payload );

	/**
	 * Get JSON Error.
	 *
	 * @since 1.8.0
	 *
	 * @param string $message Error Message.
	 * @return \WP_Error
	 */
	protected function get_json_error( $message ) {
		/**
		 * Fire on failed Provider call.
		 *
		 * This provides a way to fire events on
		 * failed AI Provider calls.
		 *
		 * @since 1.8.0
		 *
		 * @param string $message Error message.
		 * @param string $class   Provider class.
		 *
		 * @return void
		 */
		do_action( 'apbe_failed_provider_call', $message, static::class );

		return new \WP_Error(
			'ai-plus-block-editor-json-error',
			$message,
			[ 'status' => 500 ]
		);
	}
}
