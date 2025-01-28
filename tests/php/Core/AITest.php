<?php

namespace AiPlusBlockEditor\Tests\Core;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Core\AI;
use AiPlusBlockEditor\Providers\OpenAI;

/**
 * @covers \AiPlusBlockEditor\Core\AI::run
 * @covers \AiPlusBlockEditor\Core\AI::get_instance
 * @covers \AiPlusBlockEditor\Core\AI::get_provider
 */
class AITest extends TestCase {
	public AI $ai;

	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_provider() {
		\WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'ai_provider' => 'OpenAI',
				]
			);

		$ai = Mockery::mock( AI::class )->makePartial();
		$ai->shouldAllowMockingProtectedMethods();

		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		$ai->shouldReceive( 'get_instance' )
			->with( Mockery::type( OpenAI::class ) )
			->andReturn( $open_ai );

		$provider = $ai->get_provider();

		$this->assertInstanceOf( OpenAI::class, $provider );
		$this->assertConditionsMet();
	}

	public function test_get_instance() {
		$ai = Mockery::mock( AI::class )->makePartial();
		$ai->shouldAllowMockingProtectedMethods();

		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		\WP_Mock::expectFilter( 'apbe_ai_provider', $open_ai );

		$instance = $ai->get_instance( $open_ai );

		$this->assertSame( $open_ai, $instance );
		$this->assertConditionsMet();
	}

	public function test_run_passes_and_returns_ai_generated_string() {
		$ai = Mockery::mock( AI::class )->makePartial();
		$ai->shouldAllowMockingProtectedMethods();

		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		$ai->shouldReceive( 'get_provider' )
			->andReturn( $open_ai );

		$open_ai->shouldReceive( 'run' )
			->times( 1 )
			->with(
				[
					'content' => 'Generate an SEO friendly headline using: Hello World!',
				]
			)
			->andReturn(
				'What a Wonderful World! Generated by Open AI...'
			);

		$response = $ai->run(
			[
				'content' => 'Generate an SEO friendly headline using: Hello World!',
			]
		);

		$this->assertSame( $response, 'What a Wonderful World! Generated by Open AI...' );
		$this->assertConditionsMet();
	}

	public function test_run_throws_exception_and_returns_wp_error() {
		$ai = Mockery::mock( AI::class )->makePartial();
		$ai->shouldAllowMockingProtectedMethods();

		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		$ai->shouldReceive( 'get_provider' )
			->andReturn( $open_ai );

		$open_ai->shouldReceive( 'run' )
			->times( 1 )
			->with(
				[
					'content' => 'Do nothing...',
				]
			)
			->andThrow(
				new \Exception( 'ChatGPT API is currently down and rate limiting is now in effect...' )
			);

		$wp_error = $ai->run( [ 'content' => 'Do nothing...' ] );

		$this->assertInstanceOf( \WP_Error::class, $wp_error );
		$this->assertConditionsMet();
	}
}
