<?php
/**
 * Provider Interface.
 *
 * This interface defines a contract for
 * AI providers.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Interfaces;

interface Provider {
	/**
	 * Run AI Prompt.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $payload JSON Payload.
	 * @return string
	 */
	public function run( $payload ): string;
}
