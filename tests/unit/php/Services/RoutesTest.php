<?php

namespace AiPlusBlockEditor\Tests\Services;

use WP_Mock;
use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Routes\Tone;
use AiPlusBlockEditor\Routes\SideBar;
use AiPlusBlockEditor\Routes\Switcher;
use AiPlusBlockEditor\Services\Routes;

/**
 * @covers \AiPlusBlockEditor\Services\Routes::__construct
 * @covers \AiPlusBlockEditor\Services\Routes::register
 * @covers \AiPlusBlockEditor\Services\Routes::register_rest_routes
 * @covers \AiPlusBlockEditor\Abstracts\Route::register_route
 */
class RoutesTest extends TestCase {
	public Routes $routes;

	public function setUp(): void {
		WP_Mock::setUp();

		$this->routes = new Routes();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_register() {
		WP_Mock::expectActionAdded( 'rest_api_init', [ $this->routes, 'register_rest_routes' ] );

		$this->routes->register();

		$this->assertConditionsMet();
	}

	public function test_register_rest_routes() {
		WP_Mock::onFilter( 'apbe_rest_routes' )
			->with(
				[
					Tone::class,
					SideBar::class,
					Switcher::class,
				]
			)
			->reply(
				[
					SideBar::class,
				]
			);

		$sidebar = new SideBar();

		WP_Mock::userFunction( 'register_rest_route' )
			->with(
				'ai-plus-block-editor/v1',
				'/sidebar',
				[
					'methods'             => 'POST',
					'callback'            => [ $sidebar, 'request' ],
					'permission_callback' => [ $sidebar, 'is_user_permissible' ],
				]
			)
			->andReturn( null );

		$this->routes->register_rest_routes();

		$this->assertConditionsMet();
	}
}
