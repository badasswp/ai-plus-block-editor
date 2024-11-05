<?php

namespace AiPlusBlockEditor\Tests\Core;

use Mockery;
use WP_Mock\Tools\TestCase;

use AiPlusBlockEditor\Core\Container;
use AiPlusBlockEditor\Services\Admin;
use AiPlusBlockEditor\Services\Boot;
use AiPlusBlockEditor\Services\Routes;

/**
 * @covers \AiPlusBlockEditor\Core\Container::__construct
 */
class ContainerTest extends TestCase {
	public Container $container;

	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_container_contains_required_services() {
		$this->container = new Container();

		$this->assertTrue( in_array( Admin::class, Container::$services, true ) );
		$this->assertTrue( in_array( Boot::class, Container::$services, true ) );
		$this->assertTrue( in_array( Routes::class, Container::$services, true ) );
	}
}
