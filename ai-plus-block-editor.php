<?php
/**
 * Plugin Name: AI + Block Editor
 * Plugin URI:  https://github.com/badasswp/ai-plus-block-editor
 * Description: Add AI Capabilities to the WP Block Editor.
 * Version:     1.8.0
 * Author:      badasswp
 * Author URI:  https://github.com/badasswp
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: ai-plus-block-editor
 * Domain Path: /languages
 *
 * @package AiPlusBlockEditor
 */

namespace badasswp\AiPlusBlockEditor;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

define( 'APBE_AUTOLOAD', __DIR__ . '/vendor/autoload.php' );

// Composer Check.
if ( ! file_exists( APBE_AUTOLOAD ) ) {
	add_action(
		'admin_notices',
		function () {
			vprintf(
				/* translators: Plugin directory path. */
				esc_html__( 'Fatal Error: Composer not setup in %s', 'ai-plus-block-editor' ),
				[ __DIR__ ]
			);
		}
	);

	return;
}

// Run Plugin.
require_once APBE_AUTOLOAD;
( \AiPlusBlockEditor\Plugin::get_instance() )->run();
