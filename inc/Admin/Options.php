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

use AiPlusBlockEditor\Providers\OpenAI;
use AiPlusBlockEditor\Providers\Gemini;
use AiPlusBlockEditor\Providers\DeepSeek;
use AiPlusBlockEditor\Providers\Grok;
use AiPlusBlockEditor\Providers\Claude;

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
			'open_ai_options'       => OpenAI::get_options(),
			'google_gemini_options' => Gemini::get_options(),
			'deepseek_options'      => DeepSeek::get_options(),
			'grok_options'          => Grok::get_options(),
			'claude_options'        => Claude::get_options(),
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
