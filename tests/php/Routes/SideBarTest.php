<?php

namespace AiPlusBlockEditor\Tests\Routes;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Routes\SideBar;
use AiPlusBlockEditor\Abstracts\Service;

/**
 * @covers \AiPlusBlockEditor\Routes\SideBar::response
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

	public function test_response_bails_out_if_no_prompt_text() {
		$sidebar = Mockery::mock( SideBar::class )->makePartial();
		$sidebar->shouldAllowMockingProtectedMethods();

		$request = Mockery::mock( \WP_REST_Request::class )->makePartial();
		$request->shouldAllowMockingProtectedMethods();

		$request->shouldReceive( 'get_json_params' )
			->andReturn(
				[
					'text' => '',
				]
			);

		$sidebar->request = $request;

		$this->assertInstanceOf( \WP_Error::class, $sidebar->response() );
		$this->assertConditionsMet();
	}

	public function test_response_passes() {
		$sidebar = Mockery::mock( SideBar::class )->makePartial();
		$sidebar->shouldAllowMockingProtectedMethods();

		$request = Mockery::mock( \WP_REST_Request::class )->makePartial();
		$request->shouldAllowMockingProtectedMethods();

		$request->shouldReceive( 'get_json_params' )
			->andReturn(
				[
					'text' => 'Generate an SEO friendly Headline for: Hello World!',
				]
			);

		$sidebar->shouldReceive( 'get_response' )
			->andReturn( 'What a Wonderful World!' );

		$sidebar->request = $request;

		$this->assertInstanceOf( \WP_REST_Response::class, $sidebar->response() );
		$this->assertConditionsMet();
	}
}
