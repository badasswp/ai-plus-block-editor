<?php
/**
 * Form Class.
 *
 * This utility class is responsible for generating
 * the Admin page form.
 *
 * @package AiPlusBlockEditor
 */

namespace AiPlusBlockEditor\Admin;

class Form {
	/**
	 * Field Options.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed[]
	 */
	protected array $options;

	/**
	 * Set up Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $options Admin Options.
	 */
	public function __construct( $options ) {
		$this->options = $options;
	}

	/**
	 * Get Options.
	 *
	 * This method grabs the Options for the Admin
	 * page, including the form.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed[]
	 */
	public function get_options(): array {
		return [
			'title'   => $this->options['page']['title'] ?? '',
			'summary' => $this->options['page']['summary'] ?? '',
			'form'    => $this->get_form(),
		];
	}

	/**
	 * Get Form.
	 *
	 * This method is responsible for getting
	 * the Form.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_form(): string {
		$form = [
			'form_action' => $this->get_form_action(),
			'form_notice' => $this->get_form_notice(),
			'form_main'   => $this->get_form_main(),
			'form_submit' => $this->get_form_submit(),
		];

		return vsprintf(
			'<form class="badasswp-form" method="POST" action="%s">
				%s
				<div class="badasswp-form-main">%s</div>
				<div class="badasswp-form-submit">%s</div>
			</form>',
			$form
		);
	}

	/**
	 * Get Form Action.
	 *
	 * This method is responsible for getting the
	 * Form Action.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_form_action(): string {
		return esc_url(
			sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) )
		);
	}

	/**
	 * Get Form Main.
	 *
	 * This method is responsible for obtaining
	 * the complete form.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_form_main(): string {
		$form_fields = '';

		/**
		 * Filter Form Fields.
		 *
		 * Pass in custom fields to the Admin Form with
		 * key, value options.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed[] $fields Form Fields.
		 * @return mixed[]
		 */
		$fields = (array) apply_filters( 'apbe_form_fields', $this->options['fields'] ?? [] );

		foreach ( $fields as $option ) {
			$form_fields .= $this->get_form_group( $option );
		}

		return $form_fields;
	}

	/**
	 * Get Form Group.
	 *
	 * This method is responsible for obtaining
	 * a single form group.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $arg Form group array.
	 * @return string
	 */
	protected function get_form_group( $arg ): string {
		$form_group = '';

		foreach ( $arg as $key => $value ) {
			switch ( $key ) {
				case 'heading':
					$form_group .= sprintf(
						'<div class="badasswp-form-group-heading">%s</div>',
						$value,
					);
					break;

				default:
					$form_group .= sprintf(
						'<div class="badasswp-form-group-body">%s</div>',
						$this->get_form_group_body( $value )
					);
					break;
			}
		}

		return sprintf( '<div class="badasswp-form-group">%s</div>', $form_group );
	}

	/**
	 * Get Form Group Body.
	 *
	 * This method is responsible for getting
	 * the form group body.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $arg Form Group Body args.
	 * @return string
	 */
	protected function get_form_group_body( $arg ): string {
		$form_group_body = '';

		foreach ( $arg as $name => $control ) {
			$group_block = [
				'label'   => $control['label'] ?? '',
				'control' => $this->get_form_control( $control, $name ),
				'summary' => $control['summary'] ?? '',
			];

			$form_group_body .= vsprintf(
				'<p class="badasswp-form-group-block">
					<label>%1$s</label>
					%2$s
					<em>%3$s</em>
				</p>',
				$group_block,
			);
		}

		return $form_group_body;
	}

	/**
	 * Get Setting.
	 *
	 * This gets the Options setting for a specific
	 * key name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Option Key name.
	 * @return string
	 */
	protected function get_setting( $name ) {
		return get_option( ( $this->options['page']['option'] ?? '' ), [] )[ $name ] ?? '';
	}

	/**
	 * Get Form Control.
	 *
	 * This method is responsible for getting the
	 * form control.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $arg  Form control array.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	protected function get_form_control( $arg, $name ): string {
		$control = '';

		switch ( $arg['control'] ?? '' ) {
			case 'text':
				$control = $this->get_text_control( $arg, $name );
				break;

			case 'password':
				$control = $this->get_password_control( $arg, $name );
				break;

			case 'select':
				$control = $this->get_select_control( $arg, $name );
				break;

			case 'checkbox':
				$control = $this->get_checkbox_control( $arg, $name );
				break;
		}

		return $control;
	}

	/**
	 * Get Text Control.
	 *
	 * This method is responsible for getting
	 * Text controls.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $arg  Text args.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	protected function get_text_control( $arg, $name ): string {
		return sprintf(
			'<input type="text" placeholder="%1$s" value="%2$s" name="%3$s"/>',
			$arg['placeholder'] ?? '',
			$this->get_setting( $name ),
			$name,
		);
	}

	/**
	 * Get Password Control.
	 *
	 * This method is responsible for getting
	 * Password controls.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $arg  Password args.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	protected function get_password_control( $arg, $name ): string {
		return sprintf(
			'<input type="password" placeholder="%1$s" value="%2$s" name="%3$s"/>',
			$arg['placeholder'] ?? '',
			$this->get_setting( $name ),
			$name,
		);
	}

	/**
	 * Get Select Control.
	 *
	 * This method is responsible for getting
	 * Select controls.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $arg  Select args.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	protected function get_select_control( $arg, $name ): string {
		$options = '';

		foreach ( $arg['options'] ?? [] as $key => $value ) {
			$is_selected = ( $this->get_setting( $name ) === $key ) ? 'selected' : '';

			$options .= sprintf(
				'<option value="%1$s" %2$s>%3$s</option>',
				$key,
				$is_selected,
				$value,
			);
		}

		return sprintf(
			'<select name="%1$s">
				%2$s
			</select>',
			$name,
			$options,
		);
	}

	/**
	 * Get Checkbox Control.
	 *
	 * This method is responsible for getting
	 * Checkbox controls.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed[] $arg  Checkbox args.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	protected function get_checkbox_control( $arg, $name ): string {
		$is_checked = ! empty( $this->get_setting( $name ) ) ? 'checked' : '';

		return sprintf(
			'<input
				name="%1$s"
				type="checkbox"
				%2$s
			/>',
			$name,
			$is_checked,
		);
	}

	/**
	 * Get Form Submit.
	 *
	 * This method is responsible for getting the
	 * Submit button.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_form_submit(): string {
		$heading      = $this->options['submit']['heading'] ?? '';
		$button_name  = $this->options['submit']['button']['name'] ?? '';
		$button_label = $this->options['submit']['button']['label'] ?? '';
		$nonce_name   = $this->options['submit']['nonce']['name'] ?? '';
		$nonce_action = $this->options['submit']['nonce']['action'] ?? '';

		$submit = [
			'heading'      => $heading,
			'button_name'  => $button_name,
			'button_label' => $button_label,
			'nonce_fields' => wp_nonce_field( $nonce_action, $nonce_name, true, false ),
		];

		return vsprintf(
			'<div class="badasswp-form-group">
				<p class="badasswp-form-group-heading">
					<strong>%s</strong>
				</p>
				<p class="badasswp-form-group-heading">
					<button name="%s" type="submit" class="button button-primary">
						<span>%s</span>
					</button>
				</p>
				%s
			</div>',
			$submit
		);
	}

	/**
	 * Get Form Notice.
	 *
	 * This method is responsible for getting the
	 * Form notice.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_form_notice(): string {
		$notice_label = $this->options['notice']['label'] ?? '';
		$button_name  = $this->options['submit']['button']['name'] ?? '';
		$nonce_name   = $this->options['submit']['nonce']['name'] ?? '';
		$nonce_action = $this->options['submit']['nonce']['action'] ?? '';

		if (
			isset( $_POST[ $button_name ] ) &&
			wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST[ $nonce_name ] ?? '' ) ),
				$nonce_action
			)
		) {
			return sprintf(
				'<div class="badasswp-form-notice">
					<span>%s</span>
				</div>',
				$notice_label
			);
		}

		return '';
	}
}
