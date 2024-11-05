<?php

namespace AiPlusBlockEditor\Tests\Abstracts;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Abstracts\Service;

/**
 * @covers \AiPlusBlockEditor\Abstracts\Service::__construct
 * @covers \AiPlusBlockEditor\Abstracts\Service::get_instance
 */
class ServiceTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_instance_returns_same_instance() {
		$instance1 = ConcreteService::get_instance();
		$instance2 = ConcreteService::get_instance();

		$this->assertSame( $instance1, $instance2 );
	}

	public function test_get_instance_creates_only_one_instance() {
		$instance1 = ConcreteService::get_instance();
		$instance2 = ConcreteService::get_instance();
		$instance3 = ConcreteService::get_instance();

		$this->assertSame( $instance1, $instance2 );
		$this->assertSame( $instance2, $instance3 );
	}
}

class ConcreteService extends Service {
	public function register(): void {}
}
