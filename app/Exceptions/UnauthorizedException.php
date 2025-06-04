<?php

namespace App\Exceptions;

/**
 * Exception thrown when a user is not authenticated.
 */
class UnauthorizedException extends BaseException
{
    /**
     * The error code for this exception.
     *
     * @var string
     */
    protected string $errorCode = 'UNAUTHORIZED';

    /**
     * Create a new unauthorized exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $context
     * @return void
     */
    public function __construct(
        string $message = "Authentication is required to access this resource",
        int $code = 401,
        \Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
