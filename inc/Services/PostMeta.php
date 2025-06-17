<?php
/**
 * Post Meta Service.
 *
 * This service is responsible for enabling meta data
 * capturing within the Block Editor.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Services;

use AiPlusBlockEditor\Abstracts\Service;
use AiPlusBlockEditor\Interfaces\Kernel;

class PostMeta extends Service implements Kernel {
	/**
	 * Post META.
	 *
	 * @since 1.1.0
	 *
	 * @var string[]
	 */
	public array $meta;

	/**
	 * Set up.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->meta = [
			'apbe_headline',
			'apbe_seo_keywords',
			'apbe_slug',
			'apbe_summary',
			'apbe_social',
		];
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'register_post_meta' ] );
	}

	/**
	 * Register Meta.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register_post_meta(): void {
		/**
		 * Filter list of meta values.
		 *
		 * @since 1.1.0
		 *
		 * @param string[] $meta Meta values.
		 * @return string[]
		 */
		$this->meta = (array) apply_filters( 'apbe_post_meta', $this->meta );

		/**
		 * Register Meta for all Post types.
		 *
		 * @since 1.1.0
		 *
		 * @var string $meta
		 */
		foreach ( $this->meta as $meta ) {
			register_post_meta(
				'',
				$meta,
				[
					'single'            => true,
					'show_in_rest'      => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				]
			);
		}
	}
}
