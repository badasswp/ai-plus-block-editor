import { __ } from '@wordpress/i18n';
import { select } from '@wordpress/data';
import { Button, TextareaControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

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
const Headline = () => {
  const[ headline, setHeadline ] = useState( '' );
  const postID = select('core/editor').getCurrentPostId();

  return (
    <>
      <p><strong>{ __( 'Headline', 'ai-plus-block-editor' ) }</strong></p>
      <TextareaControl
        rows={ 2 }
        value={ headline }
        onChange={ e => console.log(e) }
      />
      <Button
        variant="primary"
        onClick={ () => { } }
      >
        { __( 'Generate', 'ai-plus-block-editor' ) }
      </Button>
  </>
  )
}

export default Headline;
