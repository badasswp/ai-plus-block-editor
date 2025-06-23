<?php
/**
 * Tone Route.
 *
 * This route is responsible for Tone
 * endpoint.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Routes;

use AiPlusBlockEditor\Core\AI;
use AiPlusBlockEditor\Abstracts\Route;
use AiPlusBlockEditor\Interfaces\Router;

/**
 * Tone class.
 */
class Tone extends Route implements Router {
	/**
	 * Get, Post, Put, Patch, Delete.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $method = 'POST';

	/**
	 * WP REST Endpoint e.g. /wp-json/ai-plus-block-editor/v1/tone.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $endpoint = '/tone';

	/**
	 * WP_REST_Request object.
	 *
	 * @since 1.0.0
	 *
	 * @var \WP_REST_Request
	 */
	public \WP_REST_Request $request;

	/**
	 * Response Callback.
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function response() {
		$this->args = $this->request->get_json_params();

		// Bail out, if it does NOT exists.
		if ( empty( $this->args['text'] ?? '' ) ) {
			return $this->get_400_response(
				sprintf(
					'API Request does not contain a text. Post ID: %s',
					$this->args['id'] ?? ''
				)
			);
		}

		return $this->get_response();
	}

	/**
	 * Get Response for valid WP REST request.
	 *
	 * This method obtains the REST response using
	 * the custom prompt.
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	protected function get_response() {
		// Get Args.
		$prompt_tone = $this->args['tone'] ?? '';
		$prompt_text = $this->args['text'] ?? '';
		$placeholder = 'Using a %s tone, generate a text I can use to substitute the following text: %s. Do not include any explanation, commentary, or alternative suggestions.';

		// Get Prompt.
		$prompt = sprintf( $placeholder, $prompt_tone, $prompt_text );

		/**
		 * Filter Tone prompt.
		 *
		 * @since 1.0.0
		 *
		 * @param string $prompt      Prompt sent to AI LLM endpoint.
		 * @param string $prompt_tone Tone sent.
		 * @param string $prompt_text Text sent.
		 *
		 * @return string
		 */
		$prompt = apply_filters( 'apbe_tone_prompt', $prompt, $prompt_tone, $prompt_text );

		return rest_ensure_response(
			$this->ai->run(
				[
					'content' => $prompt,
				]
			)
		);
	}
}
