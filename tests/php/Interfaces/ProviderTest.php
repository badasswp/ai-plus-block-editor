<?php

namespace AiPlusBlockEditor\Tests\Interfaces;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Interfaces\Provider;

/**
 * @covers \AiPlusBlockEditor\Interfaces\Provider::run
 */
class ProviderTest extends TestCase {
	public Provider $provider;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->provider = $this->getMockForAbstractClass( Provider::class );
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_run() {
		$this->provider->expects( $this->once() )
			->method( 'run' );

		$this->provider->run( [] );

		$this->assertConditionsMet();
	}
}
