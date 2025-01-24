<?php

namespace AiPlusBlockEditor\Tests\Core;

use Mockery;
use WP_Mock\Tools\TestCase;

use AiPlusBlockEditor\Core\Container;
use AiPlusBlockEditor\Services\Admin;
use AiPlusBlockEditor\Services\Boot;
use AiPlusBlockEditor\Services\PostMeta;
use AiPlusBlockEditor\Services\Routes;
use AiPlusBlockEditor\Abstracts\Service;

/**
 * @covers \AiPlusBlockEditor\Core\Container::__construct
 * @covers \AiPlusBlockEditor\Core\Container::register
 * @covers \AiPlusBlockEditor\Abstracts\Service::get_instance
 * @covers \AiPlusBlockEditor\Services\Admin::register
 * @covers \AiPlusBlockEditor\Services\Boot::register
 * @covers \AiPlusBlockEditor\Services\Routes::register
 * @covers \AiPlusBlockEditor\Services\Routes::__construct
 * @covers \AiPlusBlockEditor\Services\PostMeta::register
 * @covers \AiPlusBlockEditor\Services\PostMeta::__construct
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

	public function test_register() {
		$container = new Container();

		/**
		 * Hack around unset Service::$instances.
		 *
		 * We create instances of services so we can
		 * have a populated version of the Service abstraction's instances.
		 */
		foreach ( Container::$services as $service ) {
			$service::get_instance();
		}

		\WP_Mock::expectActionAdded(
			'admin_init',
			[
				Service::$services[ Admin::class ],
				'register_options_init',
			]
		);

		\WP_Mock::expectActionAdded(
			'admin_menu',
			[
				Service::$services[ Admin::class ],
				'register_options_menu',
			]
		);

		\WP_Mock::expectActionAdded(
			'admin_enqueue_scripts',
			[
				Service::$services[ Admin::class ],
				'register_options_styles',
			]
		);

		\WP_Mock::expectActionAdded(
			'init',
			[
				Service::$services[ Boot::class ],
				'register_translation',
			]
		);

		\WP_Mock::expectActionAdded(
			'enqueue_block_editor_assets',
			[
				Service::$services[ Boot::class ],
				'register_scripts',
			]
		);

		\WP_Mock::expectActionAdded(
			'init',
			[
				Service::$services[ PostMeta::class ],
				'register_post_meta',
			]
		);

		\WP_Mock::expectActionAdded(
			'rest_api_init',
			[
				Service::$services[ Routes::class ],
				'register_rest_routes',
			]
		);

		$container->register();

		$this->assertConditionsMet();
	}
}
