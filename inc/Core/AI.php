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
	public Provider $provider;

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
				$ai_provider = new OpenAI();
				break;
		}

		/**
		 * Filter AI Provider.
		 *
		 * @since 1.0.0
		 *
		 * @param Provider $ai_provider AI Provider.
		 * @return Provider
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
		$this->provider = $this->get_provider();

		return $this->provider->run( $payload );
	}
}
