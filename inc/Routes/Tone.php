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

		return new \WP_REST_Response( $this->get_response() );
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
		$ai = new AI();

		// Get Args.
		$tone   = $this->args['tone'] ?? '';
		$text   = $this->args['text'] ?? '';
		$prompt = 'Using a % tone, generate a text I can use to substitute the following text: %s';

		// Get Prompt.
		$prompt = sprintf( $prompt, $tone, $text );

		/**
		 * Filter Tone prompt.
		 *
		 * @since 1.0.0
		 *
		 * @param string $prompt Prompt sent to AI LLM endpoint.
		 * @param string $tone   Tone sent.
		 * @param string $text   Text sent.
		 *
		 * @return string
		 */
		$prompt = apply_filters( 'apbe_tone_prompt', $prompt, $tone, $text );

		return rest_ensure_response(
			$ai->run(
				[
					'content' => $prompt,
				]
			)
		);
	}
}
