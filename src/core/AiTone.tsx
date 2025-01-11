import { __ } from '@wordpress/i18n';
import { verse, rotateRight } from '@wordpress/icons';
import { addFilter } from '@wordpress/hooks';
import { select, dispatch } from '@wordpress/data';
import { BlockControls } from '@wordpress/block-editor';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { ToolbarGroup, ToolbarDropdownMenu } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import AiToast from '../components/Toast';
import options from '../utils/options';
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
    const [icon, setIcon] = useState( verse );
    const [tone, setTone] = useState( '' );
    const [isLoading, setIsLoading] = useState( false );
    const menu = [];

    Object.keys(options).forEach(key => {
      menu.push(
        {
          icon: verse,
          title: options[key],
          onClick: () => {
            setTone( key );
          },
        }
      )
    });

    const getTone = async (tone: string) => {
      const { getCurrentPostId } = select('core/editor');
      const { updateBlockAttributes } = dispatch('core/block-editor') as any;
      const { getSelectedBlock, getSelectedBlockClientId } = select('core/block-editor');
      const { content } = getSelectedBlock().attributes;

      // Display Toast.
      setIsLoading( true );

      const { data } = await apiFetch(
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

      updateBlockAttributes(
        getSelectedBlockClientId(),
        {
          content: data,
        }
      );

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
        <AiToast
          message={ __( 'AI is generating text, please hold on for a bit...' ) }
          isLoading={ isLoading }
        />
        <BlockControls>
          <ToolbarGroup>
            <ToolbarDropdownMenu
              icon={ icon }
              label={ __( 'AI + Block Editor' ) }
              controls={ menu }
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
