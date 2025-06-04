<?php

namespace App\Exceptions;

use Exception;

/**
 * Base exception class for all custom exceptions in the application.
 *
 * This class provides common functionality for all custom exceptions,
 * such as error codes and additional context data.
 */
class BaseException extends Exception
{
    /**
     * The error code for this exception.
     *
     * @var string
     */
    protected string $errorCode = 'GENERAL_ERROR';

    /**
     * Additional context data for this exception.
     *
     * @var array
     */
    protected array $context = [];

    /**
     * Create a new exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $context Additional context data
     * @return void
     */
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get the error code for this exception.
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Set the error code for this exception.
     *
     * @param string $errorCode
     * @return $this
     */
    public function setErrorCode(string $errorCode): self
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * Get the context data for this exception.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Add context data to this exception.
     *
     * @param array $context
     * @return $this
     */
    public function addContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }
}
