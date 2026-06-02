<?php
/**
 * Page Class.
 *
 * This class is responsible for rendering the
 * "More Plugins" page.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Plugins;

class Page {
	/**
	 * Get Plugin Page.
	 *
	 * @since 1.10.0
	 *
	 * @return string
	 */
	public function get_markup(): string {
		return sprintf( '<ul class="more-plugins">%s</ul>', $this->get_content() );
	}

	/**
	 * Get Image URL.
	 *
	 * @since 1.10.0
	 *
	 * @param array $plugin Plugin data.
	 * @return string
	 */
	public function get_image_url( $plugin ): string {
		return plugins_url( '../assets/plugins/' . ( $plugin['slug'] ?? 'empty' ) . '.webp', __DIR__ );
	}

	/**
	 * Get Button Label.
	 *
	 * @since 1.10.0
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
	 * @since 1.10.0
	 *
	 * @param array $plugin Plugin data.
	 * @return string
	 */
	public function get_button_class( $plugin ): string {
		$label = $this->get_button_label( $plugin );

		switch ( $label ) {
			case 'Activate':
				$button_class = 'button-primary more-plugins-activate';
				break;

			case 'Installed':
				$button_class = 'button-secondary more-plugins-installed';
				break;

			case 'Install Plugin':
			default:
				$button_class = 'button more-plugins-install';
				break;
		}

		return $button_class;
	}

	/**
	 * Get Content.
	 *
	 * @since 1.10.0
	 *
	 * @return string
	 */
	public function get_content(): string {
		return array_reduce(
			$this->get_plugins(),
			function ( $carry, $plugin ) {
				$carry .= sprintf(
					'<li class="more-plugins-list-item">
						<div class="more-plugins-list-item-info-wrapper">
							<img src="%1$s" alt="%2$s"/>
							<div>
								<h2>%2$s</h2>
								<p>%3$s</p>
							</div>
						</div>
						<div class="more-plugins-list-item-action-wrapper">
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
	 * Get Plugins.
	 *
	 * @since 1.10.0
	 *
	 * @return array
	 */
	public function get_plugins(): array {
		return [
			[
				'title' => 'AI Plus Block Editor',
				'desc'  => 'Add AI Capabilities to the Block Editor. Generate Captions/Headlines, Summaries, Slugs, SEO Keywords using our amazing plugin.',
				'url'   => 'https://wordpress.org/plugins/ai-plus-block-editor/',
				'slug'  => 'ai-plus-block-editor',
			],
			[
				'title' => 'Image Converter for WebP',
				'desc'  => 'Convert your WordPress JPG and PNG images to efficient WebP format, improving performance, reducing file size, and enhancing website speed.',
				'url'   => 'https://wordpress.org/plugins/image-converter-webp/',
				'slug'  => 'image-converter-webp',
			],
			[
				'title' => 'Search & Replace for Block Editor',
				'desc'  => 'Search and Replace text within the WordPress Block Editor just like Microsoft Word or Google Docs. Its super fast, easy & just works!',
				'url'   => 'https://wordpress.org/plugins/search-replace-for-block-editor/',
				'slug'  => 'search-replace-for-block-editor',
			],
			[
				'title' => 'Convert Blocks to JSON',
				'desc'  => 'Convert your WP blocks to JSON. Import & Export blocks across multiple WordPress websites. Generate JSON for your Headless CMS websites.',
				'url'   => 'https://wordpress.org/plugins/convert-blocks-to-json/',
				'slug'  => 'convert-blocks-to-json',
			],
			[
				'title' => 'Trash Post in Block Editor',
				'desc'  => 'Delete a Post from within the WP Block Editor with just a few clicks, making content management easier and more efficient.',
				'url'   => 'https://wordpress.org/plugins/trash-post-in-block-editor/',
				'slug'  => 'trash-post-in-block-editor',
			],
			[
				'title' => 'Make Post Dirty',
				'desc'  => 'A useful tool for populating the block editor title and content. Never have to manually type content to test a feature.',
				'url'   => 'https://wordpress.org/plugins/make-post-dirty/',
				'slug'  => 'make-post-dirty',
			],
			[
				'title' => 'Manage Block Template',
				'desc'  => 'Create and manage block templates for different post types within your WordPress website. Perfect for sites with tons of post types!',
				'url'   => 'https://wordpress.org/plugins/manage-block-template/',
				'slug'  => 'manage-block-template',
			],
			[
				'title' => 'SQL to CPT',
				'desc'  => 'Import & Convert SQL tables to Custom Post Types (CPT). Migrate legacy SQL table data to WordPress custom post types of your choice. Its super fast!',
				'url'   => 'https://wordpress.org/plugins/sql-to-cpt/',
				'slug'  => 'sql-to-cpt',
			],
			[
				'title' => 'Censor Order Details',
				'desc'  => 'Hide sensitive customer order details in WooCommerce Admin page. Prevent order info from being visible to non-administrators.',
				'url'   => 'https://wordpress.org/plugins/censor-order-details/',
				'slug'  => 'censor-order-details',
			],
			[
				'title' => 'Display Site Notification Bar',
				'desc'  => 'Display a notice bar on your WP home page. It also allows you to customize how the notification bar looks like.',
				'url'   => 'https://wordpress.org/plugins/display-site-notification-bar/',
				'slug'  => 'display-site-notification-bar',
			],
			[
				'title' => 'Watermark My Images',
				'desc'  => 'Add watermarks to your images for better protection. Customize text and position of watermark to your taste with so much ease.',
				'url'   => 'https://wordpress.org/plugins/watermark-my-images/',
				'slug'  => 'watermark-my-images',
			],
			[
				'title' => 'Create Product on Image Upload',
				'desc'  => 'Create WooCommerce products automatically by uploading images. Get new products to your e-commerce store faster and more efficiently.',
				'url'   => 'https://wordpress.org/plugins/create-product-on-image-upload/',
				'slug'  => 'create-product-on-image-upload',
			],
			[
				'title' => 'Pending Order Bot',
				'desc'  => 'Send automated reminders to customers about their pending WooCommerce orders, reduce abandoned carts and improve sales on your e-commerce website.',
				'url'   => 'https://wordpress.org/plugins/pending-order-bot/',
				'slug'  => 'pending-order-bot',
			],
			[
				'title' => 'Redirect Duplicate Posts',
				'desc'  => 'Redirect users away from duplicate posts to the original post URL, improve Search Engine Optimization for your website.',
				'url'   => 'https://wordpress.org/plugins/redirect-duplicate-posts/',
				'slug'  => 'redirect-duplicate-posts',
			],
			[
				'title' => 'Ping Me On Slack',
				'desc'  => 'Receive instant Slack notifications whenever updates, edits, or changes are made on your WordPress website, stay informed in real time.',
				'url'   => 'https://wordpress.org/plugins/ping-me-on-slack/',
				'slug'  => 'ping-me-on-slack',
			],
			[
				'title' => 'Addon for Post Meta Translation using DeepL',
				'desc'  => 'Add translation for post meta data when using DeepL for WordPress. Integrates nicely with WooCommerce, Yoast SEO and major plugins.',
				'url'   => 'https://wordpress.org/plugins/addon-for-post-meta-translation-using-deepl/',
				'slug'  => 'addon-for-post-meta-translation-using-deepl',
			],
		];
	}
}
