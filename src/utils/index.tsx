import { __ } from '@wordpress/i18n';
import { applyFilters } from '@wordpress/hooks';

type DropdownOption = {
	title: string;
	onClick: () => void;
};

/**
 * Is Animation Enabled.
 *
 * Check to see if Animation is enabled
 * for plugin.
 *
 * @since 1.9.0
 *
 * @return {boolean} Is Animation enabled.
 */
export const isAnimationEnabled = (): boolean => {
	const { isAnimationEnabled: isPluginAnimationEnabled } = apbe;

	if ( isPluginAnimationEnabled || null === isPluginAnimationEnabled ) {
		return true;
	}

	return false;
};

/**
 * Show Animated AI text.
 *
 * This function returns a promise that resolves
 * to the AI generated text when the Animation
 * responsible for showing same is completed.
 *
 * @since 1.9.0
 *
 * @param {string}   text     AI genereated Text.
 * @param {Function} callback Callback function.
 *
 * @return {Promise<void>}
 */
export const showAnimatedAiText = (
	text: string,
	callback: ( text: string ) => void
): Promise< string > => {
	let limit = 1;

	return new Promise( ( resolve ) => {
		const animatedTextInterval = setInterval( () => {
			if ( limit >= text.length ) {
				clearInterval( animatedTextInterval );
				resolve( text );
			}
			callback( text.substring( 0, limit ) );
			limit++;
		}, 5 );
	} );
};

/**
 * Get Options.
 *
 * This function filters the Block Menu AI Options
 * available when the user selects a block.
 *
 * @since 1.1.0
 *
 * @param {Function} getTone Function to get the tone.
 * @return {DropdownOption[]} Dropdown options.
 */
export const getBlockMenuOptions = ( getTone: Function ): DropdownOption[] => {
	const menu = [];

	/**
	 * Filter Menu Options.
	 *
	 * By default the passed object should contain
	 * menu options.
	 *
	 * @since 1.1.0
	 * @since 1.5.0 Pass in menu options before returning menu.
	 *
	 * @param {Object} menu Menu options.
	 * @return {Object}
	 */
	const options = applyFilters( 'apbe.blockMenuOptions', {
		casual: __( 'Use Casual Tone', 'ai-plus-block-editor' ),
		official: __( 'Use Official Tone', 'ai-plus-block-editor' ),
		descriptive: __( 'Use Descriptive Tone', 'ai-plus-block-editor' ),
		narrative: __( 'Use Narrative Tone', 'ai-plus-block-editor' ),
		aggressive: __( 'Use Aggressive Tone', 'ai-plus-block-editor' ),
	} );

	Object.keys( options ).forEach( ( key ) => {
		menu.push( {
			title: options[ key ],
			onClick: () => {
				getTone( key );
			},
		} );
	} );

	return menu;
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
