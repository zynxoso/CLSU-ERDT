<?php

namespace App\Exceptions;

/**
 * Exception thrown when a user is authenticated but does not have permission to access a resource.
 */
class ForbiddenException extends BaseException
{
    /**
     * The error code for this exception.
     *
     * @var string
     */
    protected string $errorCode = 'FORBIDDEN';

    /**
     * Create a new forbidden exception instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $context
     * @return void
     */
    public function __construct(
        string $message = "You do not have permission to access this resource",
        int $code = 403,
        \Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous, $context);
    }
}
