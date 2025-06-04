<?php

namespace App\Exceptions;

/**
 * Exception thrown when a request is malformed or contains invalid data.
 */
class BadRequestException extends BaseException
{
    /**
     * The error code for this exception.
     *
     * @var string
     */
    protected string $errorCode = 'BAD_REQUEST';

    /**
     * Create a new bad request exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $context
     * @return void
     */
    public function __construct(
        string $message = "The request contains invalid data",
        int $code = 400,
        \Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
