<?php

namespace AiPlusBlockEditor\Tests\Services;

use WP_Mock;
use Mockery;
use Badasswp\WPMockTC\WPMockTestCase;
use AiPlusBlockEditor\Services\Boot;
use AiPlusBlockEditor\Abstracts\Service;

/**
 * @covers \AiPlusBlockEditor\Services\Boot::register
 * @covers \AiPlusBlockEditor\Services\Boot::register_translation
 * @covers \AiPlusBlockEditor\Services\Boot::register_scripts
 * @covers \AiPlusBlockEditor\Admin\Options::__callStatic
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_fields
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_notice
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_page
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_submit
 * @covers \AiPlusBlockEditor\Admin\Options::init
 * @covers \AiPlusBlockEditor\Abstracts\Provider::get_providers
 * @covers \AiPlusBlockEditor\Services\Boot::get_providers
 */
class BootTest extends WPMockTestCase {
	public Boot $boot;
	public $providers;

	public function setUp(): void {
		parent::setUp();

		$this->boot = new Boot();

		$this->providers = [
			'OpenAI'   => 'ChatGPT',
			'Gemini'   => 'Gemini',
			'DeepSeek' => 'DeepSeek',
			'Grok'     => 'Grok',
			'Claude'   => 'Claude',
		];
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	public function test_register() {
		WP_Mock::expectActionAdded( 'init', [ $this->boot, 'register_translation' ] );
		WP_Mock::expectActionAdded( 'enqueue_block_editor_assets', [ $this->boot, 'register_scripts' ] );

		$this->boot->register();

		$this->assertConditionsMet();
	}

	public function test_register_scripts() {
		$boot = new \ReflectionClass( Boot::class );

		$mock_boot = Mockery::mock( Boot::class )->makePartial();
		$mock_boot->shouldAllowMockingProtectedMethods();

		$mock_boot->shouldReceive( 'get_assets' )
			->andReturn(
				[
					'dependencies' => [
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
					'version'      => '1750321560',
				]
			);

		WP_Mock::userFunction( 'plugins_url' )
			->andReturnUsing(
				function ( $arg ) {
					return sprintf( 'https://example.com/wp-content/plugins/%s', $arg );
				}
			);

		WP_Mock::userFunction( 'plugin_dir_path' )
			->with( $boot->getFileName() )
			->andReturn( '/var/www/wp-content/plugins/ai-plus-block-editor/inc/Services/' );

		WP_Mock::userFunction( 'wp_enqueue_script' )
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
				'1750321560',
				false,
			);

		WP_Mock::userFunction( 'get_option' )
			->andReturn(
				[
					'ai_provider' => 'AI Provider',
				]
			);

		WP_Mock::userFunction( 'wp_localize_script' )
			->with(
				'ai-plus-block-editor',
				'apbe',
				[
					'provider'  => 'AI Provider',
					'providers' => [
						[
							'label' => 'ChatGPT',
							'value' => 'OpenAI',
						],
						[
							'label' => 'Gemini',
							'value' => 'Gemini',
						],
						[
							'label' => 'DeepSeek',
							'value' => 'DeepSeek',
						],
						[
							'label' => 'Grok',
							'value' => 'Grok',
						],
						[
							'label' => 'Claude',
							'value' => 'Claude',
						],
					],
				]
			)
			->andReturn( null );

		WP_Mock::userFunction( 'wp_set_script_translations' )
			->with(
				'ai-plus-block-editor',
				'ai-plus-block-editor',
				'/var/www/wp-content/plugins/ai-plus-block-editor/inc/Services/../../languages',
			);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$mock_boot->register_scripts();

		$this->assertConditionsMet();
	}

	public function test_register_translation() {
		$boot = new \ReflectionClass( Boot::class );

		WP_Mock::userFunction( 'plugin_basename' )
			->once()
			->with( $boot->getFileName() )
			->andReturn( '/inc/Services/Boot.php' );

		WP_Mock::userFunction( 'load_plugin_textdomain' )
			->once()
			->with(
				'ai-plus-block-editor',
				false,
				'/inc/Services/../../languages'
			);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$this->boot->register_translation();

		$this->assertConditionsMet();
	}

	public function test_get_providers() {
		$boot = Mockery::mock( Boot::class )->makePartial();
		$boot->shouldAllowMockingProtectedMethods();

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$this->assertSame(
			$boot->get_providers(),
			[
				[
					'label' => 'ChatGPT',
					'value' => 'OpenAI',
				],
				[
					'label' => 'Gemini',
					'value' => 'Gemini',
				],
				[
					'label' => 'DeepSeek',
					'value' => 'DeepSeek',
				],
				[
					'label' => 'Grok',
					'value' => 'Grok',
				],
				[
					'label' => 'Claude',
					'value' => 'Claude',
				],
			]
		);
	}
}
