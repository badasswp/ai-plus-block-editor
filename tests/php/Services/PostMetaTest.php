<?php

namespace AiPlusBlockEditor\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Services\PostMeta;

/**
 * @covers \AiPlusBlockEditor\Services\PostMeta::__construct
 * @covers \AiPlusBlockEditor\Services\PostMeta::register
 * @covers \AiPlusBlockEditor\Services\PostMeta::register_post_meta
 */
class PostMetaTest extends TestCase {
	public PostMeta $post_meta;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->post_meta = new PostMeta();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'init', [ $this->post_meta, 'register_post_meta' ] );

		$this->post_meta->register();

		$this->assertConditionsMet();
	}

	public function test_register_post_meta() {
		\WP_Mock::onFilter( 'apbe_post_meta' )
			->with(
				[
					'apbe_headline',
					'apbe_seo_keywords',
					'apbe_slug',
					'apbe_summary',
					'apbe_social',
				]
			)
			->reply(
				[
					'apbe_summary',
				]
			);

		\WP_Mock::userFunction( 'register_post_meta' )
			->with(
				'',
				'apbe_summary',
				[
					'single'            => true,
					'show_in_rest'      => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				]
			)
			->andReturn( null );

		$this->post_meta->register_post_meta();

		$this->assertConditionsMet();
	}
}
