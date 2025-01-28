<?php
/**
 * OpenAI Class.
 *
 * This class is responsible for handling
 * OpenAI calls.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Providers;

use Orhanerday\OpenAi\OpenAi as ChatGPT;
use AiPlusBlockEditor\Interfaces\Provider;

class OpenAI implements Provider {
	/**
	 * Get Default Args.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]
	 */
	protected function get_default_args(): array {
		$args = [
			'model'             => 'gpt-3.5-turbo',
			'temperature'       => 1.0,
			'max_tokens'        => 4000,
			'frequency_penalty' => 0,
			'presence_penalty'  => 0,
		];

		/**
		 * Filter OpenAI default args.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed[] $args Default args.
		 * @return mixed[]
		 */
		$filtered_args = (array) apply_filters( 'apbe_open_ai_args', $args );

		return wp_parse_args( $filtered_args, $args );
	}

	/**
	 * Get AI Response.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $payload JSON Payload.
	 * @return string|\WP_Error
	 */
	public function run( $payload ) {
		$ai_keys = get_option( 'ai_plus_block_editor', [] )['open_ai_token'] ?? '';
		$payload = wp_parse_args( [ 'role' => 'user' ], $payload );

		$response = json_decode(
			( new ChatGPT( $ai_keys ) )->chat(
				wp_parse_args(
					[ 'messages' => [ $payload ] ],
					$this->get_default_args()
				)
			),
			true
		);

		return $response['choices'][0]['message']['content'] ?? '';
	}
}
