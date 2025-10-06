<?php

namespace AiPlusBlockEditor\Tests\Routes;

use WP_Mock;
use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Core\AI;
use AiPlusBlockEditor\Routes\Switcher;

/**
 * @covers \AiPlusBlockEditor\Routes\Switcher::response
 * @covers \AiPlusBlockEditor\Routes\Switcher::get_400_response
 * @covers \AiPlusBlockEditor\Admin\Options::__callStatic
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_fields
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_notice
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_page
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_submit
 * @covers \AiPlusBlockEditor\Admin\Options::init
 */
class SwitcherTest extends TestCase {
	public Switcher $switcher;

	public function setUp(): void {
		WP_Mock::setUp();

		$this->switcher = new Switcher();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_route_initial_values() {
		$this->assertSame( $this->switcher->method, 'POST' );
		$this->assertSame( $this->switcher->endpoint, '/switcher' );
	}

	public function test_response_bails_out_if_provider_is_empty() {
		$switcher = Mockery::mock( Switcher::class )->makePartial();
		$switcher->shouldAllowMockingProtectedMethods();

		$request = Mockery::mock( \WP_REST_Request::class )->makePartial();
		$request->shouldAllowMockingProtectedMethods();

		$request->shouldReceive( 'get_json_params' )
			->andReturn(
				[
					'provider' => '',
				]
			);

		$switcher->request = $request;

		$this->assertInstanceOf( \WP_Error::class, $switcher->response() );
		$this->assertConditionsMet();
	}

	public function test_provider_is_updated_and_returns_rest_response() {
		$switcher = Mockery::mock( Switcher::class )->makePartial();
		$switcher->shouldAllowMockingProtectedMethods();

		$request = Mockery::mock( \WP_REST_Request::class )->makePartial();
		$request->shouldAllowMockingProtectedMethods();

		$request->shouldReceive( 'get_json_params' )
			->andReturn(
				[
					'provider' => 'Gemini',
				]
			);

		$switcher->request = $request;

		WP_Mock::userFunction( 'wp_parse_args' )
			->andReturnUsing(
				function ( $arg1, $arg2 ) {
					return array_merge( $arg2, $arg1 );
				}
			);

		WP_Mock::userFunction( 'get_option' )
			->with( 'ai_plus_block_editor', [] )
			->andReturn(
				[
					'ai_provider'          => 'OpenAI',
					'open_ai_enable'       => true,
					'open_ai_token'        => 'afkqy4iew9bf',
					'google_gemini_enable' => false,
					'google_gemini_token'  => '',
				]
			);

		WP_Mock::userFunction( 'sanitize_text_field' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		WP_Mock::userFunction( 'esc_html__' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		WP_Mock::userFunction( 'esc_attr' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		WP_Mock::userFunction( 'update_option' )
			->with(
				'ai_plus_block_editor',
				[
					'ai_provider'          => 'Gemini',
					'open_ai_enable'       => true,
					'open_ai_token'        => 'afkqy4iew9bf',
					'google_gemini_enable' => false,
					'google_gemini_token'  => '',
				]
			);

		WP_Mock::userFunction( 'rest_ensure_response' )
			->andReturnUsing(
				function ( $arg ) {
					if ( $arg instanceof \WP_Error ) {
						return $arg;
					}

					if ( is_array( $arg ) ) {
						return Mockery::mock( \WP_REST_Response::class )->makePartial();
					}

					return null;
				}
			);

		$this->assertInstanceOf( \WP_REST_Response::class, $switcher->response() );
		$this->assertConditionsMet();
	}
}
