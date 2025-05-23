import { __ } from '@wordpress/i18n';
import { applyFilters } from '@wordpress/hooks';

type DropdownOption = {
	title: string;
	onClick: () => void;
};

/**
 * Get Options.
 *
 * This function filters the Block Control Options
 * available when the user selects a block.
 *
 * @since 1.1.0
 *
 * @param {Function} setTone Subscribe to setTone setter method.
 * @return {DropdownOption[]} Dropdown options.
 */
export const getBlockMenuOptions = ( setTone: Function ): DropdownOption[] => {
	const menu = [];

	const options = {
		casual: __( 'Use Casual Tone', 'ai-plus-block-editor' ),
		official: __( 'Use Official Tone', 'ai-plus-block-editor' ),
		descriptive: __( 'Use Descriptive Tone', 'ai-plus-block-editor' ),
		narrative: __( 'Use Narrative Tone', 'ai-plus-block-editor' ),
		aggressive: __( 'Use Aggressive Tone', 'ai-plus-block-editor' ),
	};

	Object.keys( options ).forEach( ( key ) => {
		menu.push( {
			title: options[ key ],
			onClick: () => {
				setTone( key );
			},
		} );
	} );

	/**
	 * Filter Menu.
	 *
	 * By default the passed option should contain
	 * menu objects.
	 *
	 * @since 1.1.0
	 *
	 * @param {DropdownOption[]} menu Menu array containing menu objects.
	 * @return {DropdownOption[]}
	 */
	return applyFilters( 'apbe.blockMenuOptions', menu ) as DropdownOption[];
};

/**
 * Get Allowed Blocks.
 *
 * This function filters the allowed blocks
 * available to the AI tone feature.
 *
 * @since 1.4.0
 *
 * @return {string[]} Allowed blocks.
 */
export const getAllowedBlocks = (): string[] => {
	const allowedBlocks = [ 'core/paragraph' ];

	/**
	 * Filter Allowed Blocks.
	 *
	 * By default the passed option should contain
	 * allowed blocks.
	 *
	 * @since 1.4.0
	 *
	 * @param {string[]} allowedBlocks Allowed blocks array.
	 * @return {string[]}
	 */
	return applyFilters( 'apbe.allowedBlocks', allowedBlocks ) as string[];
};
