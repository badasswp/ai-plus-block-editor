<?php

namespace AiPlusBlockEditor\Tests\Services;

use WP_Mock;
use Mockery;
use WP_Mock\Tools\TestCase;
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
 */
class AdminTest extends TestCase {
	public Admin $admin;
	public $providers;

	public function setUp(): void {
		WP_Mock::setUp();

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
		WP_Mock::tearDown();
	}

	public function test_register() {
		WP_Mock::expectActionAdded( 'admin_init', [ $this->admin, 'register_options_init' ] );
		WP_Mock::expectActionAdded( 'admin_menu', [ $this->admin, 'register_options_menu' ] );
		WP_Mock::expectActionAdded( 'admin_enqueue_scripts', [ $this->admin, 'register_options_styles' ] );

		$this->admin->register();

		$this->assertConditionsMet();
	}

	public function test_register_options_menu() {
		WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction( 'add_menu_page' )
			->once()
			->with(
				'AI + Block Editor',
				'AI + Block Editor',
				'manage_options',
				'ai-plus-block-editor',
				[ $this->admin, 'register_options_page' ],
				'dashicons-superhero',
				100
			)
			->andReturn( null );

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$menu = $this->admin->register_options_menu();

		$this->assertNull( $menu );
		$this->assertConditionsMet();
	}

	public function test_register_options_init_bails_out_if_any_nonce_settings_is_missing() {
		WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

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

		WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

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
		WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

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

		WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

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
