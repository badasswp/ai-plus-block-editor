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
	 * Provider name.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	protected static $name;

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
	 * Get Provider Response.
	 *
	 * @since 1.8.0
	 *
	 * @param string $response Success response.
	 * @param string $payload  JSON Payload.
	 *
	 * @return string
	 */
	protected function get_provider_response( $response, $payload ): string {
		/**
		 * Fire on successful Provider call.
		 *
		 * This provides a way to fire events on
		 * successful AI Provider calls.
		 *
		 * @since 1.8.0
		 *
		 * @param string $response Success response.
		 * @param string $payload  JSON Payload.
		 * @param string $provider Provider class.
		 *
		 * @return void
		 */
		do_action( 'apbe_ai_provider_success_call', $response, $payload, static::$name );

		/**
		 * Filter API response.
		 *
		 * This provides a way to filter the LLM
		 * API response.
		 *
		 * @since 1.8.0
		 *
		 * @param string $response Success response.
		 * @param string $payload  JSON Payload.
		 * @param string $provider Provider class.
		 *
		 * @return string
		 */
		return apply_filters( 'apbe_ai_provider_response', $response, $payload, static::$name );
	}

	/**
	 * Get JSON Error.
	 *
	 * @since 1.8.0
	 *
	 * @param string  $message Error Message.
	 * @param mixed[] $body    JSON payload.
	 *
	 * @return \WP_Error
	 */
	protected function get_json_error( $message, $body = [] ) {
		// Get Payload.
		$payload = wp_json_encode( $body );

		// Get Provider name.
		$provider = static::$name;

		// Add error logging capability.
		error_log(
			wp_json_encode(
				[
					'message'  => $message,
					'payload'  => $payload,
					'provider' => $provider,
				]
			)
		);

		/**
		 * Fire on failed Provider call.
		 *
		 * This provides a way to fire events on
		 * failed AI Provider calls.
		 *
		 * @since 1.8.0
		 *
		 * @param string $message  Error message.
		 * @param string $payload  JSON Payload.
		 * @param string $provider Provider class.
		 *
		 * @return void
		 */
		do_action( 'apbe_ai_provider_fail_call', $message, $payload, $provider );

		return new \WP_Error(
			'ai-plus-block-editor-json-error',
			$message,
			[ 'status' => 500 ]
		);
	}
}
