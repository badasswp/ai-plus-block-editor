<?php
/**
 * Installer Service.
 *
 * This service contains all the necessary methods to
 * install, activate and deactivate plugins directly from
 * the plugin options page.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Plugins;

/**
 * Installer class.
 */
class Installer {
	/**
	 * Install Plugin.
	 *
	 * @since 1.10.0
	 *
	 * @param string $slug Plugin Slug.
	 * @return array
	 */
	public static function install_plugin( $slug ): array {
		// Required due to plugins_api() call.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		// Get Download link
		$plugin = plugins_api(
			'plugin_information',
			[
				'slug' => $slug,
			]
		);

		if ( is_wp_error( $plugin ) ) {
			return [
				'status'  => 0,
				'message' => 'Plugin information retrieval failed!',
			];
		}

		// Get Download URL
		$tmp = download_url( $plugin->download_link );

		if ( is_wp_error( $tmp ) ) {
			return [
				'status'  => 0,
				'message' => 'Plugin URL retrieval failed!',
			];
		}

		$install = ( new \Plugin_Upgrader() )->install( $tmp );
		unlink( $tmp );

		if ( is_wp_error( $install ) ) {
			return [
				'status'  => 0,
				'message' => 'Plugin Installation failed!',
			];
		}

		// Set Response
		return [
			'status'  => 1,
			'message' => 'Successful Plugin Install!',
		];
	}

	/**
	 * Activate Plugin.
	 *
	 * @since 1.10.0
	 *
	 * @param string $slug Plugin File.
	 * @return array
	 */
	public static function activate_plugin( $slug ): array {
		// Required due to plugins_api() call.
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$response = activate_plugin( $slug );

		if ( is_wp_error( $response ) ) {
			return [
				'status'  => 0,
				'message' => 'Error, activating plugin!',
			];
		}

		return [
			'status'  => 1,
			'message' => 'Successful Plugin Activation!',
		];
	}

	/**
	 * Deactivate Plugin.
	 *
	 * @since 1.10.0
	 *
	 * @param string $slug Plugin File.
	 * @return array
	 */
	public static function deactivate_plugin( $slug ): array {
		// Required due to plugins_api() call.
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$response = deactivate_plugins( $slug );

		if ( is_wp_error( $response ) ) {
			return [
				'status'  => 0,
				'message' => 'Error, deactivating plugin!',
			];
		}

		return [
			'status'  => 1,
			'message' => 'Successful Plugin Deactivation!',
		];
	}

	/**
	 * Get Plugin Status - Install, Activate, Deactive.
	 *
	 * @since 1.10.0
	 *
	 * @param string $slug Plugin name.
	 * @return string
	 */
	public static function get_plugin_status( $slug, $default = 'Install Plugin' ): string {
		if ( is_plugin_active( $slug . '/' . $slug . '.php' ) ) {
			return esc_html__( 'Installed', 'ai-plus-block-editor' );
		}

		if ( file_exists( WP_PLUGIN_DIR . '/' . $slug ) ) {
			return esc_html__( 'Activate', 'ai-plus-block-editor' );
		}

		return esc_html__( $default, 'ai-plus-block-editor' );
	}
}
