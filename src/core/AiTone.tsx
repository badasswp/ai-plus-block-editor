import { __ } from '@wordpress/i18n';
import { verse } from '@wordpress/icons';
import { addFilter } from '@wordpress/hooks';
import { select, dispatch } from '@wordpress/data';
import { BlockControls } from '@wordpress/block-editor';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { ToolbarGroup, ToolbarDropdownMenu } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';
import { getBlockControlOptions } from '../utils';

import '../styles/app.scss';

/**
 * Filter Blocks with AI.
 *
 * @since 1.0.0
 *
 * @param {Object} settings  Original block settings
 * @returns {Object}         Filtered block settings
 */
export const filterBlockTypesWithAI = (settings: any) => {
  const { name, edit } = settings;

  if ( 'core/paragraph' !== name ) {
    return settings;
  }

  settings.edit = (props: any) => {
    const [tone, setTone] = useState( '' );
    const [isLoading, setIsLoading] = useState( false );

    /**
     * Get AI generated tone.
     *
     * @since 1.0.0
     *
     * @param {string} tone  AI tone sent to LLM endpoint.
     * @returns {void}
     */
    const getTone = async (tone: string) => {
      const { getCurrentPostId } = select( 'core/editor' );
      const { updateBlockAttributes } = dispatch( 'core/block-editor' ) as any;
      const { getSelectedBlock, getSelectedBlockClientId } = select( 'core/block-editor' );
      const { content } = getSelectedBlock().attributes;

      // Display Toast.
      setIsLoading( true );

      const response = await apiFetch(
        {
          path: '/ai-plus-block-editor/v1/tone',
          method: 'POST',
          data: {
            id: getCurrentPostId(),
            text: content.text || content,
            tone: tone
          },
        }
      );

    const { data } = response as any;

      let limit = 1;

      const displayWithEffect = setInterval( () => {
        // Clear Interval.
        if ( limit === data.length ) {
          clearInterval( displayWithEffect );
        }

        // Update Block Editor.
        updateBlockAttributes( getSelectedBlockClientId(), { content: data.substring( 0, limit ) } );
        limit++;
      }, 5 )

      // Hide Toast.
      setIsLoading( false );
    };

    useEffect(() => {
      if ( tone ) {
        getTone( tone );
      }
    }, [ tone ]);

    return (
      <Fragment>
        <Toast
          message={ __( 'AI is generating text, please hold on for a bit...' ) }
          isLoading={ isLoading }
        />
        <BlockControls>
          <ToolbarGroup>
            <ToolbarDropdownMenu
              icon={ verse }
              label={ __( 'AI + Block Editor' ) }
              controls={ getBlockControlOptions( setTone ) }
            />
          </ToolbarGroup>
        </BlockControls>
        { edit( props ) }
      </Fragment>
    );
  };

  return settings;
};

addFilter( 'blocks.registerBlockType', 'apbe/ai', filterBlockTypesWithAI );
