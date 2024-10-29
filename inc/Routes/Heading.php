<?php
/**
 * Heading Route.
 *
 * This route is responsible for Heading
 * endpoint.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Routes;

use AiPlusBlockEditor\Core\AI;
use AiPlusBlockEditor\Abstracts\Route;
use AiPlusBlockEditor\Interfaces\Router;

/**
 * Heading class.
 */
class Heading extends Route implements Router {
	/**
	 * Get, Post, Put, Patch, Delete.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $method = 'POST';

	/**
	 * WP REST Endpoint e.g. /wp-json/ai-plus-block-editor/v1/heading.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $endpoint = '/heading';

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

		//Bail out, if it does NOT exists.
		if ( empty( $this->args['content'] ?? '' ) ) {
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
		$ai      = new AI();
		$article = $this->args['content'];

		return rest_ensure_response(
			$ai->get_ai_response(
				[
					'content' => "Generate an interesting headline for the following article: $article",
				]
			)
		);
	}
}
