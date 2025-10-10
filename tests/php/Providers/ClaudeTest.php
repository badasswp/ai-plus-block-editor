<?php

namespace AiPlusBlockEditor\Tests\Providers;

use WP_Mock;
use Mockery;
use Badasswp\WPMockTC\WPMockTestCase;
use AiPlusBlockEditor\Providers\Claude;

/**
 * @covers \AiPlusBlockEditor\Providers\Claude::get_default_args
 * @covers \AiPlusBlockEditor\Providers\Claude::get_api_url
 * @covers \AiPlusBlockEditor\Providers\Claude::run
 * @covers \AiPlusBlockEditor\Providers\Claude::get_provider_response
 * @covers \AiPlusBlockEditor\Providers\Claude::get_json_error
 * @covers \AiPlusBlockEditor\Admin\Options::__callStatic
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_fields
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_notice
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_page
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_submit
 * @covers \AiPlusBlockEditor\Admin\Options::init
 * @covers \AiPlusBlockEditor\Abstracts\Provider::get_providers
 */
class ClaudeTest extends WPMockTestCase {
	public Claude $claude;
	public $providers;

	public function setUp(): void {
		parent::setUp();

		$this->claude = new Claude();

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

	public function test_get_default_args() {
		WP_Mock::expectFilter(
			'apbe_claude_args',
			[
				'model'      => 'claude-3-opus-20240229',
				'max_tokens' => 512,
			]
		);

		$reflection = new \ReflectionClass( $this->claude );
		$method     = $reflection->getMethod( 'get_default_args' );

		$method->setAccessible( true );
		$args = $method->invoke( $this->claude );

		$this->assertSame(
			$args,
			[
				'model'      => 'claude-3-opus-20240229',
				'max_tokens' => 512,
			]
		);
	}

	public function test_get_api_url() {
		$claude = Mockery::mock( Claude::class )->makePartial();
		$claude->shouldAllowMockingProtectedMethods();

		WP_Mock::expectFilter(
			'apbe_claude_api_url',
			'https://api.anthropic.com/v1/messages'
		);

		$url = $claude->get_api_url();

		$this->assertSame( $url, 'https://api.anthropic.com/v1/messages' );
		$this->assertConditionsMet();
	}

	public function test_run_fails_if_missing_api_keys_and_returns_wp_error() {
		$claude = Mockery::mock( Claude::class )->makePartial();
		$claude->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'claude_token' => '',
				]
			);

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'Missing Claude API key.',
			'[]',
			'Claude',
		);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$response = $claude->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run_fails_if_missing_prompt_text_and_returns_wp_error() {
		$claude = Mockery::mock( Claude::class )->makePartial();
		$claude->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'claude_token' => 'age38gegewjdhagepkhif',
				]
			);

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'Invalid prompt text.',
			'[]',
			'Claude',
		);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$response = $claude->run(
			[
				'content' => '',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run_fails_returns_wp_error_if_malformed_JSON_is_returned() {
		$claude = Mockery::mock( Claude::class )->makePartial();
		$claude->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		$claude->shouldReceive( 'get_default_args' )
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
					'claude_token' => 'age38gegewjdhagepkhif',
				]
			);

		WP_Mock::expectFilter(
			'apbe_claude_system_prompt',
			'You are Claude, a highly intelligent, helpful AI assistant.'
		);

		$claude->shouldReceive( 'get_api_url' )
			->andReturn( '' );

		WP_Mock::userFunction( 'wp_remote_post' )
			->andReturn( '{"body":{"choices":[{"message":{"content":"What a Wonderful World!"}}]}}' );

		// Return malformed JSON response
		WP_Mock::userFunction( 'wp_remote_retrieve_body' )
			->andReturn( '{"choices":[{"message":{"content":' );

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'Unexpected Claude API response.',
			'{"model":"grok-4","stream":false,"messages":[{"role":"system","content":"You are Claude, a highly intelligent, helpful AI assistant."},{"role":"user","content":"Generate me an SEO friendly Headline using: Hello World!"}]}',
			'Claude',
		);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$response = $claude->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run() {
		$claude = Mockery::mock( Claude::class )->makePartial();
		$claude->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		$claude->shouldReceive( 'get_default_args' )
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
					'claude_token' => 'age38gegewjdhagepkhif',
				]
			);

		WP_Mock::expectFilter(
			'apbe_claude_system_prompt',
			'You are Claude, a highly intelligent, helpful AI assistant.'
		);

		$claude->shouldReceive( 'get_api_url' )
			->andReturn( '' );

		WP_Mock::userFunction( 'wp_remote_post' )
			->andReturn( '{"body":{"choices":[{"message":{"content":"What a Wonderful World!"}}]}}' );

		WP_Mock::userFunction( 'wp_remote_retrieve_body' )
			->andReturn( '{"choices":[{"message":{"content":"What a Wonderful World!"}}]}' );

		WP_Mock::expectAction(
			'apbe_ai_provider_success_call',
			'What a Wonderful World!',
			'{"model":"grok-4","stream":false,"messages":[{"role":"system","content":"You are Claude, a highly intelligent, helpful AI assistant."},{"role":"user","content":"Generate me an SEO friendly Headline using: Hello World!"}]}',
			'Claude',
		);

		WP_Mock::expectFilter(
			'apbe_ai_provider_response',
			'What a Wonderful World!',
			'{"model":"grok-4","stream":false,"messages":[{"role":"system","content":"You are Claude, a highly intelligent, helpful AI assistant."},{"role":"user","content":"Generate me an SEO friendly Headline using: Hello World!"}]}',
			'Claude',
		);

		WP_Mock::expectFilter( 'apbe_ai_providers', $this->providers );

		$response = $claude->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertSame( 'What a Wonderful World!', $response );
		$this->assertConditionsMet();
	}

	public function test_get_json_error() {
		$claude = Mockery::mock( Claude::class )->makePartial();
		$claude->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'API Error...',
			'[]',
			'Claude',
		);

		$response = $claude->get_json_error( 'API Error...' );

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}
}
