<?php

namespace App\Exceptions;

/**
 * Exception thrown when validation fails for user input.
 */
class CustomValidationException extends BaseException
{
    /**
     * The error code for this exception.
     *
     * @var string
     */
    protected string $errorCode = 'VALIDATION_FAILED';

    /**
     * The validation errors.
     *
     * @var array
     */
    protected array $errors = [];

    /**
     * Create a new validation exception instance.
     *
     * @param string $message
     * @param array $errors
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $context
     * @return void
     */
    public function __construct(
        string $message = "The given data was invalid",
        array $errors = [],
        int $code = 422,
        \Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous, $context);
        $this->errors = $errors;
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Set the validation errors.
     *
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }
}
