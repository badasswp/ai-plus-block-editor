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
			'ai-plus-block-editor',
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
			'1.3.0',
			false,
		);

		wp_set_script_translations(
			'ai-plus-block-editor',
			'ai-plus-block-editor',
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
			'ai-plus-block-editor',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/../../languages'
		);
	}
}
