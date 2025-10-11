<?php
/**
 * Options Class.
 *
 * This class is responsible for holding the Admin
 * page options.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Admin;

use AiPlusBlockEditor\Abstracts\Provider;

class Options {
	/**
	 * The Form.
	 *
	 * This array defines every single aspect of the
	 * Form displayed on the Admin options page.
	 *
	 * @since 1.0.0
	 */
	public static array $form;

	/**
	 * Define custom static method for calling
	 * dynamic methods for e.g. Options::get_page_title().
	 *
	 * @since 1.0.0
	 *
	 * @param string  $method Method name.
	 * @param mixed[] $args   Method args.
	 *
	 * @return string|mixed[]
	 */
	public static function __callStatic( $method, $args ) {
		static::init();

		$keys = substr( $method, strpos( $method, '_' ) + 1 );
		$keys = explode( '_', $keys );

		$value = '';

		foreach ( $keys as $key ) {
			$value = empty( $value ) ? ( static::$form[ $key ] ?? '' ) : ( $value[ $key ] ?? '' );
		}

		return $value;
	}

	/**
	 * Set up Form.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init(): void {
		static::$form = [
			'page'   => static::get_form_page(),
			'notice' => static::get_form_notice(),
			'fields' => static::get_form_fields(),
			'submit' => static::get_form_submit(),
		];
	}

	/**
	 * Form Page.
	 *
	 * The Form page items containg the Page title,
	 * summary, slug and option name.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]
	 */
	public static function get_form_page(): array {
		return [
			'title'   => esc_html__(
				'AI + Block Editor',
				'ai-plus-block-editor'
			),
			'summary' => esc_html__(
				'Add AI Capabilities to the WP Block Editor.',
				'ai-plus-block-editor'
			),
			'slug'    => 'ai-plus-block-editor',
			'option'  => 'ai_plus_block_editor',
		];
	}

	/**
	 * Form Submit.
	 *
	 * The Form submit items containing the heading,
	 * button name & label and nonce params.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]
	 */
	public static function get_form_submit(): array {
		return [
			'heading' => esc_html__( 'Actions', 'ai-plus-block-editor' ),
			'button'  => [
				'name'  => 'ai_plus_block_editor_save_settings',
				'label' => esc_html__( 'Save Changes', 'ai-plus-block-editor' ),
			],
			'nonce'   => [
				'name'   => 'ai_plus_block_editor_settings_nonce',
				'action' => 'ai_plus_block_editor_settings_action',
			],
		];
	}

	/**
	 * Form Fields.
	 *
	 * The Form field items containing the heading for
	 * each group block and controls.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]
	 */
	public static function get_form_fields() {
		return [
			'general_options'       => [
				'heading'  => esc_html__( 'General Options', 'ai-plus-block-editor' ),
				'controls' => [
					'ai_provider' => [
						'control' => esc_attr( 'select' ),
						'label'   => esc_html__( 'AI Provider', 'ai-plus-block-editor' ),
						'summary' => 'e.g. Open AI (Chat GPT)',
						'options' => Provider::get_providers(),
					],
				],
			],
			'open_ai_options'       => [
				'heading'  => esc_html__( 'Open AI', 'ai-plus-block-editor' ),
				'controls' => [
					'open_ai_enable' => [
						'control' => esc_attr( 'checkbox' ),
						'label'   => esc_html__( 'Enable Open AI', 'ai-plus-block-editor' ),
						'summary' => esc_html__( 'Use Chat GPT capabilities in Block Editor', 'ai-plus-block-editor' ),
					],
					'open_ai_token'  => [
						'control'     => esc_attr( 'password' ),
						'placeholder' => esc_attr( '' ),
						'label'       => esc_html__( 'API Keys', 'ai-plus-block-editor' ),
						'summary'     => esc_html__( 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av', 'ai-plus-block-editor' ),
					],
				],
			],
			'google_gemini_options' => [
				'heading'  => esc_html__( 'Google Gemini', 'ai-plus-block-editor' ),
				'controls' => [
					'google_gemini_enable' => [
						'control' => esc_attr( 'checkbox' ),
						'label'   => esc_html__( 'Enable Google Gemini', 'ai-plus-block-editor' ),
						'summary' => esc_html__( 'Use Google Gemini capabilities in Block Editor', 'ai-plus-block-editor' ),
					],
					'google_gemini_token'  => [
						'control'     => esc_attr( 'password' ),
						'placeholder' => esc_attr( '' ),
						'label'       => esc_html__( 'API Keys', 'ai-plus-block-editor' ),
						'summary'     => esc_html__( 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av', 'ai-plus-block-editor' ),
					],
				],
			],
			'deepseek_options'      => [
				'heading'  => esc_html__( 'DeepSeek', 'ai-plus-block-editor' ),
				'controls' => [
					'deepseek_enable' => [
						'control' => esc_attr( 'checkbox' ),
						'label'   => esc_html__( 'Enable DeepSeek', 'ai-plus-block-editor' ),
						'summary' => esc_html__( 'Use DeepSeek capabilities in Block Editor', 'ai-plus-block-editor' ),
					],
					'deepseek_token'  => [
						'control'     => esc_attr( 'password' ),
						'placeholder' => esc_attr( '' ),
						'label'       => esc_html__( 'API Keys', 'ai-plus-block-editor' ),
						'summary'     => esc_html__( 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av', 'ai-plus-block-editor' ),
					],
				],
			],
			'grok_options'          => [
				'heading'  => esc_html__( 'Grok', 'ai-plus-block-editor' ),
				'controls' => [
					'grok_enable' => [
						'control' => esc_attr( 'checkbox' ),
						'label'   => esc_html__( 'Enable Grok', 'ai-plus-block-editor' ),
						'summary' => esc_html__( 'Use Grok capabilities in Block Editor', 'ai-plus-block-editor' ),
					],
					'grok_token'  => [
						'control'     => esc_attr( 'password' ),
						'placeholder' => esc_attr( '' ),
						'label'       => esc_html__( 'API Keys', 'ai-plus-block-editor' ),
						'summary'     => esc_html__( 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av', 'ai-plus-block-editor' ),
					],
				],
			],
			'claude_options'        => [
				'heading'  => esc_html__( 'Claude', 'ai-plus-block-editor' ),
				'controls' => [
					'claude_enable' => [
						'control' => esc_attr( 'checkbox' ),
						'label'   => esc_html__( 'Enable Claude', 'ai-plus-block-editor' ),
						'summary' => esc_html__( 'Use Claude capabilities in Block Editor', 'ai-plus-block-editor' ),
					],
					'claude_token'  => [
						'control'     => esc_attr( 'password' ),
						'placeholder' => esc_attr( '' ),
						'label'       => esc_html__( 'API Keys', 'ai-plus-block-editor' ),
						'summary'     => esc_html__( 'e.g. ae2kgch7ib9eqcbeveq9a923nv87392av', 'ai-plus-block-editor' ),
					],
				],
			],
		];
	}

	/**
	 * Form Notice.
	 *
	 * The Form notice containing the notice
	 * text displayed on save.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]
	 */
	public static function get_form_notice() {
		return [
			'label' => esc_html__( 'Settings Saved.', 'ai-plus-block-editor' ),
		];
	}
}
