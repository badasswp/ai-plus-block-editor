import { __ } from '@wordpress/i18n';
import { select } from '@wordpress/data';
import { Button, TextControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';

/**
 * Slug.
 *
 * This Component returns the Slug
 * label and button.
 *
 * @since 1.0.0
 *
 * @returns {JSX.Element}
 */
const Slug = () => {
  const[ slug, setSlug ] = useState( '' );
  const[ isLoading, setIsLoading ] = useState( false );
  const { getCurrentPostId, getEditedPostContent } = select('core/editor');
  const content = getEditedPostContent();

  const handleClick = async () => {
    // Display Toast.
    setIsLoading( true );

    const { data } = await apiFetch(
      {
        path: '/ai-plus-block-editor/v1/sidebar',
        method: 'POST',
        data: {
          id: getCurrentPostId(),
          text: content.text || content,
          feature: 'slug'
        },
      }
    );

    let limit = 1;

    const displayWithEffect = setInterval( () => {
      // Clear Interval.
      if ( limit === data.length ) {
        clearInterval( displayWithEffect );
      }

      // Update the Slug field.
      setSlug( data.substring( 0, limit ) );
      limit++;
    }, 5 )

    // Hide Toast.
    setIsLoading( false );
  }

  return (
    <>
      <p><strong>{ __( 'Slug', 'ai-plus-block-editor' ) }</strong></p>
      <TextControl
        __nextHasNoMarginBottom
        placeholder="/your-article-slug"
        value={ slug }
        onChange={ () => { } }
      />
      <Button
        variant="primary"
        onClick={ handleClick }
      >
        { __( 'Generate', 'ai-plus-block-editor' ) }
      </Button>
      <Toast
        message={ __( 'AI is generating text, please hold on for a bit...' ) }
        isLoading={ isLoading }
      />
    </>
  )
}

export default Slug;
