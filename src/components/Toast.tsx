/**
 * Toast Component.
 *
 * This function returns a JSX component that is
 * a Toast notification.
 *
 * @since 1.0.0
 *
 * @returns {JSX.Element}
 */
const Toast = ({ isLoading, message }): JSX.Element => {
  return (
    isLoading && (
      <div className="apbe-toast" role="alert">
        <span>{ message }</span>
      </div>
    )
  );
};

export default Toast;
