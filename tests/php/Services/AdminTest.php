<?php

namespace AiPlusBlockEditor\Tests\Services;

use WP_Mock;
use Mockery;
use Badasswp\WPMockTC\WPMockTestCase;
use AiPlusBlockEditor\Services\Admin;

/**
 * @covers \AiPlusBlockEditor\Services\Admin::register
 * @covers \AiPlusBlockEditor\Services\Admin::register_options_menu
 * @covers \AiPlusBlockEditor\Services\Admin::register_options_init
 * @covers \AiPlusBlockEditor\Services\Admin::register_options_styles
 * @covers \AiPlusBlockEditor\Admin\Options::__callStatic
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_fields
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_notice
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_page
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_submit
 * @covers \AiPlusBlockEditor\Admin\Options::init
 * @covers \AiPlusBlockEditor\Abstracts\Provider::get_providers
 */
class AdminTest extends WPMockTestCase {
	public Admin $admin;
	public $providers;

	public function setUp(): void {
		parent::setUp();

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn( [] );

		$this->admin = new Admin();

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
		WP_Mock::expectActionAdded( 'admin_init', [ $this->admin, 'register_options_init' ] );
		WP_Mock::expectActionAdded( 'admin_menu', [ $this->admin, 'register_options_menu' ] );
		WP_Mock::expectActionAdded( 'admin_enqueue_scripts', [ $this->admin, 'register_options_styles' ] );

		$this->admin->register();

		$this->assertConditionsMet();
	}

	public function test_register_options_menu() {
		WP_Mock::userFunction( 'add_menu_page' )
			->once()
			->with(
				'AI + Block Editor',
				'AI + Block Editor',
				'manage_options',
				'ai-plus-block-editor',
				[ $this->admin, 'register_options_page' ],
				'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iY3VycmVudENvbG9yIj4KCQkJCQk8cGF0aCBkPSJNMTcuOCAybC0uOS4zYy0uMSAwLTMuNiAxLTUuMiAyLjFDMTAgNS41IDkuMyA2LjUgOC45IDcuMWMtLjYuOS0xLjcgNC43LTEuNyA2LjNsLS45IDIuM2MtLjIuNCAwIC44LjQgMSAuMSAwIC4yLjEuMy4xLjMgMCAuNi0uMi43LS41bC42LTEuNWMuMyAwIC43LS4xIDEuMi0uMi43LS4xIDEuNC0uMyAyLjItLjUuOC0uMiAxLjYtLjUgMi40LS44LjctLjMgMS40LS43IDEuOS0xLjJzLjgtMS4yIDEtMS45Yy4yLS43LjMtMS42LjQtMi40LjEtLjguMS0xLjcuMi0yLjUgMC0uOC4xLTEuNS4yLTIuMVYyem0tMS45IDUuNmMtLjEuOC0uMiAxLjUtLjMgMi4xLS4yLjYtLjQgMS0uNiAxLjMtLjMuMy0uOC42LTEuNC45LS43LjMtMS40LjUtMi4yLjgtLjYuMi0xLjMuMy0xLjguNEwxNSA3LjVjLjMtLjMuNi0uNyAxLTEuMSAwIC40IDAgLjgtLjEgMS4yek02IDIwaDh2LTEuNUg2VjIweiIgLz4KCQkJCTwvc3ZnPg==',
				100
			)
			->andReturn( null );

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$menu = $this->admin->register_options_menu();

		$this->assertNull( $menu );
		$this->assertConditionsMet();
	}

	public function test_register_options_init_bails_out_if_any_nonce_settings_is_missing() {
		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$_POST = [
			'ai_plus_block_editor_save_settings' => true,
		];

		$settings = $this->admin->register_options_init();

		$this->assertNull( $settings );
		$this->assertConditionsMet();
	}

	public function test_register_options_init_bails_out_if_nonce_verification_fails() {
		$_POST = [
			'ai_plus_block_editor_save_settings'  => true,
			'ai_plus_block_editor_settings_nonce' => 'a8vbq3cg3sa',
		];

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		WP_Mock::userFunction( 'wp_unslash' )
			->times( 1 )
			->with( 'a8vbq3cg3sa' )
			->andReturn( 'a8vbq3cg3sa' );

		WP_Mock::userFunction( 'sanitize_text_field' )
			->times( 1 )
			->with( 'a8vbq3cg3sa' )
			->andReturn( 'a8vbq3cg3sa' );

		WP_Mock::userFunction( 'wp_verify_nonce' )
			->once()
			->with( 'a8vbq3cg3sa', 'ai_plus_block_editor_settings_action' )
			->andReturn( false );

		$settings = $this->admin->register_options_init();

		$this->assertNull( $settings );
		$this->assertConditionsMet();
	}

	public function test_register_options_init_passes() {
		$_POST = [
			'ai_plus_block_editor_save_settings'  => true,
			'ai_plus_block_editor_settings_nonce' => 'a8vbq3cg3sa',
		];

		WP_Mock::userFunction(
			'wp_unslash',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction( 'sanitize_text_field' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		WP_Mock::userFunction( 'wp_verify_nonce' )
			->times( 1 )
			->with( 'a8vbq3cg3sa', 'ai_plus_block_editor_settings_action' )
			->andReturn( true );

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		WP_Mock::userFunction( 'update_option' )
			->once()
			->with(
				'ai_plus_block_editor',
				[
					'claude_enable'        => '',
					'claude_token'         => '',
					'grok_enable'          => '',
					'grok_token'           => '',
					'deepseek_enable'      => '',
					'deepseek_token'       => '',
					'google_gemini_enable' => '',
					'google_gemini_token'  => '',
					'open_ai_enable'       => '',
					'open_ai_token'        => '',
					'ai_provider'          => '',
				]
			)
			->andReturn( null );

		$settings = $this->admin->register_options_init();

		$this->assertNull( $settings );
		$this->assertConditionsMet();
	}

	public function test_register_options_styles_passes() {
		$screen = Mockery::mock( \WP_Screen::class )->makePartial();
		$screen->shouldAllowMockingProtectedMethods();
		$screen->id = 'toplevel_page_ai-plus-block-editor';

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		WP_Mock::userFunction( 'get_current_screen' )
			->andReturn( $screen );

		$reflection = new \ReflectionClass( Admin::class );

		WP_Mock::userFunction( 'plugin_dir_url' )
			->with( $reflection->getFileName() )
			->andReturn( 'https://example.com/wp-content/plugins/ai-plus-block-editor/inc/Services/' );

		WP_Mock::userFunction( 'wp_enqueue_style' )
			->with(
				'ai-plus-block-editor',
				'https://example.com/wp-content/plugins/ai-plus-block-editor/inc/Services/../../styles.css',
				[],
				true,
				'all'
			)
			->andReturn( null );

		$this->admin->register_options_styles();

		$this->assertConditionsMet();
	}

	public function test_register_options_styles_bails() {
		WP_Mock::userFunction( 'get_current_screen' )
			->andReturn( '' );

		$this->admin->register_options_styles();

		$this->assertConditionsMet();
	}
}
