<?php
/**
 * Boot Service.
 *
 * Handle all setup logic before plugin is
 * fully capable.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Services;

use AiPlusBlockEditor\Admin\Options;
use AiPlusBlockEditor\Abstracts\Service;
use AiPlusBlockEditor\Interfaces\Kernel;
use AiPlusBlockEditor\Abstracts\Provider;

class Boot extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'register_translation' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'register_scripts' ] );
	}

	/**
	 * Register Scripts.
	 *
	 * @since 1.0.0
	 * @since 1.5.0 Use dependencies and version from generated Webpack assets file.
	 *
	 * @wp-hook 'enqueue_block_editor_assets'
	 */
	public function register_scripts() {
		$assets = $this->get_assets( plugin_dir_path( __FILE__ ) . '/../../dist/app.asset.php' );

		wp_enqueue_script(
			Options::get_page_slug(),
			plugins_url( 'ai-plus-block-editor/dist/app.js' ),
			$assets['dependencies'],
			$assets['version'],
			false,
		);

		wp_localize_script(
			Options::get_page_slug(),
			'apbe',
			[
				'provider'  => get_option( Options::get_page_option(), [] )['ai_provider'] ?? '',
				'providers' => $this->get_providers(),
			]
		);

		wp_set_script_translations(
			Options::get_page_slug(),
			Options::get_page_slug(),
			plugin_dir_path( __FILE__ ) . '../../languages'
		);
	}

	/**
	 * Add Plugin text translation.
	 *
	 * @since 1.0.0
	 *
	 * @wp-hook 'init'
	 */
	public function register_translation() {
		load_plugin_textdomain(
			Options::get_page_slug(),
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/../../languages'
		);
	}

	/**
	 * Get Asset dependencies.
	 *
	 * @since 1.5.0
	 *
	 * @param string $path Path to webpack generated PHP asset file.
	 * @return array
	 */
	protected function get_assets( string $path ): array {
		$assets = [
			'version'      => strval( time() ),
			'dependencies' => [],
		];

		if ( ! file_exists( $path ) ) {
			return $assets;
		}

		// phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
		$assets = require_once $path;

		return $assets;
	}

	/**
	 * Get Providers.
	 *
	 * Return processed array with label and value
	 * key-pairs for block editor use.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	protected function get_providers(): array {
		$providers = Provider::get_providers();

		return array_map(
			function ( $key, $value ) {
				return [
					'label' => $value,
					'value' => $key,
				];
			},
			array_keys( $providers ),
			array_values( $providers ),
		);
	}
}
