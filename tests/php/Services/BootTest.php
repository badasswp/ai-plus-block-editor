<?php

namespace AiPlusBlockEditor\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Services\Boot;
use AiPlusBlockEditor\Abstracts\Service;

/**
 * @covers \AiPlusBlockEditor\Services\Boot::register
 * @covers \AiPlusBlockEditor\Services\Boot::register_translation
 * @covers \AiPlusBlockEditor\Services\Boot::register_scripts
 */
class BootTest extends TestCase {
	public Boot $boot;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->boot = new Boot();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'init', [ $this->boot, 'register_translation' ] );
		\WP_Mock::expectActionAdded( 'enqueue_block_editor_assets', [ $this->boot, 'register_scripts' ] );

		$this->boot->register();

		$this->assertConditionsMet();
	}

	public function test_register_scripts() {
		$boot = new \ReflectionClass( Boot::class );

		\WP_Mock::userFunction( 'plugins_url' )
			->andReturnUsing(
				function ( $arg ) {
					return sprintf( 'https://example.com/wp-content/plugins/%s', $arg );
				}
			);

		\WP_Mock::userFunction( 'plugin_dir_path' )
			->with( $boot->getFileName() )
			->andReturn( '/var/www/wp-content/plugins/ai-plus-block-editor/inc/Services/' );

		\WP_Mock::userFunction( 'wp_enqueue_script' )
			->with(
				'ai-plus-block-editor',
				'https://example.com/wp-content/plugins/ai-plus-block-editor/dist/app.js',
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

		\WP_Mock::userFunction( 'get_option' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		\WP_Mock::userFunction( 'esc_html__' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		\WP_Mock::userFunction( 'esc_attr' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		\WP_Mock::userFunction( 'wp_localize_script' )
			->andReturn( null );

		\WP_Mock::userFunction( 'wp_set_script_translations' )
			->with(
				'ai-plus-block-editor',
				'ai-plus-block-editor',
				'/var/www/wp-content/plugins/ai-plus-block-editor/inc/Services/../../languages',
			);

		$this->boot->register_scripts();

		$this->assertConditionsMet();
	}

	public function test_register_translation() {
		$boot = new \ReflectionClass( Boot::class );

		\WP_Mock::userFunction( 'esc_html__' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		\WP_Mock::userFunction( 'esc_attr' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		\WP_Mock::userFunction( 'plugin_basename' )
			->once()
			->with( $boot->getFileName() )
			->andReturn( '/inc/Services/Boot.php' );

		\WP_Mock::userFunction( 'load_plugin_textdomain' )
			->once()
			->with(
				'ai-plus-block-editor',
				false,
				'/inc/Services/../../languages'
			);

		$this->boot->register_translation();

		$this->assertConditionsMet();
	}
}
