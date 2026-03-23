<?php

namespace AiPlusBlockEditor\Tests;

use WP_Mock;
use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Plugin;
use AiPlusBlockEditor\Abstracts\Kernel;

/**
 * @covers \AiPlusBlockEditor\Plugin::get_instance
 */
class PluginTest extends TestCase {
	public function setUp(): void {
		WP_Mock::setUp();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_plugin_returns_same_instance() {
		$instance1 = Plugin::get_instance();
		$instance2 = Plugin::get_instance();

		$this->assertSame( $instance1, $instance2 );
		$this->assertConditionsMet();
	}

	public static function mock_llm_options() {
		WP_Mock::expectFilter(
			'apbe_open_ai_options',
			[
				'heading'  => 'Open AI',
				'controls' => [
					'open_ai_enable' => [
						'control' => 'checkbox',
						'label'   => 'Enable Open AI',
						'summary' => 'Use Chat GPT capabilities in Block Editor',
					],
					'open_ai_token'  => [
						'control'     => 'password',
						'placeholder' => '',
						'label'       => 'API Keys',
						'summary'     => 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av',
					],
				],
			]
		);

		WP_Mock::expectFilter(
			'apbe_gemini_options',
			[
				'heading'  => 'Google Gemini',
				'controls' => [
					'google_gemini_enable' => [
						'control' => 'checkbox',
						'label'   => 'Enable Google Gemini',
						'summary' => 'Use Google Gemini capabilities in Block Editor',
					],
					'google_gemini_token'  => [
						'control'     => 'password',
						'placeholder' => '',
						'label'       => 'API Keys',
						'summary'     => 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av',
					],
				],
			]
		);

		WP_Mock::expectFilter(
			'apbe_deepseek_options',
			[
				'heading'  => 'DeepSeek',
				'controls' => [
					'deepseek_enable' => [
						'control' => 'checkbox',
						'label'   => 'Enable DeepSeek',
						'summary' => 'Use DeepSeek capabilities in Block Editor',
					],
					'deepseek_token'  => [
						'control'     => 'password',
						'placeholder' => '',
						'label'       => 'API Keys',
						'summary'     => 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av',
					],
				],
			]
		);

		WP_Mock::expectFilter(
			'apbe_grok_options',
			[
				'heading'  => 'Grok',
				'controls' => [
					'grok_enable' => [
						'control' => 'checkbox',
						'label'   => 'Enable Grok',
						'summary' => 'Use Grok capabilities in Block Editor',
					],
					'grok_token'  => [
						'control'     => 'password',
						'placeholder' => '',
						'label'       => 'API Keys',
						'summary'     => 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av',
					],
				],
			]
		);

		WP_Mock::expectFilter(
			'apbe_claude_options',
			[
				'heading'  => 'Claude',
				'controls' => [
					'claude_enable' => [
						'control' => 'checkbox',
						'label'   => 'Enable Claude',
						'summary' => 'Use Claude capabilities in Block Editor',
					],
					'claude_token'  => [
						'control'     => 'password',
						'placeholder' => '',
						'label'       => 'API Keys',
						'summary'     => 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av',
					],
				],
			]
		);
	}
}
