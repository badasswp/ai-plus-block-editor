<?php

namespace AiPlusBlockEditor\Tests\Routes;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Core\AI;
use AiPlusBlockEditor\Routes\SideBar;

/**
 * @covers \AiPlusBlockEditor\Routes\SideBar::response
 * @covers \AiPlusBlockEditor\Routes\SideBar::get_400_response
 * @covers \AiPlusBlockEditor\Routes\SideBar::get_response
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

		$this->assertSame( 'What a Wonderful World!', $sidebar->response() );
		$this->assertConditionsMet();
	}

	public function test_get_response_passes_with_headline_feature() {
		$sidebar = Mockery::mock( SideBar::class )->makePartial();
		$sidebar->shouldAllowMockingProtectedMethods();

		$ai = Mockery::mock( AI::class )->makePartial();
		$ai->shouldAllowMockingProtectedMethods();

		$sidebar->args = [
			'feature' => 'headline',
			'text'    => 'Hello World!',
		];

		\WP_Mock::expectFilter(
			'apbe_feature_prompt',
			'Generate an appropriate headline in 1 paragraph, using the following content: Hello World!',
			'headline',
			'Hello World!'
		);

		\WP_Mock::userFunction( 'rest_ensure_response' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		$ai->shouldReceive( 'run' )
			->with(
				[
					'content' => 'Generate an appropriate headline in 1 paragraph, using the following content: Hello World!',
				]
			)
			->andReturn( 'What a Wonderful World!' );

		$sidebar->ai = $ai;

		$response = $sidebar->get_response();

		$this->assertSame( $response, 'What a Wonderful World!' );
		$this->assertConditionsMet();
	}

	public function test_get_response_passes_with_slug_feature() {
		$sidebar = Mockery::mock( SideBar::class )->makePartial();
		$sidebar->shouldAllowMockingProtectedMethods();

		$ai = Mockery::mock( AI::class )->makePartial();
		$ai->shouldAllowMockingProtectedMethods();

		$sidebar->args = [
			'feature' => 'slug',
			'text'    => 'Hello World!',
		];

		\WP_Mock::expectFilter(
			'apbe_feature_prompt',
			'Generate an appropriate slug that can be found easily by search engines, using the following content: Hello World!',
			'slug',
			'Hello World!'
		);

		\WP_Mock::userFunction( 'rest_ensure_response' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		$ai->shouldReceive( 'run' )
			->with(
				[
					'content' => 'Generate an appropriate slug that can be found easily by search engines, using the following content: Hello World!',
				]
			)
			->andReturn( 'What a Wonderful World!' );

		$sidebar->ai = $ai;

		$response = $sidebar->get_response();

		$this->assertSame( $response, 'What a Wonderful World!' );
		$this->assertConditionsMet();
	}

	public function test_get_response_passes_with_keywords_feature() {
		$sidebar = Mockery::mock( SideBar::class )->makePartial();
		$sidebar->shouldAllowMockingProtectedMethods();

		$ai = Mockery::mock( AI::class )->makePartial();
		$ai->shouldAllowMockingProtectedMethods();

		$sidebar->args = [
			'feature' => 'keywords',
			'text'    => 'Hello World!',
		];

		\WP_Mock::expectFilter(
			'apbe_feature_prompt',
			'Generate appropriate keywords that are SEO friendly and separated with commas, using the following content: Hello World!',
			'keywords',
			'Hello World!'
		);

		\WP_Mock::userFunction( 'rest_ensure_response' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		$ai->shouldReceive( 'run' )
			->with(
				[
					'content' => 'Generate appropriate keywords that are SEO friendly and separated with commas, using the following content: Hello World!',
				]
			)
			->andReturn( 'What a Wonderful World!' );

		$sidebar->ai = $ai;

		$response = $sidebar->get_response();

		$this->assertSame( $response, 'What a Wonderful World!' );
		$this->assertConditionsMet();
	}

	public function test_get_response_passes_with_summary_feature() {
		$sidebar = Mockery::mock( SideBar::class )->makePartial();
		$sidebar->shouldAllowMockingProtectedMethods();

		$ai = Mockery::mock( AI::class )->makePartial();
		$ai->shouldAllowMockingProtectedMethods();

		$sidebar->args = [
			'feature' => 'summary',
			'text'    => 'Hello World!',
		];

		\WP_Mock::expectFilter(
			'apbe_feature_prompt',
			'Generate an appropriate summary for the following content: Hello World!',
			'summary',
			'Hello World!'
		);

		\WP_Mock::userFunction( 'rest_ensure_response' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		$ai->shouldReceive( 'run' )
			->with(
				[
					'content' => 'Generate an appropriate summary for the following content: Hello World!',
				]
			)
			->andReturn( 'What a Wonderful World!' );

		$sidebar->ai = $ai;

		$response = $sidebar->get_response();

		$this->assertSame( $response, 'What a Wonderful World!' );
		$this->assertConditionsMet();
	}

	public function test_get_response_passes_with_social_media_hashtag_feature() {
		$sidebar = Mockery::mock( SideBar::class )->makePartial();
		$sidebar->shouldAllowMockingProtectedMethods();

		$ai = Mockery::mock( AI::class )->makePartial();
		$ai->shouldAllowMockingProtectedMethods();

		$sidebar->args = [
			'feature' => 'social',
			'text'    => 'Hello World!',
		];

		\WP_Mock::expectFilter(
			'apbe_feature_prompt',
			'Generate appropriate social media trending hashtags for the following content: Hello World!',
			'social',
			'Hello World!'
		);

		\WP_Mock::userFunction( 'rest_ensure_response' )
			->andReturnUsing(
				function ( $arg ) {
					return $arg;
				}
			);

		$ai->shouldReceive( 'run' )
			->with(
				[
					'content' => 'Generate appropriate social media trending hashtags for the following content: Hello World!',
				]
			)
			->andReturn( '#hello, #world' );

		$sidebar->ai = $ai;

		$response = $sidebar->get_response();

		$this->assertSame( $response, '#hello, #world' );
		$this->assertConditionsMet();
	}
}
