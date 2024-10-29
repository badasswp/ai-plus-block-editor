<?php
/**
 * Admin Service.
 *
 * Generate custom Admin options page along with
 * plugin options to be used.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Services;

use AiPlusBlockEditor\Admin\Form;
use AiPlusBlockEditor\Admin\Options;
use AiPlusBlockEditor\Abstracts\Service;
use AiPlusBlockEditor\Interfaces\Kernel;

class Admin extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_init', [ $this, 'register_options_init' ] );
		add_action( 'admin_menu', [ $this, 'register_options_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_options_styles' ] );
	}

	/**
	 * Register Options Menu.
	 *
	 * This controls the menu display for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_options_menu(): void {
		add_menu_page(
			Options::get_page_title(),
			Options::get_page_title(),
			'manage_options',
			Options::get_page_slug(),
			[ $this, 'register_options_page' ],
			'dashicons-awards',
			100
		);
	}

	/**
	 * Register Options Page.
	 *
	 * This controls the display of the menu page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_options_page(): void {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		vprintf(
			'<section class="wrap">
				<h1>%s</h1>
				<p>%s</p>
				%s
			</section>',
			array_map(
				'__',
				( new Form( Options::$form ) )->get_options()
			)
		);
	}

	/**
	 * Register Settings.
	 *
	 * This method handles all save actions for the fields
	 * on the Plugin's settings page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_options_init(): void {
		// Form data.
		$form_fields = [];
		$form_values = [];

		// Button & WP Nonces.
		$form_button_name     = Options::get_submit_button_name();
		$form_settings_nonce  = Options::get_submit_nonce_name();
		$form_settings_action = Options::get_submit_nonce_action();

		// Bail out early, if save button or nonce is not set.
		if ( ! isset( $_POST[ $form_button_name ] ) || ! isset( $_POST[ $form_settings_nonce ] ) ) {
			return;
		}

		// Bail out early, if nonce is not verified.
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $form_settings_nonce ] ) ), $form_settings_action ) ) {
			return;
		}

		// Get Form Fields.
		foreach ( Options::get_fields() as $field ) {
			$form_fields = array_merge(
				array_keys( $field['controls'] ?? [] ),
				$form_fields
			);
		}

		// Get Form Values.
		foreach ( $form_fields as $field ) {
			$form_values[] = sanitize_text_field( wp_unslash( $_POST[ $field ] ?? '' ) );
		}

		// Update Plugin options.
		update_option( Options::get_page_option(), array_combine( $form_fields, $form_values ) );
	}

	/**
	 * Register Styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_options_styles(): void {
		wp_enqueue_style(
			Options::get_page_slug(),
			plugins_url( 'ai-plus-block-editor/styles.css' ),
			[],
			true,
			'all'
		);
	}
}
