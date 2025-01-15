import { __ } from '@wordpress/i18n';
import { select } from '@wordpress/data';
import { Button, TextareaControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

import Toast from '../components/Toast';

/**
 * Headline.
 *
 * This Component returns the Headline
 * label and button.
 *
 * @since 1.0.0
 *
 * @returns {JSX.Element}
 */
const Headline = (): JSX.Element => {
  const[ headline, setHeadline ] = useState( '' );
  const[ isLoading, setIsLoading ] = useState( false );
  const { getCurrentPostId, getEditedPostContent } = select('core/editor');
  const content = getEditedPostContent();

  const handleClick = async () => {
    // Display Toast.
    setIsLoading( true );

    const response = await apiFetch(
      {
        path: '/ai-plus-block-editor/v1/sidebar',
        method: 'POST',
        data: {
          id: getCurrentPostId(),
          text: content.text || content,
          feature: 'headline'
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

      // Update the Headline.
      setHeadline( data.substring( 0, limit ) );
      limit++;
    }, 5 )

    // Hide Toast.
    setIsLoading( false );
  }

  return (
    <>
      <p><strong>{ __( 'Headline', 'ai-plus-block-editor' ) }</strong></p>
      <TextareaControl
        rows={ 4 }
        value={ headline }
        onChange={ e => console.log(e) }
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

export default Headline;
