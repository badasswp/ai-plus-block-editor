<?php
/**
 * Ajax Class.
 *
 * This services is responsible for processing Ajax
 * calls made directly in the plugin.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Plugins;

use AiPlusBlockEditor\Abstracts\Service;

/**
 * Ajax class.
 */
class Ajax extends Service {
	/**
	 * Bind to WP.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp_ajax_badasswp_install_plugin', [ $this, 'badasswp_install_plugin' ] );
		add_action( 'wp_ajax_nopriv_badasswp_install_plugin', [ $this, 'badasswp_install_plugin' ] );
		add_action( 'wp_ajax_badasswp_activate_plugin', [ $this, 'badasswp_activate_plugin' ] );
		add_action( 'wp_ajax_nopriv_badasswp_activate_plugin', [ $this, 'badasswp_activate_plugin' ] );
		add_action( 'wp_ajax_badasswp_deactivate_plugin', [ $this, 'badasswp_deactivate_plugin' ] );
		add_action( 'wp_ajax_nopriv_badasswp_deactivate_plugin', [ $this, 'badasswp_deactivate_plugin' ] );
	}

	/**
	 * Install Plugin.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 */
	public function badasswp_install_plugin(): void {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ajax-badasswp-nonce' ) ) {
			return;
		}

		$slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		$response = Installer::install_plugin( $slug );

		wp_die( wp_json_encode( $response ) );
	}

	/**
	 * Activate Plugin.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 */
	public function badasswp_activate_plugin(): void {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ajax-badasswp-nonce' ) ) {
			return;
		}

		$response = Installer::activate_plugin( $_POST['file'] ?? '' );

		wp_die( wp_json_encode( $response ) );
	}

	/**
	 * Deactivate Plugin.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 */
	public function badasswp_deactivate_plugin(): void {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ajax-badasswp-nonce' ) ) {
			return;
		}

		$response = Installer::deactivate_plugin( $_POST['file'] ?? '' );

		wp_die( wp_json_encode( $response ) );
	}
}
