import '@testing-library/jest-dom';
import { act, render, fireEvent, waitFor } from '@testing-library/react';

import apiFetch from '@wordpress/api-fetch';
import { useDispatch } from '@wordpress/data';

import Switcher from '../../src/components/Switcher';

jest.mock( '@wordpress/i18n', () => ( {
	__: jest.fn( ( arg ) => arg ),
	sprintf: jest.fn(),
} ) );

jest.mock( '@wordpress/data', () => ( {
	useSelect: jest.fn(),
	useDispatch: jest.fn(),
} ) );

jest.mock( '@wordpress/notices', () => ( {
	store: 'core/notices',
} ) );

jest.mock( '@wordpress/components', () => ( {
	SelectControl: jest.fn( ( { label, value, options, onChange, id } ) => {
		return (
			<>
				<p>{ label }</p>
				<select
					onChange={ ( e ) => {
						const selectedText =
							e.target.options[ e.target.selectedIndex ].text;
						onChange( selectedText );
					} }
					value={ value }
					data-testid={ id }
				>
					{ options.map(
						(
							item: { label: string; value: string },
							index: number
						) => {
							return (
								<option key={ index } value={ item.value }>
									{ item.label }
								</option>
							);
						}
					) }
				</select>
			</>
		);
	} ),
} ) );

jest.mock( '@wordpress/api-fetch', () => jest.fn() );

describe( 'Switcher', () => {
	beforeEach( () => {
		( useDispatch as jest.Mock ).mockReturnValue( {
			createNotice: jest.fn(),
		} );

		( window as any ).apbe = {
			provider: 'OpenAI',
		};

		jest.spyOn( console, 'error' ).mockImplementation( () => {} );
	} );

	afterEach( () => {
		jest.clearAllMocks();
	} );

	it( 'renders Switcher component', () => {
		const { container, getByText, getByRole } = render( <Switcher /> );

		expect( container ).toMatchSnapshot();

		expect( getByText( 'Switch AI Provider' ) ).toBeVisible();
		expect( getByRole( 'combobox' ) ).toBeVisible();
		expect( getByText( 'ChatGPT' ) ).toBeVisible();
		expect( getByText( 'Gemini' ) ).toBeVisible();
		expect( getByText( 'DeepSeek' ) ).toBeVisible();
		expect( getByText( 'Grok' ) ).toBeVisible();
		expect( getByText( 'Claude' ) ).toBeVisible();
	} );

	it( 'makes an API call request on selection change', async () => {
		const mockCreateNotice = jest.fn();
		( useDispatch as jest.Mock ).mockReturnValue( {
			createNotice: mockCreateNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () => Promise.resolve( 'Gemini' ) )
		);

		const { getByTestId, getByText } = render( <Switcher /> );

		const select = getByTestId( 'switcher' );
		await act( async () => {
			fireEvent.change( select, { target: { value: 'Gemini' } } );
		} );

		await waitFor( () => {
			expect( getByText( 'Gemini' ) ).toBeInTheDocument();
			expect( mockCreateNotice ).toHaveBeenCalledTimes( 1 );
		} );
	} );

	it( 'throws error notice, on API fail', async () => {
		const mockCreateNotice = jest.fn();
		( useDispatch as jest.Mock ).mockReturnValue( {
			createNotice: mockCreateNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockRejectedValueOnce(
			new Error( 'WP website down...' )
		);

		const { getByTestId } = render( <Switcher /> );

		const select = getByTestId( 'switcher' );
		await act( async () => {
			fireEvent.change( select, { target: { value: 'Gemini' } } );
		} );

		await waitFor( () => {
			expect( mockCreateNotice ).toHaveBeenCalledTimes( 1 );
		} );
	} );
} );
