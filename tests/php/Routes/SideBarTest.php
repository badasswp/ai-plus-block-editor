<?php

namespace AiPlusBlockEditor\Tests\Routes;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Routes\SideBar;
use AiPlusBlockEditor\Abstracts\Service;

/**
 * @covers \AiPlusBlockEditor\Routes\SideBar::is_sql
 */
class SideBarTest extends TestCase {
	public SideBar $sidebar;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->sidebar = new SideBar();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_route_initial_values() {
		$this->assertSame( $this->sidebar->method, 'POST' );
		$this->assertSame( $this->sidebar->endpoint, '/sidebar' );
	}
}
