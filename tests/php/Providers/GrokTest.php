<?php

namespace AiPlusBlockEditor\Tests\Providers;

use WP_Mock;
use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Providers\Grok;

/**
 * @covers \AiPlusBlockEditor\Providers\Grok::get_default_args
 * @covers \AiPlusBlockEditor\Providers\Grok::get_api_url
 * @covers \AiPlusBlockEditor\Providers\Grok::run
 * @covers \AiPlusBlockEditor\Providers\Grok::get_provider_response
 * @covers \AiPlusBlockEditor\Providers\Grok::get_json_error
 * @covers \AiPlusBlockEditor\Admin\Options::__callStatic
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_fields
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_notice
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_page
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_submit
 * @covers \AiPlusBlockEditor\Admin\Options::init
 */
class GrokTest extends TestCase {
	public Grok $grok;
	public $providers;

	public function setUp(): void {
		WP_Mock::setUp();

		WP_Mock::userFunction( '__' )
			->andReturnUsing(
				function ( $arg1, $arg2 ) {
					return $arg1;
				}
			);

		WP_Mock::userFunction( 'esc_html__' )
			->andReturnUsing(
				function ( $arg1, $arg2 ) {
					return $arg1;
				}
			);

		WP_Mock::userFunction( 'esc_attr' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		WP_Mock::userFunction( 'esc_url' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		WP_Mock::userFunction( 'is_wp_error' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg instanceof \WP_Error;
				}
			);

		WP_Mock::userFunction( 'wp_json_encode' )
			->andReturnUsing(
				function ( $arg ) {
					return json_encode( $arg );
				}
			);

		WP_Mock::userFunction( 'wp_parse_args' )
			->andReturnUsing(
				function ( $arg1, $arg2 ) {
					return array_merge( $arg2, $arg1 );
				}
			);

		$this->grok = new Grok();

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

	public function test_get_default_args() {
		WP_Mock::expectFilter(
			'apbe_grok_args',
			[
				'model'  => 'grok-4',
				'stream' => false,
			]
		);

		$reflection = new \ReflectionClass( $this->grok );
		$method     = $reflection->getMethod( 'get_default_args' );

		$method->setAccessible( true );
		$args = $method->invoke( $this->grok );

		$this->assertSame(
			$args,
			[
				'model'  => 'grok-4',
				'stream' => false,
			]
		);
	}

	public function test_get_api_url() {
		$grok = Mockery::mock( Grok::class )->makePartial();
		$grok->shouldAllowMockingProtectedMethods();

		WP_Mock::expectFilter(
			'apbe_grok_api_url',
			'https://api.x.ai/v1/chat/completions'
		);

		$url = $grok->get_api_url();

		$this->assertSame( $url, 'https://api.x.ai/v1/chat/completions' );
		$this->assertConditionsMet();
	}

	public function test_run_fails_if_missing_api_keys_and_returns_wp_error() {
		$grok = Mockery::mock( Grok::class )->makePartial();
		$grok->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'grok_token' => '',
				]
			);

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'Missing Grok API key.',
			'[]',
			'Grok',
		);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$response = $grok->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run_fails_if_missing_prompt_text_and_returns_wp_error() {
		$grok = Mockery::mock( Grok::class )->makePartial();
		$grok->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'grok_token' => 'age38gegewjdhagepkhif',
				]
			);

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'Invalid prompt text.',
			'[]',
			'Grok',
		);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$response = $grok->run(
			[
				'content' => '',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run_fails_returns_wp_error_if_malformed_JSON_is_returned() {
		$grok = Mockery::mock( Grok::class )->makePartial();
		$grok->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		$grok->shouldReceive( 'get_default_args' )
			->andReturn(
				[
					'model'  => 'grok-4',
					'stream' => false,
				]
			);

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'grok_token' => 'age38gegewjdhagepkhif',
				]
			);

		WP_Mock::expectFilter(
			'apbe_grok_system_prompt',
			'You are Grok, a highly intelligent, helpful AI assistant.'
		);

		$grok->shouldReceive( 'get_api_url' )
			->andReturn( '' );

		WP_Mock::userFunction( 'wp_remote_post' )
			->andReturn( '{"body":{"choices":[{"message":{"content":"What a Wonderful World!"}}]}}' );

		// Return malformed JSON response
		WP_Mock::userFunction( 'wp_remote_retrieve_body' )
			->andReturn( '{"choices":[{"message":{"content":' );

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'Unexpected Grok API response.',
			'{"model":"grok-4","stream":false,"messages":[{"role":"system","content":"You are Grok, a highly intelligent, helpful AI assistant."},{"role":"user","content":"Generate me an SEO friendly Headline using: Hello World!"}]}',
			'Grok',
		);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$response = $grok->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run() {
		$grok = Mockery::mock( Grok::class )->makePartial();
		$grok->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		$grok->shouldReceive( 'get_default_args' )
			->andReturn(
				[
					'model'  => 'grok-4',
					'stream' => false,
				]
			);

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'grok_token' => 'age38gegewjdhagepkhif',
				]
			);

		WP_Mock::expectFilter(
			'apbe_grok_system_prompt',
			'You are Grok, a highly intelligent, helpful AI assistant.'
		);

		$grok->shouldReceive( 'get_api_url' )
			->andReturn( '' );

		WP_Mock::userFunction( 'wp_remote_post' )
			->andReturn( '{"body":{"choices":[{"message":{"content":"What a Wonderful World!"}}]}}' );

		WP_Mock::userFunction( 'wp_remote_retrieve_body' )
			->andReturn( '{"choices":[{"message":{"content":"What a Wonderful World!"}}]}' );

		WP_Mock::expectAction(
			'apbe_ai_provider_success_call',
			'What a Wonderful World!',
			'{"model":"grok-4","stream":false,"messages":[{"role":"system","content":"You are Grok, a highly intelligent, helpful AI assistant."},{"role":"user","content":"Generate me an SEO friendly Headline using: Hello World!"}]}',
			'Grok',
		);

		WP_Mock::expectFilter(
			'apbe_ai_provider_response',
			'What a Wonderful World!',
			'{"model":"grok-4","stream":false,"messages":[{"role":"system","content":"You are Grok, a highly intelligent, helpful AI assistant."},{"role":"user","content":"Generate me an SEO friendly Headline using: Hello World!"}]}',
			'Grok',
		);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$response = $grok->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertSame( 'What a Wonderful World!', $response );
		$this->assertConditionsMet();
	}

	public function test_get_json_error() {
		$grok = Mockery::mock( Grok::class )->makePartial();
		$grok->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'API Error...',
			'[]',
			'Grok',
		);

		$response = $grok->get_json_error( 'API Error...' );

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}
}
