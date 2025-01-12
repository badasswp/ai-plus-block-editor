import React from 'react';
import { render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';

import Toast from '../../src/components/Toast';

describe( 'Toast', () => {
  it( 'renders the component with correct text', () => {
    const { container } = render( <Toast isLoading={true} message="AI generated text is loading..." /> );

    // Expect Component to look like so:
    expect( container.innerHTML ).toBe(
      `<div class="apbe-toast" role="alert"><span>AI generated text is loading...</span></div>`
    );

    // Assert the toast is rendered and has a class name.
    const toastContainer = screen.getByRole( 'alert' );
    expect( toastContainer ).toHaveClass( 'apbe-toast' );
    expect( toastContainer ).toBeInTheDocument();

    const spanContainer = screen.getByText( 'AI generated text is loading...' );
    expect( spanContainer ).toBeInTheDocument();
  } );
} );
