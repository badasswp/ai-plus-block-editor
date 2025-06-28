<?php

namespace AiPlusBlockEditor\Tests\Providers;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Providers\DeepSeek;

/**
 * @covers \AiPlusBlockEditor\Providers\DeepSeek::run
 * @covers \AiPlusBlockEditor\Providers\DeepSeek::get_api_url
 * @covers \AiPlusBlockEditor\Providers\DeepSeek::get_default_args
 * @covers \AiPlusBlockEditor\Providers\DeepSeek::get_json_error
 * @covers \AiPlusBlockEditor\Admin\Options::__callStatic
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_fields
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_notice
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_page
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_submit
 * @covers \AiPlusBlockEditor\Admin\Options::init
 */
class DeepSeekTest extends TestCase {
	public DeepSeek $deepseek;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->deepseek = new DeepSeek();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_default_args() {
		\WP_Mock::expectFilter(
			'apbe_deepseek_args',
			[
				'model'             => 'deepseek-chat',
				'temperature'       => 0.7,
				'top_p'             => 1,
				'max_tokens'        => 500,
				'presence_penalty'  => 0,
				'frequency_penalty' => 0,
			]
		);

		\WP_Mock::userFunction( 'wp_parse_args' )
			->andReturnUsing(
				function ( $arg1, $arg2 ) {
					return array_merge( $arg2, $arg1 );
				}
			);

		$reflection = new \ReflectionClass( $this->deepseek );
		$method     = $reflection->getMethod( 'get_default_args' );

		$method->setAccessible( true );
		$args = $method->invoke( $this->deepseek );

		$this->assertSame(
			$args,
			[
				'model'             => 'deepseek-chat',
				'temperature'       => 0.7,
				'top_p'             => 1,
				'max_tokens'        => 500,
				'presence_penalty'  => 0,
				'frequency_penalty' => 0,
			]
		);
	}

	public function test_get_api_url() {
		$deepseek = Mockery::mock( DeepSeek::class )->makePartial();
		$deepseek->shouldAllowMockingProtectedMethods();

		\WP_Mock::expectFilter(
			'apbe_deepseek_api_url',
			'https://api.deepseek.com/chat/completions'
		);

		\WP_Mock::userFunction( 'esc_url' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		$url = $deepseek->get_api_url();

		$this->assertSame( $url, 'https://api.deepseek.com/chat/completions' );
		$this->assertConditionsMet();
	}

	public function test_run_fails_if_missing_api_keys_and_returns_wp_error() {
		$deepseek = Mockery::mock( DeepSeek::class )->makePartial();
		$deepseek->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

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

		\WP_Mock::userFunction( '__' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		\WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'deepseek_token' => '',
				]
			);

		$response = $deepseek->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run_fails_if_missing_prompt_text_and_returns_wp_error() {
		$deepseek = Mockery::mock( DeepSeek::class )->makePartial();
		$deepseek->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

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

		\WP_Mock::userFunction( '__' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		\WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'deepseek_token' => 'age38gegewjdhagepkhif',
				]
			);

		$response = $deepseek->run(
			[
				'content' => '',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run() {
		$deepseek = Mockery::mock( DeepSeek::class )->makePartial();
		$deepseek->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		$deepseek->shouldReceive( 'get_default_args' )
			->andReturn(
				[
					'model'             => 'deepseek-chat',
					'temperature'       => 0.7,
					'top_p'             => 1,
					'max_tokens'        => 500,
					'presence_penalty'  => 0,
					'frequency_penalty' => 0,
				]
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

		\WP_Mock::userFunction( 'is_wp_error' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg instanceof \WP_Error;
				}
			);

		\WP_Mock::userFunction( 'wp_json_encode' )
			->andReturnUsing(
				function ( $arg ) {
					return json_encode( $arg );
				}
			);

		\WP_Mock::userFunction( 'wp_parse_args' )
			->andReturnUsing(
				function ( $arg1, $arg2 ) {
					return array_merge( $arg2, $arg1 );
				}
			);

		\WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'deepseek_token' => 'age38gegewjdhagepkhif',
				]
			);

		\WP_Mock::userFunction( 'add_query_arg' )
			->andReturnNull();

		$deepseek->shouldReceive( 'get_api_url' )
		->andReturn( '' );

		\WP_Mock::userFunction( 'wp_remote_post' )
			->andReturn( '{"body":{"choices":[{"message":{"content":"What a Wonderful World!"}}]}}' );

		\WP_Mock::userFunction( 'wp_remote_retrieve_body' )
			->andReturn( '{"choices":[{"message":{"content":"What a Wonderful World!"}}]}' );

		$response = $deepseek->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertSame( 'What a Wonderful World!', $response );
		$this->assertConditionsMet();
	}

	public function test_get_json_error() {
		$deepseek = Mockery::mock( DeepSeek::class )->makePartial();
		$deepseek->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		$response = $deepseek->get_json_error( 'API Error...' );

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}
}
