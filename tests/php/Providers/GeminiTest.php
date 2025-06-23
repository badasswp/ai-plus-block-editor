<?php

namespace AiPlusBlockEditor\Tests\Providers;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Providers\Gemini;

/**
 * @covers \AiPlusBlockEditor\Providers\Gemini::run
 * @covers \AiPlusBlockEditor\Providers\Gemini::get_default_args
 * @covers \AiPlusBlockEditor\Providers\Gemini::get_json_error
 * @covers \AiPlusBlockEditor\Admin\Options::__callStatic
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_fields
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_notice
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_page
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_submit
 * @covers \AiPlusBlockEditor\Admin\Options::init
 */
class GeminiTest extends TestCase {
	public Gemini $gemini;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->gemini = new Gemini();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_default_args() {
		\WP_Mock::expectFilter(
			'apbe_gemini_args',
			[
				'model'           => 'gemini-2.0-flash',
				'temperature'     => 1.0,
				'maxOutputTokens' => 256,
				'topK'            => 40,
				'topP'            => 0.95,
				'stopSequences'   => [ "\n\n" ],
			]
		);

		\WP_Mock::userFunction( 'wp_parse_args' )
			->andReturnUsing(
				function ( $arg1, $arg2 ) {
					return array_merge( $arg2, $arg1 );
				}
			);

		$reflection = new \ReflectionClass( $this->gemini );
		$method     = $reflection->getMethod( 'get_default_args' );

		$method->setAccessible( true );
		$args = $method->invoke( $this->gemini );

		$this->assertSame(
			$args,
			[
				'model'           => 'gemini-2.0-flash',
				'temperature'     => 1.0,
				'maxOutputTokens' => 256,
				'topK'            => 40,
				'topP'            => 0.95,
				'stopSequences'   => [ "\n\n" ],
			]
		);
	}

	public function test_get_api_url() {
		$gemini = Mockery::mock( Gemini::class )->makePartial();
		$gemini->shouldAllowMockingProtectedMethods();

		$gemini->shouldReceive( 'get_default_args' )
			->andReturn(
				[
					'model' => 'gemini-2.0-flash',
				]
			);

		\WP_Mock::expectFilter(
			'apbe_gemini_api_url',
			'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent'
		);

		\WP_Mock::userFunction( 'esc_url' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		$url = $gemini->get_api_url();

		$this->assertSame( $url, 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent' );
		$this->assertConditionsMet();
	}

	public function test_run_fails_if_missing_api_keys_and_returns_wp_error() {
		$gemini = Mockery::mock( Gemini::class )->makePartial();
		$gemini->shouldAllowMockingProtectedMethods();

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
					'google_gemini_token' => '',
				]
			);

		$response = $gemini->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run_fails_if_missing_prompt_text_and_returns_wp_error() {
		$gemini = Mockery::mock( Gemini::class )->makePartial();
		$gemini->shouldAllowMockingProtectedMethods();

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
					'google_gemini_token' => 'age38gegewjdhagepkhif',
				]
			);

		$response = $gemini->run(
			[
				'content' => '',
			]
		);

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run() {
		$gemini = Mockery::mock( Gemini::class )->makePartial();
		$gemini->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		$gemini->shouldReceive( 'get_default_args' )
			->andReturn(
				[
					'model'           => 'gemini-2.0-flash',
					'temperature'     => 1.0,
					'maxOutputTokens' => 256,
					'topK'            => 40,
					'topP'            => 0.95,
					'stopSequences'   => [ "\n\n" ],
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
					'google_gemini_token' => 'age38gegewjdhagepkhif',
				]
			);

		\WP_Mock::userFunction( 'add_query_arg' )
			->andReturnNull();

		$gemini->shouldReceive( 'get_api_url' )
		->andReturn( '' );

		\WP_Mock::userFunction( 'wp_remote_post' )
			->andReturn( '{"body":{"candidates":[{"content":{"parts":[{"text":"What a Wonderful World!"}]}}]}}' );

		\WP_Mock::userFunction( 'wp_remote_retrieve_body' )
			->andReturn( '{"candidates":[{"content":{"parts":[{"text":"What a Wonderful World!"}]}}]}' );

		$response = $gemini->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertSame( 'What a Wonderful World!', $response );
		$this->assertConditionsMet();
	}

	public function test_get_json_error() {
		$gemini = Mockery::mock( Gemini::class )->makePartial();
		$gemini->shouldAllowMockingProtectedMethods();

		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		$response = $gemini->get_json_error( 'API Error...' );

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}
}
