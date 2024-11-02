import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { select, dispatch } from '@wordpress/data';
import { BlockControls } from '@wordpress/block-editor';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { ToolbarGroup, ToolbarDropdownMenu, IconType } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import options from './options';

/**
 * Filter Blocks with AI.
 *
 * @since 1.0.0
 *
 * @param {Object} settings  Original block settings
 * @returns {Object}         Filtered block settings
 */
export const filterBlockTypesWithAI = (settings: any) => {
  const { edit } = settings;

  settings.edit = (props: any) => {
    const [icon, setIcon] = useState<IconType>('superhero');
    const [tone, setTone] = useState('');
    const menu = [];

    Object.keys(options).forEach(key => {
      menu.push(
        {
          icon: 'superhero',
          title: options[key],
          onClick: () => {
            setTone(key);
          },
        }
      )
    });

    const getTone = async (tone: string) => {
      const { getCurrentPostId } = select('core/editor');
      const { updateBlockAttributes } = dispatch('core/block-editor') as any;
      const {
        getSelectedBlock,
        getSelectedBlockClientId,
      } = select('core/block-editor');

      const payload = {
        path: '/ai-plus-block-editor/v1/tone',
        method: 'POST',
        data: {
          id: getCurrentPostId(),
          content: getSelectedBlock().attributes.content.text,
          tone: tone
        },
      };

      setIcon('format-status');

      const { data } = await apiFetch(payload);

      updateBlockAttributes(
        getSelectedBlockClientId(),
        {
          content: data,
        }
      );

      setIcon('superhero');
    };

    useEffect(() => {
      if (tone) {
        getTone(tone);
      }
    }, [tone]);

    return (
      <Fragment>
        <BlockControls>
          <ToolbarGroup>
            <ToolbarDropdownMenu
              icon={icon}
              label={ __( 'AI + Block Editor' ) }
              controls={menu}
            />
          </ToolbarGroup>
        </BlockControls>
        { edit( props ) }
      </Fragment>
    );
  };

  return settings;
};

addFilter('blocks.registerBlockType', 'apbe/ai', filterBlockTypesWithAI);
