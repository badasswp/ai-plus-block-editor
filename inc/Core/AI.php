<?php
/**
 * AI Class.
 *
 * This services is responsible for generating
 * AI questions for each quiz.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Core;

use Orhanerday\OpenAi\OpenAi;

class AI {
	/**
	 * Open AI.
	 *
	 * @since 1.0.0
	 *
	 * @var OpenAi
	 */
	public OpenAi $ai;

	/**
	 * Setup AI Client.
	 *
	 * @since 1.0.0
	 *
	 * @return OpenAi
	 */
	public function __construct() {
		$ai_token = get_option( 'ai_plus_block_editor', [] )['open_ai_token'] ?? '';
		$this->ai = new OpenAi( $ai_token );
	}

	/**
	 * Get Default Args.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]
	 */
	public function get_default_args(): array {
		$args = [
			'model'             => 'gpt-3.5-turbo',
			'temperature'       => 1.0,
			'max_tokens'        => 4000,
			'frequency_penalty' => 0,
			'presence_penalty'  => 0,
		];

		$filtered_args = (array) apply_filters( 'apbe_init_args', $args );

		return wp_parse_args( $filtered_args, $args );
	}

	/**
	 * Get AI Response.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $payload JSON Payload.
	 * @return string
	 */
	public function get_ai_response( $payload ) {
		$payload = wp_parse_args( [ 'role' => 'user' ], $payload );

		$response = json_decode(
			$this->ai->chat(
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
