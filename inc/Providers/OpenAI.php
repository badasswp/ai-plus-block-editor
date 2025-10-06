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
use AiPlusBlockEditor\Abstracts\Provider;
use AiPlusBlockEditor\Interfaces\Provider as ProviderInterface;

class OpenAI extends Provider implements ProviderInterface {
	/**
	 * Provider name.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	protected static $name = 'OpenAI';

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
	 * Get Client.
	 *
	 * This method initializes the OpenAI client
	 * with the API keys stored in the options.
	 *
	 * @since 1.5.0
	 *
	 * @return ChatGPT
	 */
	protected function get_client(): ChatGPT {
		$api_key = get_option( Options::get_page_option(), [] )['open_ai_token'] ?? '';
		return new ChatGPT( $api_key );
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
		$api_key = get_option( Options::get_page_option(), [] )['open_ai_token'] ?? '';

		if ( empty( $api_key ) ) {
			return $this->get_json_error(
				__( 'Missing OpenAI API key.', 'ai-plus-block-editor' )
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

		// ChatGPT expects a specific body structure.
		$body = wp_parse_args(
			[
				'messages' => [
					[
						'role'    => 'user',
						'content' => $prompt_text,
					]
				],
			],
			$this->get_default_args()
		);

		try {
			$response = $this->get_client()->chat( $body );

			if ( is_wp_error( $response ) ) {
				return $this->get_json_error( $response->get_error_message(), $body );
			}

			// Get JSON response.
			$data = json_decode( $response, true );

			// Deal gracefully, with API error.
			if ( empty( $data ) || isset( $data['error'] ) ) {
				return $this->get_json_error(
					$data['error']['message'] ?? __( 'Unexpected OpenAI API response.', 'ai-plus-block-editor' ),
					$body
				);
			}
		} catch ( \Exception $e ) {
			return $this->get_json_error(
				sprintf(
					'Error: OpenAI API call failed... %s',
					$e->getMessage()
				),
				$body
			);
		}

		if ( is_null( $data ) ) {
			return $this->get_json_error(
				__( 'Error: OpenAI API call returned malformed JSON.', 'ai-plus-block-editor' ),
				$body
			);
		}

		// Get API response.
		$response = $data['choices'][0]['message']['content'] ?? '';

		// Get Payload.
		$payload = wp_json_encode( $body );

		// Get Provider name.
		$provider = static::$name;

		/**
		 * Fire on successful Provider call.
		 *
		 * This provides a way to fire events on
		 * successful AI Provider calls.
		 *
		 * @since 1.8.0
		 *
		 * @param string $response Success response.
		 * @param string $payload  JSON Payload.
		 * @param string $provider Provider class.
		 *
		 * @return void
		 */
		do_action( 'apbe_ai_provider_success_call', $response, $payload, $provider );

		/**
		 * Filter API response.
		 *
		 * This provides a way to filter the LLM
		 * API response.
		 *
		 * @since 1.8.0
		 *
		 * @param string $response Success response.
		 * @param string $payload  JSON Payload.
		 * @param string $provider Provider class.
		 *
		 * @return string
		 */
		return apply_filters( 'apbe_ai_provider_response', $response, $payload, $provider );
	}
}
