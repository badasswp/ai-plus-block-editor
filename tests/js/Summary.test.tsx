import React from 'react';
import '@testing-library/jest-dom';
import { render, screen } from '@testing-library/react';

import { useSelect, useDispatch } from '@wordpress/data';

import Summary from '../../src/components/Summary';

jest.mock( '@wordpress/i18n', () => ( {
	__: jest.fn( ( arg ) => arg ),
} ) );

jest.mock( '@wordpress/data', () => ( {
	useSelect: jest.fn(),
	useDispatch: jest.fn(),
} ) );

jest.mock( '@wordpress/notices', () => ( {
	store: 'core/notices',
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

describe( 'Summary', () => {
	beforeEach( () => {
		( useSelect as jest.Mock ).mockReturnValue( {
			postId: 1,
			postContent: 'Hello World',
			postSummary: 'AI generated Summary...',
			notices: [],
		} );

		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: jest.fn(),
			savePost: jest.fn(),
			removeNotice: jest.fn(),
			createErrorNotice: jest.fn(),
		} );
	} );

	afterEach( () => {
		jest.clearAllMocks();
	} );
	it( 'renders the Summary textarea and 2 buttons', () => {
		const { container } = render( <Summary /> );

		// Expect Component to look like so:
		expect( container.innerHTML ).toBe(
			`<p><strong>Summary</strong></p><textarea rows="4">AI generated Summary...</textarea><div class="apbe-button-group"><button class="primary">Generate</button><button class="secondary">Icon</button></div>`
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
