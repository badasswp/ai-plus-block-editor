<?php
/**
 * Grok Class.
 *
 * This class is responsible for handling
 * Grok calls.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Providers;

use AiPlusBlockEditor\Interfaces\Provider;
use AiPlusBlockEditor\Admin\Options;

class Grok implements Provider {
	/**
	 * Get Default Args.
	 *
	 * @since 1.7.0
	 *
	 * @return mixed[]
	 */
	protected function get_default_args(): array {
		$args = [
			'model'  => 'grok-4',
			'stream' => false,
		];

		/**
		 * Filter Grok default args.
		 *
		 * @since 1.7.0
		 *
		 * @param mixed[] $args Default args.
		 * @return mixed[]
		 */
		$filtered_args = (array) apply_filters( 'apbe_grok_args', $args );

		return wp_parse_args( $filtered_args, $args );
	}

	/**
	 * Get API URL.
	 *
	 * This method returns the Grok API URL
	 * endpoint. It can be filtered using the
	 * 'apbe_grok_api_url' filter.
	 *
	 * @since 1.7.0
	 *
	 * @return string
	 */
	protected function get_api_url(): string {
		$url = esc_url( 'https://api.x.ai/v1/chat/completions' );

		/**
		 * Filter Grok API URL.
		 *
		 * @since 1.7.0
		 *
		 * @param string $url Grok API URL.
		 * @return string
		 */
		return apply_filters( 'apbe_grok_api_url', $url );
	}

	/**
	 * Get AI Response.
	 *
	 * @since 1.7.0
	 *
	 * @param mixed[] $payload JSON Payload.
	 * @return string|\WP_Error
	 */
	public function run( $payload ) {
		$api_key = get_option( Options::get_page_option(), [] )['grok_token'] ?? '';

		if ( empty( $api_key ) ) {
			return $this->get_json_error(
				__( 'Missing Grok API key.', 'ai-plus-block-editor' )
			);
		}

		// Default prompt if not passed.
		$prompt_text = $payload['content'] ?? '';

		// Validate prompt text.
		if ( ! is_string( $prompt_text ) || empty( $prompt_text ) ) {
			return $this->get_json_error(
				__( 'Invalid prompt text.', 'ai-plus-block-editor' )
			);
		}

		/**
		 * Filter Grok System Prompt.
		 *
		 * @since 1.7.0
		 *
		 * @param string $prompt Grok System prompt.
		 * @return string
		 */
		$system_prompt = apply_filters( 'apbe_grok_system_prompt', 'You are Grok, a highly intelligent, helpful AI assistant.' );

		// Grok API expects a specific body structure.
		$body = wp_parse_args(
			$this->get_default_args(),
			[
				'messages' => [
					[
						'role'    => 'system',
						'content' => $system_prompt,
					],
					[
						'role'    => 'user',
						'content' => $prompt_text,
					],
				],
			]
		);

		$response = wp_remote_post(
			$this->get_api_url(),
			[
				'headers' => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $api_key,
				],
				'body'    => wp_json_encode( $body, JSON_UNESCAPED_UNICODE ),
				'timeout' => 20,
			]
		);

		if ( is_wp_error( $response ) ) {
			return $this->get_json_error( $response->get_error_message() );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		// Notify user, if JSON yields null.
		if ( empty( $data ) || ! isset( $data['choices'][0]['message']['content'] ) ) {
			return $this->get_json_error(
				$data['error']['message'] ?? __( 'Unexpected Grok API response.', 'ai-plus-block-editor' )
			);
		}

		return $data['choices'][0]['message']['content'] ?? '';
	}

	/**
	 * Get JSON Error.
	 *
	 * @since 1.7.0
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
