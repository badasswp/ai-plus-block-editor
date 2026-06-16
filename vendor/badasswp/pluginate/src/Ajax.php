<?php
/**
 * Ajax Class.
 *
 * This services is responsible for processing Ajax
 * calls made directly in the plugin.
 *
 * @package Pluginate
 */

namespace Pluginate;

/**
 * Ajax class.
 */
class Ajax {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp_ajax_pluginate_install_plugin', [ $this, 'install_plugin' ] );
		add_action( 'wp_ajax_nopriv_pluginate_install_plugin', [ $this, 'install_plugin' ] );
		add_action( 'wp_ajax_pluginate_activate_plugin', [ $this, 'activate_plugin' ] );
		add_action( 'wp_ajax_nopriv_pluginate_activate_plugin', [ $this, 'activate_plugin' ] );
		add_action( 'wp_ajax_pluginate_deactivate_plugin', [ $this, 'deactivate_plugin' ] );
		add_action( 'wp_ajax_nopriv_pluginate_deactivate_plugin', [ $this, 'deactivate_plugin' ] );
	}

	/**
	 * Install Plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function install_plugin(): void {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ajax-pluginate-nonce' ) ) {
			return;
		}

		$slug = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';

		$response = Installer::install_plugin( $slug );

		wp_die( wp_json_encode( $response ) );
	}

	/**
	 * Activate Plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function activate_plugin(): void {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ajax-pluginate-nonce' ) ) {
			return;
		}

		$response = Installer::activate_plugin( $_POST['file'] ?? '' );

		wp_die( wp_json_encode( $response ) );
	}

	/**
	 * Deactivate Plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function deactivate_plugin(): void {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ajax-pluginate-nonce' ) ) {
			return;
		}

		$response = Installer::deactivate_plugin( $_POST['file'] ?? '' );

		wp_die( wp_json_encode( $response ) );
	}
}
