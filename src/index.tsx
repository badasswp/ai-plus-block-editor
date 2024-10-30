import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { select, dispatch } from '@wordpress/data';
import { BlockControls } from '@wordpress/block-editor';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { ToolbarGroup, ToolbarDropdownMenu } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

const getCasualTone = async () => {
  const { getCurrentPostId } = select('core/editor');
  const { updateBlockAttributes } = dispatch('core/block-editor') as any;
  const {
    getSelectedBlock,
    getSelectedBlockClientId,
  } = select('core/block-editor');

  const payload = {
    path: '/ai-plus-block-editor/v1/heading',
    method: 'POST',
    data: {
      id: getCurrentPostId(),
      content: getSelectedBlock().attributes.content.text
    },
  };

  const { data } = await apiFetch(payload);

  updateBlockAttributes(
    getSelectedBlockClientId(),
    {
      content: data,
    }
  );
}

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
    const [tone, setTone] = useState('');

    useEffect(() => {
      if (tone) {
        getCasualTone();
      }
    }, [tone]);

    return (
      <Fragment>
        <BlockControls>
          <ToolbarGroup>
            <ToolbarDropdownMenu
              icon="superhero"
              label={ __( 'AI + Block Editor' ) }
              controls={
                [
                  {
                    icon: 'superhero',
                    title: 'Use Casual Tone',
                    onClick: () => {setTone('causal')},
                  },
                  {
                    icon: 'superhero',
                    title: 'Use Official Tone',
                    onClick: () => { },
                  },
                  {
                    icon: 'superhero',
                    title: 'Use Descriptive Tone',
                    onClick: () => { },
                  },
                  {
                    icon: 'superhero',
                    title: 'Use Calm Tone',
                    onClick: () => { },
                  },
                  {
                    icon: 'superhero',
                    title: 'Use Aggressive Tone',
                    onClick: () => { },
                  }
                ]
              }
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
