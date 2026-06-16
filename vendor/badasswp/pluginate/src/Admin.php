<?php
/**
 * Admin Class.
 *
 * This class is responsible for rendering the
 * "More Plugins" options page.
 *
 * @package Pluginate
 */

namespace Pluginate;

/**
 * Admin class.
 */
class Admin {
	/*
	 * Plugin Slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $slug;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string
	 */
	public function __construct( $slug = '' ) {
		$this->slug = $slug;
	}

	/**
	 * Initialize the Admin class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init(): void {
		( new Ajax() )->register();

		add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
	}

	/**
	 * Get More Plugins.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_more_plugins(): string {
		return sprintf( '<ul class="pluginate">%s</ul>', $this->get_content() );
	}

	/**
	 * Get Image URL.
	 *
	 * @since 1.0.0
	 *
	 * @param array $plugin Plugin data.
	 * @return string
	 */
	public function get_image_url( $plugin ): string {
		return plugins_url( '/assets/' . ( $plugin['slug'] ?? 'empty' ) . '.webp', __DIR__ );
	}

	/**
	 * Get Button Label.
	 *
	 * @since 1.0.0
	 *
	 * @param array $plugin Plugin data.
	 * @return string
	 */
	public function get_button_label( $plugin ): string {
		return Installer::get_plugin_status( $plugin['slug'] ?? '' );
	}

	/**
	 * Get Button Class.
	 *
	 * @since 1.0.0
	 *
	 * @param array $plugin Plugin data.
	 * @return string
	 */
	public function get_button_class( $plugin ): string {
		$label = $this->get_button_label( $plugin );

		switch ( $label ) {
			case 'Activate':
				$button_class = 'button-primary pluginate-activate';
				break;

			case 'Installed':
				$button_class = 'button-secondary pluginate-installed';
				break;

			case 'Install Plugin':
			default:
				$button_class = 'button pluginate-install';
				break;
		}

		return $button_class;
	}

	/**
	 * Get Plugins.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_plugins(): array {
		return Plugins::PLUGINS;
	}

	/**
	 * Get Content.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_content(): string {
		return array_reduce(
			$this->get_plugins(),
			function ( $carry, $plugin ) {
				$carry .= sprintf(
					'<li class="pluginate-list-item">
						<div class="pluginate-list-item-info-wrapper">
							<img src="%1$s" alt="%2$s"/>
							<div>
								<h2>%2$s</h2>
								<p>%3$s</p>
							</div>
						</div>
						<div class="pluginate-list-item-action-wrapper">
							<a
								href="#"
								rel="noopener noreferrer"
								class="%6$s"
								data-slug="%4$s"
								data-file="%4$s/%4$s.php"
							>
								%5$s
							</a>
						</div>
					</li>',
					esc_url( $this->get_image_url( $plugin ) ),
					esc_html( $plugin['title'] ?? '' ),
					esc_html( $plugin['desc'] ?? '' ),
					esc_attr( $plugin['slug'] ?? '' ),
					esc_html( $this->get_button_label( $plugin ) ),
					esc_attr( $this->get_button_class( $plugin ) ),
				);

				return $carry;
			},
			''
		);
	}

	/**
	 * Register Scripts and Styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_scripts(): void {
		$screen = get_current_screen();

		// Bail out, if not plugin Admin page.
		if ( ! is_object( $screen ) || ( isset( $this->slug ) && ! str_contains( $screen->id, $this->slug ) ) ) {
			return;
		}

		wp_enqueue_style(
			'pluginate-styles',
			plugin_dir_url( __FILE__ ) . '../styles.css',
			[],
			'1.0.0',
			'all'
		);

		wp_enqueue_script(
			'pluginate-scripts',
			plugin_dir_url( __FILE__ ) . '../scripts.js',
			[ 'jquery' ],
			'1.0.0',
			true
		);

		wp_localize_script(
			'pluginate-scripts',
			'ajax_pluginate',
			[
				'nonce'    => wp_create_nonce( 'ajax-pluginate-nonce' ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			]
		);
	}
}
