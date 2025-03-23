import React from 'react';
import { render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';

import Headline from '../../src/components/Headline';

jest.mock( '@wordpress/i18n', () => ( {
	__: jest.fn( ( arg ) => arg ),
} ) );

jest.mock( '@wordpress/components', () => ( {
	Button: jest.fn( ( { children, variant, onClick } ) => {
		return (
			<>
				<button className={ variant } onClick={ onClick }>
					{ children }
				</button>
			</>
		);
	} ),
	Icon: jest.fn( () => {
		return <>Icon</>;
	} ),
	TextareaControl: jest.fn(
		( { rows, value, onChange, __nextHasNoMarginBottom = true } ) => {
			return (
				__nextHasNoMarginBottom && (
					<>
						<textarea
							rows={ rows }
							onChange={ onChange }
							value={ value }
						/>
					</>
				)
			);
		}
	),
} ) );

jest.mock( '@wordpress/data', () => ( {
	dispatch: jest.fn( ( store ) =>
		store === 'core/editor'
			? {
					editPost: jest.fn(),
			  }
			: {}
	),
	select: jest.fn( ( store ) =>
		store === 'core/editor'
			? {
					savePost: jest.fn(),
					getCurrentPostId: jest.fn(),
					getEditedPostContent: jest.fn(),
					getEditedPostAttribute: jest.fn( ( attribute ) =>
						attribute === 'meta'
							? {
									apbe_headline: 'AI generated headline...',
							  }
							: {}
					),
			  }
			: {}
	),
} ) );

describe( 'Headline', () => {
	it( 'renders the Headline textarea and 2 buttons', () => {
		const { container } = render( <Headline /> );

		// Expect Component to look like so:
		expect( container.innerHTML ).toBe(
			`<p><strong>Headline</strong></p><textarea rows="4">AI generated headline...</textarea><div class="apbe-button-group"><button class="primary">Generate</button><button class="secondary">Icon</button></div>`
		);

		// Assert the Generate button is displayed.
		const generateButton = screen.getByText( 'Generate' );
		expect( generateButton ).toHaveClass( 'primary' );
		expect( generateButton ).toBeInTheDocument();
		expect( generateButton ).toBeInstanceOf( HTMLButtonElement );

		// Assert the Select button is displayed.
		const selectButton = screen.getByText( 'Icon' );
		expect( selectButton ).toHaveClass( 'secondary' );
		expect( selectButton ).toBeInTheDocument();
		expect( selectButton ).toBeInstanceOf( HTMLButtonElement );
	} );
} );
