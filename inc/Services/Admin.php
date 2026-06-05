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

use Pluginate\Admin as Pluginate;

class Admin extends Service implements Kernel {
	/**
	 * Pluginate instance.
	 *
	 * @since 1.10.0
	 *
	 * @var Pluginate
	 */
	public Pluginate $pluginate;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->pluginate = new Pluginate( 'ai-plus-block-editor' );
	}

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
		add_action( 'admin_enqueue_scripts', [ $this, 'register_options_scripts' ] );
		add_action( 'admin_init', [ $this->pluginate, 'init' ] );
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
			'data:image/svg+xml;base64,' . base64_encode(
				'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
					<path d="M17.8 2l-.9.3c-.1 0-3.6 1-5.2 2.1C10 5.5 9.3 6.5 8.9 7.1c-.6.9-1.7 4.7-1.7 6.3l-.9 2.3c-.2.4 0 .8.4 1 .1 0 .2.1.3.1.3 0 .6-.2.7-.5l.6-1.5c.3 0 .7-.1 1.2-.2.7-.1 1.4-.3 2.2-.5.8-.2 1.6-.5 2.4-.8.7-.3 1.4-.7 1.9-1.2s.8-1.2 1-1.9c.2-.7.3-1.6.4-2.4.1-.8.1-1.7.2-2.5 0-.8.1-1.5.2-2.1V2zm-1.9 5.6c-.1.8-.2 1.5-.3 2.1-.2.6-.4 1-.6 1.3-.3.3-.8.6-1.4.9-.7.3-1.4.5-2.2.8-.6.2-1.3.3-1.8.4L15 7.5c.3-.3.6-.7 1-1.1 0 .4 0 .8-.1 1.2zM6 20h8v-1.5H6V20z" />
				</svg>'
			),
			100
		);

		add_submenu_page(
			Options::get_page_slug(),
			__( 'More Plugins', 'ai-plus-block-editor' ),
			__( 'More Plugins', 'ai-plus-block-editor' ),
			'manage_options',
			sprintf( '%s-more-plugins', Options::get_page_slug() ),
			[ $this, 'register_more_plugins' ]
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
	 * Register More Plugins.
	 *
	 * This controls the display of the
	 * "More Plugins" submenu page.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 */
	public function register_more_plugins(): void {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		vprintf(
			'<section class="wrap">
				<h1>%s</h1>
				<p>%s</p>
				%s
			</section>',
			array_map(
				'__',
				[
					'More Plugins',
					'Check out some other amazing plugin of ours...',
					$this->pluginate->get_more_plugins(),
				]
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
	public function register_options_scripts(): void {
		$screen = get_current_screen();

		// Bail out, if not plugin Admin page.
		if ( ! is_object( $screen ) || ! str_contains( $screen->id, Options::get_page_slug() ) ) {
			return;
		}

		wp_enqueue_style(
			Options::get_page_slug(),
			plugin_dir_url( __FILE__ ) . '../../styles.css',
			[],
			'1.0.0',
			'all'
		);
	}
}
