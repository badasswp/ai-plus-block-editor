<?php
/**
 * Route abstraction.
 *
 * This abstract class defines a foundation for creating
 * route classes which act as WP REST end points.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Abstracts;

use AiPlusBlockEditor\Interfaces\Router;
use AiPlusBlockEditor\Interfaces\Provider;
use AiPlusBlockEditor\Core\AI;

/**
 * Route class.
 */
abstract class Route implements Router {
	/**
	 * AI.
	 *
	 * @var AI
	 */
	public AI $ai;

	/**
	 * JSON args.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public array $args;

	/**
	 * Get, Post, Put, Patch, Delete.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $method;

	/**
	 * WP REST Endpoint e.g. /wp-json/ai-plus-block-editor/v1/ai.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $endpoint;

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
	 * This is solely for preparing the response array
	 * before it is passed via the callback.
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	abstract public function response();

	/**
	 * Request Callback.
	 *
	 * Also known as the REST Callback. This method is
	 * responsible for getting the $request data and passing it along
	 * to the response method.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function request( $request ) {
		$this->ai      = $this->get_ai_client( new AI() );
		$this->request = $request;

		return $this->response();
	}

	/**
	 * Get AI Client.
	 *
	 * @since 1.1.2
	 *
	 * @param Provider $provider
	 * @return Provider
	 */
	protected function get_ai_client( Provider $provider ): Provider {
		return $provider;
	}

	/**
	 * Register REST Route.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_route(): void {
		register_rest_route(
			'ai-plus-block-editor/v1',
			$this->endpoint,
			[
				'methods'             => $this->method,
				'callback'            => [ $this, 'request' ],
				'permission_callback' => [ $this, 'is_user_permissible' ],
			]
		);
	}

	/**
	 * Get 400 Response.
	 *
	 * This method returns a 400 response for Bad
	 * requests submitted.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Error Msg.
	 * @return \WP_Error
	 */
	public function get_400_response( $message ): \WP_Error {
		$args = $this->request->get_json_params();

		return new \WP_Error(
			'ai-plus-block-editor-bad-request',
			sprintf(
				'Fatal Error: Bad Request, %s',
				$message
			),
			[
				'status'  => 400,
				'request' => $args,
			]
		);
	}

	/**
	 * Permissions callback for endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request Request Object.
	 * @return bool|\WP_Error
	 */
	public function is_user_permissible( $request ) {
		$http_error = rest_authorization_required_code();

		if ( ! current_user_can( 'administrator' ) ) {
			return new \WP_Error(
				'apbe-rest-forbidden',
				sprintf( 'Invalid User. Error: %s', $http_error ),
				[ 'status' => $http_error ]
			);
		}

		if ( ! wp_verify_nonce( $request->get_header( 'X-WP-Nonce' ), 'wp_rest' ) ) {
			return new \WP_Error(
				'apbe-rest-forbidden',
				sprintf( 'Invalid Nonce. Error: %s', $http_error ),
				[ 'status' => $http_error ]
			);
		}

		return true;
	}
}
