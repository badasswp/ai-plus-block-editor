<?php
/**
 * Switcher Route.
 *
 * This route is responsible for updating the
 * AI Provider.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Routes;

use AiPlusBlockEditor\Core\AI;
use AiPlusBlockEditor\Admin\Options;
use AiPlusBlockEditor\Abstracts\Route;
use AiPlusBlockEditor\Interfaces\Router;

/**
 * Switcher class.
 */
class Switcher extends Route implements Router {
	/**
	 * Get, Post, Put, Patch, Delete.
	 *
	 * @since 1.5.0
	 *
	 * @var string
	 */
	public string $method = 'POST';

	/**
	 * WP REST Endpoint e.g. /wp-json/ai-plus-block-editor/v1/switcher.
	 *
	 * @since 1.5.0
	 *
	 * @var string
	 */
	public string $endpoint = '/switcher';

	/**
	 * WP_REST_Request object.
	 *
	 * @since 1.5.0
	 *
	 * @var \WP_REST_Request
	 */
	public \WP_REST_Request $request;

	/**
	 * Response Callback.
	 *
	 * @since 1.5.0
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function response() {
		$args = $this->request->get_json_params();

		if ( empty( $args['provider'] ?? '' ) ) {
			return $this->get_400_response(
				sprintf(
					'API Request does not contain a valid AI provider. Provider: %s',
					$args['provider'] ?? ''
				)
			);
		}

		update_option(
			Options::get_page_option(),
			wp_parse_args(
				[
					'ai_provider' => sanitize_text_field( $args['provider'] ),
				],
				get_option( Options::get_page_option(), [] )
			)
		);

		return rest_ensure_response(
			[
				'message'  => sprintf(
					'AI Provider switched successfully to %s',
					$args['provider']
				),
				'provider' => get_option( Options::get_page_option(), [] )['provider'] ?? '',
			]
		);
	}
}
