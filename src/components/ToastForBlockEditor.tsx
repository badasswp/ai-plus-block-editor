/**
 * Toast Component.
 *
 * This function returns a JSX component that is
 * a Toast notification.
 *
 * @since 1.3.0
 *
 * @param  props
 * @param  props.isLoading
 * @param  props.message
 *
 * @return {JSX.Element} Toast.
 */
const ToastForBlockEditor = ( { message, isLoading } ): JSX.Element => {
	return (
		<div
			style={ {
				position: 'fixed',
				background: '#000',
				color: '#FFF',
				bottom: '7.5px',
				left: '15px',
				padding: '20px',
				borderRadius: '5px',
				fontSize: '13px',
				boxShadow: '0 0 15px rgba(0, 0, 0, 0.1)',
				opacity: isLoading ? 1 : 0,
				transition: 'opacity 0.3s ease-in-out',
			} }
		>
			{ message }
		</div>
	);
};

export default ToastForBlockEditor;
