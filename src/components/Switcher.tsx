import { useState } from '@wordpress/element';
import { useDispatch } from '@wordpress/data';
import { __, sprintf } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';
import { store as noticesStore } from '@wordpress/notices';
import apiFetch from '@wordpress/api-fetch';

/**
 * Switcher.
 *
 * This Component handles the switching of AI providers,
 * for e.g. Open AI -> Gemini and so on.
 *
 * @since 1.5.0
 *
 * @return {JSX.Element} Switcher.
 */
const Switcher = (): JSX.Element => {
	const { createNotice } = useDispatch( noticesStore );
	const [ provider, setProvider ] = useState< string >( apbe.provider || '' );

	const handleChange = async ( value: string ) => {
		setProvider( value );

		try {
			const response: any = await apiFetch( {
				path: '/ai-plus-block-editor/v1/switcher',
				method: 'POST',
				data: {
					provider: value,
				},
			} );

			createNotice(
				'success',
				sprintf(
					// translators: %s: The snackbar message.
					__( 'Success! %s', 'ai-plus-block-editor' ),
					response.message
				),
				{
					isDismissible: true,
					id: 'apbe-success',
					type: 'snackbar',
				}
			);
		} catch ( e ) {
			createNotice(
				'error',
				sprintf(
					// translators: %s: The snackbar message.
					__( 'Error! %s', 'ai-plus-block-editor' ),
					e.message
				),
				{
					isDismissible: true,
					id: 'apbe-error',
					type: 'snackbar',
				}
			);
		}
	};

	return (
		<>
			<p>
				<strong>
					{ __( 'Switch AI Provider', 'ai-plus-block-editor' ) }
				</strong>
			</p>
			<SelectControl
				label={ null }
				value={ provider }
				options={ [
					{ label: 'ChatGPT', value: 'OpenAI' },
					{ label: 'Gemini', value: 'Gemini' },
					{ label: 'DeepSeek', value: 'DeepSeek' },
				] }
				onChange={ handleChange }
				id="switcher"
				__next40pxDefaultSize
				__nextHasNoMarginBottom
			/>
		</>
	);
};

export default Switcher;
