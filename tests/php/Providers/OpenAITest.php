<?php

namespace AiPlusBlockEditor\Tests\Providers;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Providers\OpenAI;
use Orhanerday\OpenAi\OpenAi as ChatGPT;

/**
 * @covers \AiPlusBlockEditor\Providers\OpenAI::run
 * @covers \AiPlusBlockEditor\Providers\OpenAI::get_default_args
 */
class OpenAITest extends TestCase {
	public OpenAI $open_ai;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->open_ai = new OpenAI();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_default_args() {
		\WP_Mock::expectFilter(
			'apbe_open_ai_args',
			[
				'model'             => 'gpt-3.5-turbo',
				'temperature'       => 1.0,
				'max_tokens'        => 4000,
				'frequency_penalty' => 0,
				'presence_penalty'  => 0,
			]
		);

		\WP_Mock::userFunction( 'wp_parse_args' )
			->andReturnUsing(
				function ( $arg1, $arg2 ) {
					return array_merge( $arg2, $arg1 );
				}
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

	public function test_run() {
		$open_ai = Mockery::mock( OpenAI::class )->makePartial();
		$open_ai->shouldAllowMockingProtectedMethods();

		$chat_gpt = Mockery::mock( ChatGPT::class )->makePartial();
		$chat_gpt->shouldAllowMockingProtectedMethods();

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
					'open_ai_token' => 'age38gegewjdhagepkhif',
				]
			);

		$chat_gpt->shouldReceive( 'chat' )
			->andReturn( '{"choices":[{"message":{"content":"What a Wonderful World!"}}]}' );

		$response = $open_ai->run(
			[
				'content' => 'Generate me an SEO friendly Headline using: Hello World!',
			]
		);

		$this->assertSame( $response, '' );
		$this->assertConditionsMet();
	}
}
