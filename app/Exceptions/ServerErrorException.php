<?php

namespace App\Exceptions;

/**
 * Exception thrown when an internal server error occurs.
 */
class ServerErrorException extends BaseException
{
    /**
     * The error code for this exception.
     *
     * @var string
     */
    protected string $errorCode = 'SERVER_ERROR';

    /**
     * Create a new server error exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $context
     * @return void
     */
    public function __construct(
        string $message = "An internal server error occurred",
        int $code = 500,
        \Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
