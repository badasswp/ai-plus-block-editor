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
					'Post Article does not contain a body: %s',
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
		$tone    = $this->args['tone'] ?? '';
		$article = $this->args['content'] ?? '';

		return rest_ensure_response(
			$ai->run(
				[
					'content' => "Please generate a $tone tone all in one paragraph using the following text: $article",
				]
			)
		);
	}
}
