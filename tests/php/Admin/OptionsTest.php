<?php

namespace AiPlusBlockEditor\Tests\Admin;

use WP_Mock;
use Mockery;
use WP_Mock\Tools\TestCase;
use AiPlusBlockEditor\Admin\Options;

/**
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_page
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_submit
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_notice
 * @covers \AiPlusBlockEditor\Admin\Options::get_form_fields
 */
class OptionsTest extends TestCase {
	public function setUp(): void {
		WP_Mock::setUp();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_get_form_page() {
		WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 2,
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		$form_page = Options::get_form_page();

		$this->assertSame(
			$form_page,
			[
				'title'   => 'AI + Block Editor',
				'summary' => 'Add AI Capabilities to the WP Block Editor.',
				'slug'    => 'ai-plus-block-editor',
				'option'  => 'ai_plus_block_editor',
			]
		);
	}

	public function test_get_form_submit() {
		WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 2,
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		$form_submit = Options::get_form_submit();

		$this->assertSame(
			$form_submit,
			[
				'heading' => 'Actions',
				'button'  => [
					'name'  => 'ai_plus_block_editor_save_settings',
					'label' => 'Save Changes',
				],
				'nonce'   => [
					'name'   => 'ai_plus_block_editor_settings_nonce',
					'action' => 'ai_plus_block_editor_settings_action',
				],
			]
		);
	}

	public function test_get_form_fields() {
		WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		WP_Mock::userFunction(
			'esc_attr__',
			[
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		$form_fields = Options::get_form_fields();

		$this->assertSame(
			$form_fields,
			[
				'general_options'       => [
					'heading'  => 'General Options',
					'controls' => [
						'ai_provider' => [
							'control' => 'select',
							'label'   => 'AI Provider',
							'summary' => 'e.g. Open AI (Chat GPT)',
							'options' => [
								'OpenAI'   => 'ChatGPT',
								'Gemini'   => 'Gemini',
								'DeepSeek' => 'DeepSeek',
								'Grok'     => 'Grok',
								'Claude'   => 'Claude',
							],
						],
					],
				],
				'open_ai_options'       => [
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
				],
				'google_gemini_options' => [
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
				],
				'deepseek_options'      => [
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
				],
				'grok_options'          => [
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
				],
				'claude_options'        => [
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
				],
			]
		);
	}

	public function test_get_form_notice() {
		WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 1,
				'return' => function ( $text, $domain = 'ai-plus-block-editor' ) {
					return $text;
				},
			]
		);

		$form_notice = Options::get_form_notice();

		$this->assertSame(
			$form_notice,
			[
				'label' => 'Settings Saved.',
			]
		);
	}
}
