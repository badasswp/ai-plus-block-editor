<?php

namespace AiPlusBlockEditor\Tests\Providers;

use WP_Mock;
use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Providers\OpenAI;
use Orhanerday\OpenAi\OpenAi as ChatGPT;

/**
 * @covers \AiPlusBlockEditor\Providers\OpenAI::run
 * @covers \AiPlusBlockEditor\Providers\OpenAI::get_default_args
 * @covers \AiPlusBlockEditor\Providers\OpenAI::get_provider_response
 * @covers \AiPlusBlockEditor\Providers\OpenAI::get_json_error
 * @covers \AiPlusBlockEditor\Admin\Options::__callStatic
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_fields
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_notice
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_page
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_submit
 * @covers \AiPlusBlockEditor\Admin\Options::init
 * @covers \AiPlusBlockEditor\Providers\OpenAI::get_client
 */
class OpenAITest extends TestCase {
	public OpenAI $open_ai;

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

		$this->open_ai = new OpenAI();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_get_default_args() {
		WP_Mock::expectFilter(
			'apbe_open_ai_args',
			[
				'model'             => 'gpt-3.5-turbo',
				'temperature'       => 1.0,
				'max_tokens'        => 4000,
				'frequency_penalty' => 0,
				'presence_penalty'  => 0,
			]
		);

		$reflection = new \ReflectionClass( $this->open_ai );
		$method     = $reflection->getMethod( 'get_default_args' );

		$method->setAccessible( true );
		$args = $method->invoke( $this->open_ai );

		$this->assertSame(
			$args,
			[
				'model'             => 'gpt-3.5-turbo',
				'temperature'       => 1.0,
				'max_tokens'        => 4000,
				'frequency_penalty' => 0,
				'presence_penalty'  => 0,
			]
		);
	}

	public function test_get_client() {
		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'open_ai_token' => 'age38gegewjdhagepkhif',
				]
			);

		$this->assertInstanceOf( ChatGPT::class, $open_ai->get_client() );
	}

	public function test_run_fails_if_missing_api_keys_and_returns_wp_error() {
		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'open_ai_token' => '',
				]
			);

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'Missing OpenAI API key.',
			'[]',
			'OpenAI',
		);

		$response = $open_ai->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run_fails_if_missing_prompt_text_and_returns_wp_error() {
		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'open_ai_token' => 'age38gegewjdhagepkhif',
				]
			);

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'Invalid prompt text.',
			'[]',
			'OpenAI',
		);

		$response = $open_ai->run(
			[
				'content' => '',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	/**
	 * @runInSeparateProcess
	 *
	 * This test is flaky because we cannot accurately
	 * mock the 3rd party OpenAI class.
	 */
	public function test_run() {
		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		$chat_gpt = Mockery::mock( ChatGPT::class )->makePartial();
		$chat_gpt->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		$open_ai->shouldReceive( 'get_client' )
			->andReturn( $chat_gpt );

		$open_ai->shouldReceive( 'get_default_args' )
			->andReturn(
				[
					'model'             => 'gpt-3.5-turbo',
					'temperature'       => 1.0,
					'max_tokens'        => 4000,
					'frequency_penalty' => 0,
					'presence_penalty'  => 0,
				]
			);

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'open_ai_token' => 'age38gegewjdhagepkhif',
				]
			);

		WP_Mock::expectFilter(
			'apbe_open_ai_system_prompt',
			'You are ChatGPT, a highly intelligent, helpful AI assistant.'
		);

		$chat_gpt->shouldReceive( 'chat' )
			->andReturn( '{"choices":[{"message":{"content":"What a Wonderful World!"}}]}' );

		WP_Mock::expectAction(
			'apbe_ai_provider_success_call',
			'What a Wonderful World!',
			'{"model":"gpt-3.5-turbo","temperature":1,"max_tokens":4000,"frequency_penalty":0,"presence_penalty":0,"messages":[{"role":"system","content":"You are ChatGPT, a highly intelligent, helpful AI assistant."},{"role":"user","content":"Generate me an SEO friendly Headline using: Hello World!"}]}',
			'OpenAI',
		);

		WP_Mock::expectFilter(
			'apbe_ai_provider_response',
			'What a Wonderful World!',
			'{"model":"gpt-3.5-turbo","temperature":1,"max_tokens":4000,"frequency_penalty":0,"presence_penalty":0,"messages":[{"role":"system","content":"You are ChatGPT, a highly intelligent, helpful AI assistant."},{"role":"user","content":"Generate me an SEO friendly Headline using: Hello World!"}]}',
			'OpenAI',
		);

		$response = $open_ai->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertSame( 'What a Wonderful World!', $response );
		$this->assertConditionsMet();
	}

	public function test_get_json_error() {
		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'API Error...',
			'[]',
			'OpenAI',
		);

		$response = $open_ai->get_json_error( 'API Error...' );

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}
}
