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
	 *
	 * @wp-hook 'admin_enqueue_scripts'
	 */
	public function register_scripts() {
		wp_enqueue_script(
			Options::get_page_slug(),
			plugins_url( 'ai-plus-block-editor/dist/app.js' ),
			[
				'wp-i18n',
				'wp-element',
				'wp-blocks',
				'wp-components',
				'wp-editor',
				'wp-hooks',
				'wp-compose',
				'wp-plugins',
				'wp-edit-post',
			],
			'1.5.0',
			false,
		);

		wp_localize_script(
			Options::get_page_slug(),
			'apbe',
			[
				'provider' => get_option( Options::get_page_option(), [] )['ai_provider'] ?? '',
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
}
