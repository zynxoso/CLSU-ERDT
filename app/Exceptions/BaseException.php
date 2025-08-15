<?php

namespace App\Exceptions;

use Exception;

/**
 * Base exception class para sa lahat ng custom exceptions sa application.
 *
 * Ang class na ito ay nagbibigay ng common functionality para sa lahat ng custom exceptions,
 * tulad ng error codes at additional context data.
 */
class BaseException extends Exception
{
    /**
     * Ang error code para sa exception na ito.
     *
     * @var string
     */
    protected string $errorCode = 'GENERAL_ERROR';

    /**
     * Additional context data para sa exception na ito.
     *
     * @var array
     */
    protected array $context = [];

    /**
     * Gumawa ng bagong exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $context Additional context data
     * @return void
     */
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Kunin ang error code para sa exception na ito.
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * I-set ang error code para sa exception na ito.
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
     * Kunin ang context data para sa exception na ito.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Magdagdag ng context data sa exception na ito.
     *
     * @param array $context
     * @return $this
     */
    public function addContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }

    /**
     * Kunin ang HTTP status code para sa exception na ito.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return 500; // Default to internal server error
    }
}
