<?php
/**
 * Router Interface.
 *
 * This interface defines a contract for routes
 * and defines common methods that derived classes
 * should implement.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Interfaces;

interface Router {
	/**
	 * Request Callback.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function request( $request );

	/**
	 * Response Callback.
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function response();

	/**
	 * Permissions callback for endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Request $request Request Object.
	 * @return bool|\WP_Error
	 */
	public function is_user_permissible( $request );
}
