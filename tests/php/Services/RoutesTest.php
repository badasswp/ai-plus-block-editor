<?php

namespace AiPlusBlockEditor\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Services\Routes;
use AiPlusBlockEditor\Abstracts\Service;

/**
 * @covers \AiPlusBlockEditor\Services\Routes::__construct
 * @covers \AiPlusBlockEditor\Services\Routes::register
 */
class RoutesTest extends TestCase {
	public Routes $routes;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->routes = new Routes();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'rest_api_init', [ $this->routes, 'register_rest_routes' ] );

		$this->routes->register();

		$this->assertConditionsMet();
	}
}
