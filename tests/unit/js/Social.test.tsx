import '@testing-library/jest-dom';
import { act, render, fireEvent, waitFor } from '@testing-library/react';

import apiFetch from '@wordpress/api-fetch';
import { useSelect, useDispatch } from '@wordpress/data';

import Social from '../../src/components/Social';

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

	TextareaControl: jest.fn( ( { rows, value, onChange } ) => {
		return (
			<textarea
				rows={ rows }
				onChange={ ( e ) => onChange( e.target.value ) }
				value={ value }
			/>
		);
	} ),
} ) );

jest.mock( '@wordpress/api-fetch', () => jest.fn() );

describe( 'Social', () => {
	beforeEach( () => {
		( useSelect as jest.Mock ).mockReturnValue( {
			postId: 1,
			postContent: 'Hello World',
			postSocial: '#hello, #world',
			notices: [],
		} );

		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: jest.fn(),
			savePost: jest.fn(),
			removeNotice: jest.fn(),
			createErrorNotice: jest.fn(),
		} );

		jest.spyOn( console, 'error' ).mockImplementation( () => {} );
	} );

	afterEach( () => {
		jest.clearAllMocks();
	} );

	it( 'renders Social component', () => {
		const { container, getByText, getByRole } = render( <Social /> );

		expect( container ).toMatchSnapshot();

		expect( getByText( '#hello, #world' ) ).toBeInTheDocument();
		expect( getByText( 'Social Media Hashtags' ) ).toBeVisible();
		expect( getByRole( 'button', { name: 'Icon' } ) ).toBeVisible();
		expect( getByRole( 'button', { name: 'Icon' } ) ).toHaveClass(
			'secondary'
		);
		expect( getByRole( 'button', { name: 'Generate' } ) ).toBeVisible();
		expect( getByRole( 'button', { name: 'Generate' } ) ).toHaveClass(
			'primary'
		);
	} );

	it( 'renders fetched API data from AI LLM', async () => {
		const mockEditPost = jest.fn();
		const mockCreateNotice = jest.fn();
		const mockRemoveNotice = jest.fn();

		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
			createNotice: mockCreateNotice,
			removeNotice: mockRemoveNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () =>
				Promise.resolve( '#what, #beautiful, #wonderful, #world' )
			)
		);

		const { getByText, getByRole } = render( <Social /> );

		expect( getByText( '#hello, #world' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 1 );
			expect(
				getByText( '#what, #beautiful, #wonderful, #world' )
			).toBeInTheDocument();
		} );
	} );

	it( 'renders error notice on API fail', async () => {
		const mockCreateErrorNotice = jest.fn();
		const mockCreateNotice = jest.fn();
		const mockRemoveNotice = jest.fn();

		( useDispatch as jest.Mock ).mockReturnValue( {
			createErrorNotice: mockCreateErrorNotice,
			createNotice: mockCreateNotice,
			removeNotice: mockRemoveNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockRejectedValueOnce(
			new Error( 'AI LLM down...' )
		);

		const { getByText, getByRole } = render( <Social /> );

		expect( getByText( '#hello, #world' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockCreateErrorNotice ).toHaveBeenCalledTimes( 1 );
			expect( getByText( '#hello, #world' ) ).toBeInTheDocument();
		} );
	} );

	it( 'saves the selected AI Hashtags', async () => {
		const mockEditPost = jest.fn();
		const mockSavePost = jest.fn();
		const mockCreateNotice = jest.fn();
		const mockRemoveNotice = jest.fn();
		const mockCreateErrorNotice = jest.fn();

		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
			savePost: mockSavePost,
			createNotice: mockCreateNotice,
			removeNotice: mockRemoveNotice,
			createErrorNotice: mockCreateErrorNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () =>
				Promise.resolve( '#what, #beautiful, #wonderful, #world' )
			)
		);

		const { getByText, getByRole } = render( <Social /> );

		expect( getByText( '#hello, #world' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 1 );
			expect(
				getByText( '#what, #beautiful, #wonderful, #world' )
			).toBeInTheDocument();
		} );

		const icon = getByRole( 'button', { name: 'Icon' } );
		await act( async () => {
			fireEvent.click( icon );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 2 );
			expect( mockSavePost ).toHaveBeenCalledTimes( 1 );
		} );
	} );
} );
