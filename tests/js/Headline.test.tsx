import '@testing-library/jest-dom';
import { act, render, fireEvent, waitFor } from '@testing-library/react';

import apiFetch from '@wordpress/api-fetch';
import { useSelect, useDispatch } from '@wordpress/data';

import Headline from '../../src/components/Headline';

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
			<>
				<textarea
					rows={ rows }
					onChange={ ( e ) => onChange( e.target.value ) }
					value={ value }
				/>
			</>
		);
	} ),
} ) );

jest.mock( '@wordpress/api-fetch', () => jest.fn() );

describe( 'Headline', () => {
	beforeEach( () => {
		( useSelect as jest.Mock ).mockReturnValue( {
			postId: 1,
			postContent: 'Hello World',
			postHeadline: 'AI generated headline...',
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

	it( 'renders Headline component', () => {
		const { container, getByText, getByRole } = render( <Headline /> );

		expect( container ).toMatchSnapshot();

		expect( getByText( 'AI generated headline...' ) ).toBeInTheDocument();
		expect( getByText( 'Headline' ) ).toBeVisible();
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
		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () => Promise.resolve( 'What a Wonderful World!' ) )
		);

		const { getByText, getByRole } = render( <Headline /> );

		expect( getByText( 'AI generated headline...' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 1 );
			expect(
				getByText( 'What a Wonderful World!' )
			).toBeInTheDocument();
		} );
	} );

	it( 'renders error notice on API fail', async () => {
		const mockCreateErrorNotice = jest.fn();
		( useDispatch as jest.Mock ).mockReturnValue( {
			createErrorNotice: mockCreateErrorNotice,
		} );

		( apiFetch as unknown as jest.Mock ).mockRejectedValueOnce(
			new Error( 'AI LLM down...' )
		);

		const { getByText, getByRole } = render( <Headline /> );

		expect( getByText( 'AI generated headline...' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockCreateErrorNotice ).toHaveBeenCalledTimes( 1 );
			expect(
				getByText( 'AI generated headline...' )
			).toBeInTheDocument();
		} );
	} );

	it( 'saves the selected AI Headline', async () => {
		const mockEditPost = jest.fn();
		const mockSavePost = jest.fn();
		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
			savePost: mockSavePost,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () => Promise.resolve( 'What a Wonderful World!' ) )
		);

		const { getByText, getByRole } = render( <Headline /> );

		expect( getByText( 'AI generated headline...' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 1 );
			expect(
				getByText( 'What a Wonderful World!' )
			).toBeInTheDocument();
		} );

		const icon = getByRole( 'button', { name: 'Icon' } );
		await act( async () => {
			fireEvent.click( icon );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 25 );
			expect( mockSavePost ).toHaveBeenCalledTimes( 1 );
		} );
	} );
} );
