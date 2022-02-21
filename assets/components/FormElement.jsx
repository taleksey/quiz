import React from 'react';
import classNames from 'classnames';

function InputFeedback({ error }) {
  return error ? <div className="d-block invalid-feedback">{error}</div> : null;
}

function RadioButton({
  field: {
    name, value, onChange, onBlur,
  },
  id,
  label,
  className,
  ...props
}) {
  return (
    <div>
      <input
        name={name}
        id={id}
        type="radio"
        value={value} // could be something else for output?
        onChange={onChange}
        onBlur={onBlur}
        className="radio-button"
        {...props}
      />
      <label htmlFor={id}>{label}</label>
    </div>
  );
}

function RadioButtonGroup({
  value,
  error,
  touched,
    label,
  className,
  children,
}) {
  const classes = classNames(
    'input-field',
    {
      'is-success': value || (!error && touched), // handle prefilled or user-filled
      'is-error': !!error && touched,
    },
    className,
  );
  return (
    <div className={classes}>
      <fieldset>
        <legend>{label}</legend>
        {children}
        {touched && <InputFeedback error={error} />}
      </fieldset>
    </div>
  );
}
export { RadioButton, RadioButtonGroup };
