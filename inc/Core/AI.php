<?php
/**
 * AI Class.
 *
 * This class is responsible for handling
 * the AI calls.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Core;

use AiPlusBlockEditor\Providers\OpenAI;
use AiPlusBlockEditor\Interfaces\Provider;

class AI implements Provider {
	/**
	 * Get Provider.
	 *
	 * This method gets the instance of the AI Provider
	 * selected by the user.
	 *
	 * @since 1.0.0
	 *
	 * @return Provider
	 */
	protected function get_provider(): Provider {
		$ai_provider = get_option( 'ai_plus_block_editor', [] )['ai_provider'] ?? '';

		switch ( $ai_provider ) {
			case 'OpenAI':
				$ai_provider = OpenAI::class;
				break;
		}

		return $this->get_instance( new $ai_provider() );
	}

	/**
	 * Get Provider Instance.
	 *
	 * This method presents us a work-around with
	 * correctly mocking and writing tests for the
	 * `get_provider` method.
	 *
	 * @since TBD
	 *
	 * @param Provider $provider
	 * @return Provider
	 */
	protected function get_instance( Provider $provider ): Provider {
		/**
		 * Filter AI Provider.
		 *
		 * @since 1.0.0
		 *
		 * @param Provider $provider AI Provider.
		 * @return Provider
		 */
		return apply_filters( 'apbe_ai_provider', $provider );
	}

	/**
	 * Run AI Provider.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $payload JSON Payload.
	 * @return string|\WP_Error
	 */
	public function run( $payload ) {
		try {
			return $this->get_provider()->run( $payload );
		} catch ( \Exception $e ) {
			return new \WP_Error(
				'apbe-run-error',
				sprintf( 'Server Error: %s', $e->getMessage() ),
				[ 'status' => 500 ]
			);
		}
	}
}
