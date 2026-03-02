<?php

namespace AiPlusBlockEditor\Tests\Abstracts;

use WP_Mock;
use Mockery;
use Badasswp\WPMockTC\WPMockTestCase;
use AiPlusBlockEditor\Abstracts\Provider;

/**
 * @covers \AiPlusBlockEditor\Abstracts\Provider::get_default_args
 * @covers \AiPlusBlockEditor\Abstracts\Provider::get_provider_response
 * @covers \AiPlusBlockEditor\Abstracts\Provider::get_json_error
 * @covers \AiPlusBlockEditor\Abstracts\Provider::run
 */
class ProviderTest extends WPMockTestCase {
	public Provider $provider;

	public function setUp(): void {
		parent::setUp();

		$this->provider = Mockery::mock( ConcreteProvider::class )->makePartial();
		$this->provider->shouldAllowMockingProtectedMethods();
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	public function test_get_default_args() {
		$response = $this->provider->get_default_args();

		$this->assertSame(
			[
				'model' => 'ai-model',
			],
			$response
		);
	}

	public function test_get_provider_response() {
		$payload = [
			'model'  => 'ai-model',
			'tokens' => 512,
			'stream' => false,
		];

		WP_Mock::expectAction(
			'apbe_ai_provider_success_call',
			'What a Wonderful World!',
			wp_json_encode( $payload ),
			'AI Provider',
		);

		WP_Mock::expectFilter(
			'apbe_ai_provider_response',
			'What a Wonderful World!',
			wp_json_encode( $payload ),
			'AI Provider',
		);

		$response = $this->provider->get_provider_response( 'What a Wonderful World!', wp_json_encode( $payload ) );

		$this->assertSame( 'What a Wonderful World!', $response );
		$this->assertConditionsMet();
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
				'content' => 'Generate me a beautiful headline for my Graduation!',
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

	public function run( $payload ) {
		echo wp_json_encode( $payload );
	}

	protected function get_default_args(): array {
		return [
			'model' => 'ai-model',
		];
	}
}
