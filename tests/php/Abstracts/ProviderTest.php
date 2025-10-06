<?php

namespace AiPlusBlockEditor\Tests\Abstracts;

use WP_Mock;
use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Abstracts\Provider;

/**
 * @covers \AiPlusBlockEditor\Abstracts\Provider::get_default_args
 * @covers \AiPlusBlockEditor\Abstracts\Provider::get_json_error
 * @covers \AiPlusBlockEditor\Abstracts\Provider::run
 */
class ProviderTest extends TestCase {
	public Provider $provider;

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

		$this->provider = Mockery::mock( ConcreteProvider::class )->makePartial();
		$this->provider->shouldAllowMockingProtectedMethods();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_get_default_args() {
		$response = $this->provider->get_default_args();

		$this->assertSame(
			[
				'model' => 'ai_model',
			],
			$response
		);
	}

	public function test_get_json_error() {
		$wp_error = Mockery::mock( \WP_Error::class )->makePartial();
		$wp_error->shouldAllowMockingProtectedMethods();

		WP_Mock::expectAction(
			'apbe_ai_provider_fail_call',
			'API Error...',
			'[]',
			'AI Provider',
		);

		$response = $this->provider->get_json_error( 'API Error...' );

		$this->assertInstanceOf( \WP_Error::class, $response );
		$this->assertConditionsMet();
	}

	public function test_run() {
		$this->provider->run(
			[
				'content' => 'Generate me a beautiful headline for my Graduation!'
			]
		);

		$this->expectOutputString(
			'{"content":"Generate me a beautiful headline for my Graduation!"}'
		);
		$this->assertConditionsMet();
	}
}

class ConcreteProvider extends Provider {
	protected static $name = 'AI Provider';

	protected function get_default_args() {
		return [
			'model' => 'ai_model',
		];
	}

	public function run( $payload ) {
		echo wp_json_encode( $payload );
	}
}
