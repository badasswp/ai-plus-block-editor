<?php
/**
 * SideBar Route.
 *
 * This route is responsible for the SideBar
 * endpoint.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Routes;

use AiPlusBlockEditor\Core\AI;
use AiPlusBlockEditor\Abstracts\Route;
use AiPlusBlockEditor\Interfaces\Router;

/**
 * SideBar class.
 */
class SideBar extends Route implements Router {
	/**
	 * Get, Post, Put, Patch, Delete.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public string $method = 'POST';

	/**
	 * WP REST Endpoint e.g. /wp-json/ai-plus-block-editor/v1/sidebar.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public string $endpoint = '/sidebar';

	/**
	 * WP_REST_Request object.
	 *
	 * @since 1.1.0
	 *
	 * @var \WP_REST_Request
	 */
	public \WP_REST_Request $request;

	/**
	 * Response Callback.
	 *
	 * @since 1.1.0
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
	 * @since 1.1.0
	 * @since 1.5.0 Add Social prompt.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	protected function get_response() {
		// Get Args.
		$prompt_text    = $this->args['text'] ?? '';
		$prompt_feature = $this->args['feature'] ?? '';
		$placeholder    = '';

		// Get Prompt.
		switch ( $prompt_feature ) {
			case 'headline':
				$placeholder = 'Generate an appropriate %s in 1 paragraph, using the following content: %s. Do not include any explanation, commentary, or alternative suggestions.';
				break;

			case 'slug':
				$placeholder = 'Generate an appropriate %s that can be found easily by search engines, using the following content: %s. Do not include any explanation, commentary, or alternative suggestions.';
				break;

			case 'keywords':
				$placeholder = 'Generate appropriate %s that are SEO friendly and separated with commas, using the following content: %s. Do not include any explanation, commentary, or alternative suggestions.';
				break;

			case 'summary':
				$placeholder = 'Generate an appropriate %s for the following content: %s. Do not include any explanation, commentary, or alternative suggestions.';
				break;

			case 'social':
				$placeholder = 'Generate appropriate %s media trending hashtags for the following content: %s. Do not include any explanation, commentary, or alternative suggestions.';
				break;
		}

		// Get Prompt.
		$prompt = sprintf( $placeholder, $prompt_feature, $prompt_text );

		/**
		 * Filter SideBar prompt.
		 *
		 * @since 1.1.0
		 *
		 * @param string $prompt         Prompt sent to AI LLM endpoint.
		 * @param string $prompt_feature Feature sent.
		 * @param string $prompt_text    Text sent.
		 *
		 * @return string
		 */
		$prompt = apply_filters( 'apbe_feature_prompt', $prompt, $prompt_feature, $prompt_text );

		return rest_ensure_response(
			$this->ai->run(
				[
					'content' => $prompt,
				]
			)
		);
	}
}
