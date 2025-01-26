<?php

namespace AiPlusBlockEditor\Tests\Routes;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Routes\Tone;
use AiPlusBlockEditor\Abstracts\Service;

/**
 * @covers \AiPlusBlockEditor\Routes\Tone::response
 * @covers \AiPlusBlockEditor\Routes\Tone::get_400_response
 */
class ToneTest extends TestCase {
	public Tone $tone;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->tone = new Tone();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_route_initial_values() {
		$this->assertSame( $this->tone->method, 'POST' );
		$this->assertSame( $this->tone->endpoint, '/tone' );
	}

	public function test_response_bails_out_if_no_prompt_text() {
		$tone = Mockery::mock( Tone::class )->makePartial();
		$tone->shouldAllowMockingProtectedMethods();

		$request = Mockery::mock( \WP_REST_Request::class )->makePartial();
		$request->shouldAllowMockingProtectedMethods();

		$request->shouldReceive( 'get_json_params' )
			->andReturn(
				[
					'text' => '',
				]
			);

		$tone->request = $request;

		$this->assertInstanceOf( \WP_Error::class, $tone->response() );
		$this->assertConditionsMet();
	}

	public function test_response_passes() {
		$tone = Mockery::mock( Tone::class )->makePartial();
		$tone->shouldAllowMockingProtectedMethods();

		$request = Mockery::mock( \WP_REST_Request::class )->makePartial();
		$request->shouldAllowMockingProtectedMethods();

		$request->shouldReceive( 'get_json_params' )
			->andReturn(
				[
					'text' => 'Generate an SEO friendly Headline for: Hello World!',
				]
			);

		$tone->shouldReceive( 'get_response' )
			->andReturn( 'What a Wonderful World!' );

		$tone->request = $request;

		$this->assertInstanceOf( \WP_REST_Response::class, $tone->response() );
		$this->assertConditionsMet();
	}
}
