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

class Gemini implements Provider {
	/**
	 * Gemini API URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $gemini_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

	/**
	 * Get AI Response.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $payload JSON Payload.
	 * @return string|\WP_Error
	 */
	public function run( $payload ) {
		$options  = get_option( 'ai_plus_block_editor', [] );
		$api_key  = $options['google_gemini'] ?? '';

		if ( empty( $api_key ) ) {
			return $this->get_json_error( 'Missing Gemini API key.' );
		}

		// Default prompt if not passed
		$prompt_text = $payload['text'] ?? 'Say something smart';

		// Gemini-compatible payload
		$body = [
			'contents' => [
				[
					'parts' => [
						[ 'text' => $prompt_text ],
					],
				],
			],
		];

		$response = wp_remote_post(
			add_query_arg( 'key', $api_key, $this->gemini_url ),
			[
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'body'    => wp_json_encode( $body ),
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
