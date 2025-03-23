/**
 * Toast Component.
 *
 * This function returns a JSX component that is
 * a Toast notification.
 *
 * @since 1.1.0
 *
 * @param  props
 * @param  props.isLoading
 * @param  props.message
 *
 * @return {JSX.Element} Toast.
 */
const Toast = ( { isLoading, message } ): JSX.Element => {
	return (
		isLoading && (
			<div className="apbe-toast" role="alert">
				<span>{ message }</span>
			</div>
		)
	);
};

export default Toast;
