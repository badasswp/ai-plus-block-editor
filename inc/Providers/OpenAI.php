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
use AiPlusBlockEditor\Admin\Options;
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
		$ai_keys = get_option( Options::get_page_option(), [] )['open_ai_token'] ?? '';
		$payload = wp_parse_args( [ 'role' => 'user' ], $payload );

		try {
			$response = ( new ChatGPT( $ai_keys ) )->chat(
				wp_parse_args(
					[ 'messages' => [ $payload ] ],
					$this->get_default_args()
				)
			);

			$response = json_decode( $response, true );

			// Deal gracefully, with API error.
			if ( isset( $response['error'] ) ) {
				$error_msg = $response['error']['message'] ?? '';
				error_log( $error_msg );

				return $this->get_json_error( $error_msg );
			}
		} catch ( \Exception $e ) {
			$error_msg = sprintf(
				'Error: OpenAI API call failed... %s',
				$e->getMessage()
			);
			error_log( $error_msg );

			return $this->get_json_error( $error_msg );
		}

		if ( is_null( $response ) ) {
			$error_msg = 'Error: OpenAI API call returned malformed JSON.';
			error_log( $error_msg );

			return $this->get_json_error( $error_msg );
		}

		return $response['choices'][0]['message']['content'] ?? '';
	}

	/**
	 * Get JSON Error.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Error Message.
	 * @return \WP_Error
	 */
	protected function get_json_error( $message ) {
		return new \WP_Error(
			'ai-plus-block-editor-json-error',
			$message,
			[ 'status' => 500 ]
		);
	}
}
