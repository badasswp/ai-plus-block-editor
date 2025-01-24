<?php

namespace AiPlusBlockEditor\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Services\Boot;
use AiPlusBlockEditor\Abstracts\Service;

/**
 * @covers \AiPlusBlockEditor\Services\Boot::register
 * @covers \AiPlusBlockEditor\Services\Boot::register_translation
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

	public function test_register_translation() {
		$boot = new \ReflectionClass( Boot::class );

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
