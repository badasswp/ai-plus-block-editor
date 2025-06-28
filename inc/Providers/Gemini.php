<?php
/**
 * Gemini Class.
 *
 * This class is responsible for handling
 * Gemini calls.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Providers;

use AiPlusBlockEditor\Interfaces\Provider;
use AiPlusBlockEditor\Admin\Options;

class Gemini implements Provider {
	/**
	 * Get Default Args.
	 *
	 * @since 1.5.0
	 *
	 * @return mixed[]
	 */
	protected function get_default_args(): array {
		$args = [
			'model'           => 'gemini-2.0-flash',
			'temperature'     => 1.0,
			'maxOutputTokens' => 256,
			'topK'            => 40,
			'topP'            => 0.95,
			'stopSequences'   => [ "\n\n" ],
		];

		/**
		 * Filter Gemini default args.
		 *
		 * @since 1.5.0
		 *
		 * @param mixed[] $args Default args.
		 * @return mixed[]
		 */
		$filtered_args = (array) apply_filters( 'apbe_gemini_args', $args );

		return wp_parse_args( $filtered_args, $args );
	}

	/**
	 * Get API URL.
	 *
	 * This method returns the Gemini API URL
	 * endpoint. It can be filtered using the
	 * 'apbe_gemini_api_url' filter.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	protected function get_api_url(): string {
		$url = esc_url(
			sprintf(
				'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
				$this->get_default_args()['model'] ?? ''
			)
		);

		/**
		 * Filter Gemini API URL.
		 *
		 * @since 1.5.0
		 *
		 * @param string $url Gemini API URL.
		 * @return string
		 */
		return apply_filters( 'apbe_gemini_api_url', $url );
	}

	/**
	 * Get AI Response.
	 *
	 * @since 1.5.0
	 *
	 * @param mixed[] $payload JSON Payload.
	 * @return string|\WP_Error
	 */
	public function run( $payload ) {
		$api_key = get_option( Options::get_page_option(), [] )['google_gemini_token'] ?? '';

		if ( empty( $api_key ) ) {
			return $this->get_json_error(
				__( 'Missing Gemini API key.', 'ai-plus-block-editor' )
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

		$args = $this->get_default_args();
		unset( $args['model'] );

		// Gemini API expects a specific body structure.
		$body = [
			'contents'         => [
				[
					'role'  => 'user',
					'parts' => [
						[ 'text' => $prompt_text ],
					],
				],
			],
			'generationConfig' => $args,
		];

		$response = wp_remote_post(
			add_query_arg( 'key', $api_key, $this->get_api_url() ),
			[
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'body'    => wp_json_encode( $body, JSON_UNESCAPED_UNICODE ),
				'timeout' => 20,
			]
		);

		if ( is_wp_error( $response ) ) {
			return $this->get_json_error( $response->get_error_message() );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $data['error'] ) ) {
			return $this->get_json_error( $data['error']['message'] ?? 'Unknown Gemini API error.' );
		}

		return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
	}

	/**
	 * Get JSON Error.
	 *
	 * @since 1.5.0
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
