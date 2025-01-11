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
 * @returns {DropdownOption[]}
 */
export const getBlockControlOptions = (setTone: Function): DropdownOption[] => {
  const menu = [];

  const options = {
    casual: __( 'Use Casual Tone', 'ai-plus-block-editor' ),
    official: __( 'Use Official Tone', 'ai-plus-block-editor' ),
    descriptive: __( 'Use Descriptive Tone', 'ai-plus-block-editor' ),
    narrative: __( 'Use Narrative Tone', 'ai-plus-block-editor' ),
    aggressive: __( 'Use Aggressive Tone', 'ai-plus-block-editor' ),
  };

  Object.keys( options ).forEach(( key ) => {
    menu.push(
      {
        title: options[ key ],
        onClick: () => {
          setTone( key );
        },
      }
    )
  });

  /**
   * Filter Menu.
   *
   * By default the passed option should contain
   * menu objects.
   *
   * @since 1.1.0
   *
   * @param {DropdownOption[]} menu Menu array containing menu objects.
   * @returns {DropdownOption[]}
   */
  return applyFilters( 'apbe.blockControlOptions', menu ) as DropdownOption[];
}
