import '@testing-library/jest-dom';
import { act, render, fireEvent, waitFor } from '@testing-library/react';

import apiFetch from '@wordpress/api-fetch';
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

jest.mock( '@wordpress/api-fetch', () => jest.fn() );

describe( 'Summary', () => {
	beforeEach( () => {
		( useSelect as jest.Mock ).mockReturnValue( {
			postId: 1,
			postContent: 'Hello World',
			postSummary: 'AI generated summary...',
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

	it( 'renders Summary component', () => {
		const { container, getByText, getByRole } = render( <Summary /> );

		expect( container ).toMatchSnapshot();

		expect( getByText( 'AI generated summary...' ) ).toBeInTheDocument();
		expect( getByText( 'Summary' ) ).toBeVisible();
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
			jest.fn( () =>
				Promise.resolve( 'It is such a wonderful world, we live in...' )
			)
		);

		const { getByText, getByRole } = render( <Summary /> );

		expect( getByText( 'AI generated summary...' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 2 );
			expect(
				getByText( 'It is such a wonderful world, we live in...' )
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

		const { getByText, getByRole } = render( <Summary /> );

		expect( getByText( 'AI generated summary...' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect( mockCreateErrorNotice ).toHaveBeenCalledTimes( 1 );
			expect(
				getByText( 'AI generated summary...' )
			).toBeInTheDocument();
		} );
	} );

	it( 'saves the selected AI Summary', async () => {
		const mockEditPost = jest.fn();
		const mockSavePost = jest.fn();
		( useDispatch as jest.Mock ).mockReturnValue( {
			editPost: mockEditPost,
			savePost: mockSavePost,
		} );

		( apiFetch as unknown as jest.Mock ).mockImplementation(
			jest.fn( () =>
				Promise.resolve( 'It is such a wonderful world, we live in...' )
			)
		);

		const { getByText, getByRole } = render( <Summary /> );

		expect( getByText( 'AI generated summary...' ) ).toBeInTheDocument();

		const button = getByRole( 'button', { name: 'Generate' } );
		await act( async () => {
			fireEvent.click( button );
		} );

		await waitFor( () => {
			expect(
				getByText( 'It is such a wonderful world, we live in...' )
			).toBeInTheDocument();
		} );

		const icon = getByRole( 'button', { name: 'Icon' } );
		await act( async () => {
			fireEvent.click( icon );
		} );

		await waitFor( () => {
			expect( mockEditPost ).toHaveBeenCalledTimes( 4 );
			expect( mockSavePost ).toHaveBeenCalledTimes( 1 );
		} );
	} );
} );
