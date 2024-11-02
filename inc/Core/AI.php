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
	 * AI Provider.
	 *
	 * @since 1.0.0
	 *
	 * @var Provider
	 */
	protected Provider $provider;

	/**
	 * Set Up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$ai = $this->get_provider();

		// Establish AI Provider.
		$this->provider = new $ai();
	}

	/**
	 * Get Provider.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_provider(): string {
		$ai_provider = get_option( 'ai_plus_block_editor', [] )['ai_provider'] ?? '';

		/**
		 * Filter AI Provider.
		 *
		 * @since 1.0.0
		 *
		 * @param string $ai_provider AI Provider.
		 * @return string
		 */
		return apply_filters( 'apbe_ai_provider', $ai_provider );
	}

	/**
	 * Run AI Provider.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $payload JSON Payload.
	 * @return string
	 */
	public function run( $payload ): string {
		return $this->provider->run( $payload );
	}
}
